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
                    @foreach($newsArticles as $article)
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
                            @can('edit news')
                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('editor') || (auth()->user()->hasRole('wartawan') && auth()->id() == $article->user_id))
                                    <a href="{{ route('admin.news_articles.edit', $article) }}" class="btn btn-info btn-xs">Edit</a>
                                @endif
                            @endcan

                            @can('publish news')
                                @if($article->status == 'draft')
                                    <a href="{{ route('admin.news_articles.publish', $article) }}" class="btn btn-success btn-xs">Terbitkan</a>
                                @else
                                    <a href="{{ route('admin.news_articles.unpublish', $article) }}" class="btn btn-secondary btn-xs">Tarik</a>
                                @endif
                            @endcan

                            @can('delete news')
                                @if(auth()->user()->hasRole('admin') || (auth()->user()->hasRole('wartawan') && auth()->id() == $article->user_id))
                                    <form action="{{ route('admin.news_articles.destroy', $article) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">Hapus</button>
                                    </form>
                                @endif
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer">
                {{ $newsArticles->links('pagination::bootstrap-5') }} {{-- Pastikan pagination sesuai Bootstrap 5 --}}
            </div>
        </div>
    </div>
@stop