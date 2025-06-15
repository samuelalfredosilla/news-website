@extends('layouts.admin')

@section('page_title', 'Tambah Berita Baru')

@section('admin_content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Form Berita Baru</h3>
        </div>
        <form action="{{ route('admin.news_articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
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
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category_id">Kategori:</label>
                    <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image">Gambar Sampul:</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="image" id="image" class="custom-file-input @error('image') is-invalid @enderror">
                            <label class="custom-file-label" for="image">Pilih file</label>
                        </div>
                    </div>
                    <small class="form-text text-muted">Maks. 2MB, format: jpg, jpeg, png, gif.</small>
                    @error('image')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content">Isi Berita:</label>
                    <textarea name="content" id="content" rows="10" class="form-control @error('content') is-invalid @enderror" required>{{ old('content') }}</textarea>
                    @error('content')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                    {{-- TODO: Integrasikan WYSIWYG editor seperti TinyMCE atau CKEditor di sini --}}
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan Berita</button>
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