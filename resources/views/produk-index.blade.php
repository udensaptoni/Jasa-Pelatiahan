@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="mb-0 fw-bold">Daftar Produk & Jasa</h2>
            @if(!empty($q))
                <small class="text-muted">Hasil pencarian untuk "{{ e($q) }}" — {{ $products->count() }} hasil</small>
            @endif
        </div>
        <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary">Segarkan</a>
    </div>
    <div class="row">
        @forelse($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card card-elevated h-100">
                @if($product->image)
                    <div style="height:200px; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                        <img src="{{ asset('storage/'.$product->image) }}" style="max-height:100%; width:auto;" alt="{{ $product->name }}">
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }} <span class="badge bg-light text-dark ms-2">Rp {{ number_format($product->price,0,',','.') }}</span></h5>
                    <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a href="{{ route('produk.detail', $product->id) }}" class="btn btn-primary btn-sm">Lihat Detail</a>
                        <a href="{{ route('produk.register.form', $product->id) }}" class="btn btn-outline-primary btn-sm">Daftar</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center text-muted">Belum ada produk.</div>
        @endforelse
    </div>
</div>
@endsection
