@extends('layouts.admin')

@section('page_title', 'Edit Berita: ' . $newsArticle->title)

@section('admin_content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Edit Detail Berita</h3>
        </div>
        <form action="{{ route('admin.news_articles.update', $newsArticle->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="title">Judul Berita:</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $newsArticle->title) }}" required>
                    @error('title')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category_id">Kategori:</label>
                    <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $newsArticle->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image">Gambar Sampul:</label>
                    @if($newsArticle->image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($newsArticle->image) }}" alt="Gambar saat ini" style="max-width: 200px; height: auto;">
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1">
                                <label class="form-check-label" for="remove_image">Hapus Gambar Ini</label>
                            </div>
                        </div>
                    @endif
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="image" id="image" class="custom-file-input @error('image') is-invalid @enderror">
                            <label class="custom-file-label" for="image">Pilih file baru</label>
                        </div>
                    </div>
                    <small class="form-text text-muted">Maks. 2MB, format: jpg, jpeg, png, gif. Unggah untuk mengganti gambar.</small>
                    @error('image')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content">Isi Berita:</label>
                    <textarea name="content" id="content" rows="10" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $newsArticle->content) }}</textarea>
                    @error('content')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                    {{-- TODO: Integrasikan WYSIWYG editor seperti TinyMCE atau CKEditor di sini --}}
                </div>

                @if(Auth::user()->hasAnyRole(['admin', 'editor'])) {{-- Hanya admin/editor yang bisa mengubah status --}}
                <div class="form-group">
                    <label for="status">Status Berita:</label>
                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                        <option value="draft" {{ old('status', $newsArticle->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $newsArticle->status) == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                @else
                    {{-- Tampilkan status saat ini jika bukan admin/editor, tanpa bisa diubah --}}
                    <div class="form-group">
                        <label>Status Berita:</label>
                        <p class="form-control-static">{{ ucfirst($newsArticle->status) }}</p>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.news_articles.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@stop

{{-- Script untuk menampilkan nama file di input custom-file-input --}}
@section('js')
    <script>
        $(document).ready(function () {
            bsCustomFileInput.init();
        });
    </script>
@stop