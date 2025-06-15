@extends('layouts.admin')

@section('page_title', 'Manajemen Kategori')

@section('admin_content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Kategori</h3>
            <div class="card-tools">
                @can('manage categories') {{-- Hanya yang punya permission ini yang bisa melihat tombol ini --}}
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">Tambah Kategori Baru</a>
                @endcan
            </div>
        </div>
        <div class="card-body p-0">
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

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th style="width: 150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>
                            @can('manage categories') {{-- Memastikan hanya admin/editor yang melihat tombol ini --}}
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-info btn-xs">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Menghapus kategori akan juga menghapus berita yang terkait.')">Hapus</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="card-footer">
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@stop