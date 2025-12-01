@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="mb-0">Artikel Terbaru</h2>
        <a href="{{ route('artikel.index') }}" class="btn btn-outline-secondary">Segarkan</a>
    </div>
    <div class="row">
        @forelse($articles as $article)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($article->image)
                        <div style="height:180px; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                            <img src="{{ asset('storage/'.$article->image) }}" style="max-height:100%; width:auto;" alt="{{ $article->title }}">
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $article->title }} <small class="text-muted d-block">{{ $article->created_at->format('d M Y') }}</small></h5>
                        <p class="card-text text-muted">{{ Str::limit(strip_tags($article->content), 120) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="{{ route('artikel.detail', $article->id) }}" class="btn btn-primary btn-sm">Baca</a>
                            <a href="{{ route('artikel.index') }}" class="btn btn-outline-secondary btn-sm">Lainnya</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
        <div class="col-12 text-center text-muted">Belum ada artikel.</div>
        @endforelse
    </div>
</div>
@endsection
