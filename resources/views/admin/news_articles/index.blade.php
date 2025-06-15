@extends('layouts.admin')

@section('page_title', 'Manajemen Berita')

@section('admin_content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Berita</h3>
            <div class="card-tools">
                @can('create news')
                    <a href="{{ route('admin.news_articles.create') }}" class="btn btn-primary btn-sm">Tambah Berita Baru</a>
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
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th>Status</th>
                        <th>Tgl Publikasi</th>
                        <th style="width: 200px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($newsArticles as $article)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $article->title }}</td>
                        <td>{{ $article->category->name ?? 'N/A' }}</td>
                        <td>{{ $article->user->name ?? 'N/A' }}</td>
                        <td>
                            @if($article->status == 'published')
                                <span class="badge badge-success">Terbit</span>
                            @else
                                <span class="badge badge-warning">Draft</span>
                            @endif
                        </td>
                        <td>{{ $article->published_at ? $article->published_at->format('d M Y H:i') : '-' }}</td>
                        <td>
                            @php
                                $user = Auth::user();
                                $canEdit = $user->hasAnyRole(['admin', 'editor']) || ($user->hasRole('wartawan') && $user->id === $article->user_id && $article->status === 'draft');
                                $canDelete = $user->hasAnyRole(['admin', 'editor']) || ($user->hasRole('wartawan') && $user->id === $article->user_id);
                                $canPublish = $user->can('publish news');
                            @endphp

                            @if($canEdit)
                                <a href="{{ route('admin.news_articles.edit', $article) }}" class="btn btn-info btn-xs">Edit</a>
                            @endif

                            @if($canPublish)
                                @if($article->status == 'draft')
                                    <form action="{{ route('admin.news_articles.publish', $article) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success btn-xs" onclick="return confirm('Apakah Anda yakin ingin menerbitkan berita ini?')">Terbitkan</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.news_articles.unpublish', $article) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-secondary btn-xs" onclick="return confirm('Apakah Anda yakin ingin menarik berita ini?')">Tarik</button>
                                    </form>
                                @endif
                            @endif

                            @if($canDelete)
                                <form action="{{ route('admin.news_articles.destroy', $article) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada berita.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="card-footer">
                {{ $newsArticles->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@stop