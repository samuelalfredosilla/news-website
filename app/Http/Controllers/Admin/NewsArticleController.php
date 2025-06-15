<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle; // Pastikan ini diimpor
use App\Models\Category;    // Pastikan ini diimpor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk upload/hapus gambar
use Illuminate\Support\Str;             // Untuk slug
use Illuminate\Support\Facades\Auth;    // Untuk mendapatkan user yang login

class NewsArticleController extends Controller
{
    // Constructor untuk middleware permission
    public function __construct()
    {
        // Middleware permissions untuk otorisasi akses ke method controller
        // Ini adalah lapisan keamanan utama.
        $this->middleware(['permission:create news'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:edit news'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delete news'], ['only' => ['destroy']]);
        $this->middleware(['permission:publish news'], ['only' => ['publish', 'unpublish']]);
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('wartawan')) {
            // Wartawan hanya melihat berita yang dia tulis
            $newsArticles = $user->newsArticles()->latest()->paginate(10);
        } else {
            // Admin dan Editor melihat semua berita
            $newsArticles = NewsArticle::latest()->paginate(10);
        }

        return view('admin.news_articles.index', compact('newsArticles'));
    }

    public function create()
    {
        $categories = Category::all(); // Ambil semua kategori untuk dropdown
        return view('admin.news_articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048', // Gambar opsional, maks 2MB
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        $article = NewsArticle::create([
            'user_id' => Auth::id(), // Penulis berita adalah user yang sedang login
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']), // Otomatis buat slug dari judul
            'content' => $validated['content'],
            'image' => $imagePath,
            'status' => 'draft', // Berita baru selalu berstatus draft
        ]);

        return redirect()->route('admin.news_articles.index')->with('success', 'Berita berhasil ditambahkan dan berstatus Draft.');
    }

    public function show(NewsArticle $newsArticle)
    {
        // Biasanya tidak diperlukan di admin panel, berita langsung diedit dari daftar
        return redirect()->route('admin.news_articles.edit', $newsArticle);
    }

    public function edit(NewsArticle $newsArticle)
    {
        $user = Auth::user();

        // Otorisasi:
        // Admin bisa mengedit semua berita
        // Editor bisa mengedit semua berita
        // Wartawan hanya bisa mengedit beritanya sendiri
        if (!$user->hasAnyRole(['admin', 'editor']) && $user->id !== $newsArticle->user_id) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit berita ini.');
        }

        // Jika wartawan mencoba mengedit berita yang sudah diterbitkan, blokir
        if ($user->hasRole('wartawan') && $newsArticle->status == 'published') {
            abort(403, 'Wartawan tidak bisa mengedit berita yang sudah diterbitkan.');
        }

        $categories = Category::all();
        return view('admin.news_articles.edit', compact('newsArticle', 'categories'));
    }

    public function update(Request $request, NewsArticle $newsArticle)
    {
        $user = Auth::user();

        // Otorisasi update:
        if (!$user->hasAnyRole(['admin', 'editor']) && $user->id !== $newsArticle->user_id) {
            abort(403, 'Anda tidak memiliki izin untuk memperbarui berita ini.');
        }
        // Jika wartawan mencoba update berita yang sudah diterbitkan, blokir
        if ($user->hasRole('wartawan') && $newsArticle->status == 'published') {
            abort(403, 'Wartawan tidak bisa memperbarui berita yang sudah diterbitkan.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            // Status hanya bisa diubah oleh Admin atau Editor
            'status' => Rule::in(['draft', 'published'])->nullable(), // nullable karena wartawan tidak akan mengirim ini
        ]);

        // Penanganan Gambar
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($newsArticle->image) {
                Storage::disk('public')->delete($newsArticle->image);
            }
            $imagePath = $request->file('image')->store('news_images', 'public');
            $validated['image'] = $imagePath;
        } elseif ($request->boolean('remove_image')) { // Tambahkan checkbox di form untuk hapus gambar
             if ($newsArticle->image) {
                Storage::disk('public')->delete($newsArticle->image);
                $validated['image'] = null;
            }
        }


        // Penanganan Status dan published_at
        // Hanya Admin atau Editor yang dapat mengubah status
        if ($user->hasAnyRole(['admin', 'editor'])) {
            if ($request->filled('status')) { // Jika status dikirimkan dari form (berarti admin/editor)
                $oldStatus = $newsArticle->status;
                $newStatus = $validated['status'];

                if ($newStatus == 'published' && $oldStatus == 'draft') {
                    $validated['published_at'] = now(); // Set tanggal publikasi saat pertama kali diterbitkan
                } elseif ($newStatus == 'draft' && $oldStatus == 'published') {
                    $validated['published_at'] = null; // Hapus tanggal publikasi jika kembali ke draft
                }
            } else {
                // Jika status tidak dikirimkan (misal oleh wartawan), jangan ubah status yang sudah ada
                unset($validated['status']);
                unset($validated['published_at']);
            }
        } else {
            // Jika bukan admin/editor, pastikan status tidak bisa diubah melalui request
            unset($validated['status']);
            unset($validated['published_at']);
        }

        $newsArticle->update($validated);

        return redirect()->route('admin.news_articles.index')->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy(NewsArticle $newsArticle)
    {
        $user = Auth::user();

        // Otorisasi delete:
        // Admin bisa menghapus semua berita
        // Editor bisa menghapus semua berita (karena memiliki 'delete news')
        // Wartawan hanya bisa menghapus beritanya sendiri
        if (!$user->hasAnyRole(['admin', 'editor']) && $user->id !== $newsArticle->user_id) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus berita ini.');
        }

        if ($newsArticle->image) {
            Storage::disk('public')->delete($newsArticle->image);
        }
        $newsArticle->delete();

        return redirect()->route('admin.news_articles.index')->with('success', 'Berita berhasil dihapus!');
    }

    // Contoh fungsi publish/unpublish terpisah jika Anda ingin
    public function publish(NewsArticle $newsArticle)
    {
        // Middleware 'permission:publish news' sudah melindungi ini,
        // tapi validasi ulang untuk memastikan tidak ada celah.
        if (!Auth::user()->can('publish news')) {
            abort(403, 'Anda tidak memiliki izin untuk menerbitkan berita.');
        }

        if ($newsArticle->status == 'draft') {
            $newsArticle->update([
                'status' => 'published',
                'published_at' => now(),
            ]);
            return redirect()->back()->with('success', 'Berita berhasil diterbitkan!');
        }

        return redirect()->back()->with('error', 'Berita sudah diterbitkan.');
    }

    public function unpublish(NewsArticle $newsArticle)
    {
        if (!Auth::user()->can('publish news')) {
            abort(403, 'Anda tidak memiliki izin untuk menarik berita.');
        }

        if ($newsArticle->status == 'published') {
            $newsArticle->update([
                'status' => 'draft',
                'published_at' => null,
            ]);
            return redirect()->back()->with('success', 'Berita berhasil ditarik dan berstatus Draft.');
        }

        return redirect()->back()->with('error', 'Berita sudah berstatus Draft.');
    }
}