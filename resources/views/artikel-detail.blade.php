@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-elevated p-4">
                @if($article->image)
                    <img src="{{ asset('storage/'.$article->image) }}" class="img-fluid rounded mb-4" alt="{{ $article->title }}">
                @endif

                <h2 class="fw-bold">{{ $article->title }}</h2>
                <p class="mt-3">{{ $article->content }}</p>

                <a href="/" class="btn btn-outline-primary mt-3"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
