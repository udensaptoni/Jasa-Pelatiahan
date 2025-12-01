@extends('layouts.app')

@section('content')
<section class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-5 fw-bold">Tentang Kami</h1>
                @if($about)
                    <p class="lead text-muted">{{ Str::limit(strip_tags($about->content), 180) }}</p>
                    <a href="{{ route('kontak') }}" class="btn btn-primary">Hubungi Kami</a>
                @else
                    <p class="lead text-muted">Belum ada data Tentang Kami.</p>
                @endif
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @if($about)
                            <h4>{{ $about->title }}</h4>
                            {!! $about->content !!}
                            @if($about->image)
                                <div class="mt-3">
                                    <img src="{{ asset('storage/'.$about->image) }}" alt="Tentang Kami" class="img-fluid rounded shadow" style="max-width:100%;">
                                </div>
                            @endif
                        @else
                            <p class="text-muted">Belum ada informasi lengkap.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
