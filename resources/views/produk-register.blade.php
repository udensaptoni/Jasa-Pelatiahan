@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card card-elevated">
                <div class="card-header text-center" style="background:var(--brand-primary); color:#fff;">
                    <h3 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Daftar Produk / Pendaftaran</h3>
                    <p class="mb-0">{{ $product->title ?? $product->name ?? $product->nama }}</p>
                    @if(!empty($product->price))
                        <small class="d-block mt-1">Harga: <strong>Rp {{ number_format($product->price,0,',','.') }}</strong></small>
                    @endif
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('produk.register', $product->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" name="nama" value="{{ old('nama') }}" required>
                            @error('nama') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="telepon" class="form-label">Telepon</label>
                            <input type="text" class="form-control @error('telepon') is-invalid @enderror" 
                                   id="telepon" name="telepon" value="{{ old('telepon') }}" required>
                            @error('telepon') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>

                        {{-- Tambahan kolom Catatan --}}
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                      id="catatan" name="catatan" rows="3">{{ old('catatan') }}</textarea>
                            @error('catatan') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn nav-btn-primary btn-lg"><i class="bi bi-check2-circle me-2"></i> Daftar Sekarang</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center text-muted">
                    <a href="{{ route('produk.detail', $product->id) }}" class="text-decoration-none">
                        Kembali ke Detail Produk
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
