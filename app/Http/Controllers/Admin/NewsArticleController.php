<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Category; // Tambahkan ini
use App\Models\NewsArticle; // Tambahkan ini

class NewsArticleController extends Controller
{
    // Constructor untuk middleware permission
    public function __construct()
    {
        $this->middleware(['permission:create news'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:edit news'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delete news'], ['only' => ['destroy']]);
        $this->middleware(['permission:publish news'], ['only' => ['publish']]); // Asumsikan ada method publish
    }

    public function index()
    {
        // Tampilkan berita berdasarkan role
        if (auth()->user()->hasRole('wartawan')) {
            $newsArticles = auth()->user()->newsArticles()->latest()->paginate(10);
        } else {
            $newsArticles = NewsArticle::latest()->paginate(10);
        }
        return view('admin.news_articles.index', compact('newsArticles'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.news_articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        $article = NewsArticle::create([
            'user_id' => auth()->id(),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']), // Otomatis buat slug
            'content' => $validated['content'],
            'image' => $imagePath,
            'status' => 'draft', // Default saat membuat
        ]);

        return redirect()->route('admin.news_articles.index')->with('success', 'Berita berhasil ditambahkan!');
    }

    public function edit(NewsArticle $newsArticle)
    {
        // Hanya izinkan penulis asli atau editor/admin untuk mengedit
        if (auth()->user()->id !== $newsArticle->user_id && !auth()->user()->hasAnyRole(['admin', 'editor'])) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit berita ini.');
        }

        $categories = Category::all();
        return view('admin.news_articles.edit', compact('newsArticle', 'categories'));
    }

    public function update(Request $request, NewsArticle $newsArticle)
    {
         // Hanya izinkan penulis asli atau editor/admin untuk mengedit
        if (auth()->user()->id !== $newsArticle->user_id && !auth()->user()->hasAnyRole(['admin', 'editor'])) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit berita ini.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'in:draft,published', // Hanya admin/editor yang bisa ubah status ke published
        ]);

        // Hapus gambar lama jika ada gambar baru diunggah
        if ($request->hasFile('image')) {
            if ($newsArticle->image) {
                Storage::disk('public')->delete($newsArticle->image);
            }
            $imagePath = $request->file('image')->store('news_images', 'public');
            $validated['image'] = $imagePath;
        }

        // Izinkan perubahan status hanya untuk editor atau admin
        if (!auth()->user()->hasAnyRole(['admin', 'editor'])) {
            unset($validated['status']); // Jangan izinkan wartawan mengubah status
        } else {
            if ($validated['status'] == 'published' && is_null($newsArticle->published_at)) {
                $validated['published_at'] = now(); // Set tanggal publikasi saat pertama kali diterbitkan
            } elseif ($validated['status'] == 'draft' && !is_null($newsArticle->published_at)) {
                $validated['published_at'] = null; // Hapus tanggal publikasi jika kembali ke draft
            }
        }


        $newsArticle->update($validated);

        return redirect()->route('admin.news_articles.index')->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy(NewsArticle $newsArticle)
    {
        // Hanya izinkan penulis asli atau admin untuk menghapus
        if (auth()->user()->id !== $newsArticle->user_id && !auth()->user()->hasRole('admin')) {
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
        $this->authorize('publish news'); // Menggunakan policy atau gate jika sudah diatur
        $newsArticle->update(['status' => 'published', 'published_at' => now()]);
        return redirect()->back()->with('success', 'Berita berhasil diterbitkan!');
    }

    public function unpublish(NewsArticle $newsArticle)
    {
        $this->authorize('publish news');
        $newsArticle->update(['status' => 'draft', 'published_at' => null]);
        return redirect()->back()->with('success', 'Berita berhasil ditarik!');
    }
}