<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category; // Pastikan model Category diimpor
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Untuk slug
use Illuminate\Validation\Rule; // Untuk validasi unique saat update

class CategoryController extends Controller
{
    public function __construct()
    {
        // Hanya user dengan permission 'manage categories' yang bisa mengakses controller ini.
        // Ini memastikan hanya Admin dan Editor yang punya akses.
        $this->middleware(['permission:manage categories']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::paginate(10); // Ambil semua kategori dengan pagination
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name', // Nama harus unik
        ]);

        // Slug akan otomatis dibuat oleh mutator di model Category
        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Display the specified resource. (Optional, can be removed if not needed)
     */
    public function show(Category $category)
    {
        // Biasanya tidak diperlukan di admin panel, kategori langsung diedit dari daftar
        return redirect()->route('admin.categories.edit', $category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id), // Unik, kecuali untuk kategori ini sendiri
            ],
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}