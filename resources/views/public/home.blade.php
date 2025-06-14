@extends('public.layouts.app')

@section('title', 'Berita Terbaru')

@section('content')
    <h2 class="mb-4">Berita Terbaru</h2>
    <div class="row">
        @forelse($latestNews as $article)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($article->image)
                        <img src="{{ Storage::url($article->image) }}" class="card-img-top news-card-img" alt="{{ $article->title }}">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $article->title }}</h5>
                        <p class="card-text text-muted small">
                            Oleh {{ $article->user->name }} | {{ $article->category->name }} | {{ $article->published_at->format('d M Y') }}
                        </p>
                        <p class="card-text">{{ Str::limit(strip_tags($article->content), 100) }}</p>
                        <div class="mt-auto">
                            <a href="{{ route('news.detail', $article->slug) }}" class="btn btn-primary btn-sm">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p>Belum ada berita terbaru.</p>
            </div>
        @endforelse
    </div>
    <div class="mt-4 text-center">
        <a href="{{ route('news.list') }}" class="btn btn-secondary">Lihat Semua Berita</a>
    </div>
@endsection