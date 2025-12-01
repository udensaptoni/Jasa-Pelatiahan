@extends('layouts.app')

@section('content')
<div class="container py-5" style="min-height:100vh;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-elevated mb-5 rounded-4">
                <!-- Gambar Produk -->
                @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top img-fluid rounded-top" alt="{{ $product->name }}">
                @else
                    <img src="https://via.placeholder.com/800x400" class="card-img-top img-fluid rounded-top" alt="No Image">
                @endif

                <div class="card-body p-5">
                    <!-- Nama, Harga & Rating -->
                    <h2 class="fw-bold mb-2 text-dark">{{ $product->name }}</h2>
                    <div class="d-flex align-items-center mb-3">
                        <p class="text-success fs-5 mb-0 fw-semibold me-3">Rp {{ number_format($product->price,0,',','.') }}</p>
                        <div class="d-flex align-items-center">
                            @php
                                $avg = $averageRating ?? 0;
                                $fullStars = floor($avg);
                                $halfStar = ($avg - $fullStars) >= 0.5;
                            @endphp
                            <div class="me-2 text-warning" title="Rating rata-rata {{ $avg }}">
                                @for ($i = 0; $i < $fullStars; $i++)
                                    <i class="bi bi-star-fill"></i>
                                @endfor
                                @if($halfStar)
                                    <i class="bi bi-star-half"></i>
                                @endif
                                @php $remaining = 5 - $fullStars - ($halfStar ? 1 : 0); @endphp
                                @for ($i = 0; $i < $remaining; $i++)
                                    <i class="bi bi-star"></i>
                                @endfor
                            </div>
                            <div class="text-muted small">({{ $reviewCount ?? 0 }} ulasan)</div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <p class="fs-6 text-secondary mb-4" style="line-height:1.8;">{{ $product->description }}</p>

                    <!-- Tombol -->
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="{{ route('produk.register.form', $product->id) }}" class="btn nav-btn-primary btn-lg flex-grow-1 shadow-sm rounded-pill"><i class="bi bi-pencil-square me-2"></i> Daftar Sekarang</a>
                        <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-lg flex-grow-1 shadow-sm rounded-pill"><i class="bi bi-arrow-left me-2"></i> Kembali</a>
                    </div>
                </div>

                <div class="card-footer text-center text-muted bg-light py-3" style="font-size:0.9rem;">
                    Produk ID: {{ $product->id }}
                </div>
            </div>

            <!-- Rekomendasi Produk Lain -->
            @if($relatedProducts->count() > 0)
                <h4 class="mb-4 fw-bold text-secondary">Produk Lainnya</h4>
                <div class="row g-4">
                    @foreach($relatedProducts as $related)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0 rounded-4" style="background: #ffffff;">
                                @if($related->image)
                                    <img src="{{ asset('storage/'.$related->image) }}" class="card-img-top img-fluid rounded-top" alt="{{ $related->name }}">
                                @else
                                    <img src="https://via.placeholder.com/400x250" class="card-img-top img-fluid rounded-top" alt="No Image">
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h6 class="fw-bold text-primary">{{ $related->name }}</h6>
                                    <p class="text-success mb-3 fw-semibold">Rp {{ number_format($related->price,0,',','.') }}</p>
                                    <div class="mt-auto d-grid gap-2">
                                        <a href="{{ route('produk.detail', $related->id) }}" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                                        <a href="{{ route('produk.register.form', $related->id) }}" class="btn btn-primary btn-sm" style="background: linear-gradient(45deg, #4facfe, #00f2fe); border:none;">Daftar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Ulasan / Reviews -->
            <div class="mt-5">
                <h4 class="mb-3 fw-bold">Ulasan Pengguna</h4>

                {{-- Form kirim ulasan --}}
                <div class="card mb-4 p-3 review-card">
                    <form action="{{ route('products.reviews.store', $product->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <input type="text" name="name" class="form-control" placeholder="Nama Anda" value="{{ old('name') }}" required>
                                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <select name="rating" class="form-select">
                                    @for($r=5; $r>=1; $r--)
                                        <option value="{{ $r }}" {{ old('rating') == $r ? 'selected' : '' }}>{{ str_repeat('⭐', $r) }} {{ $r }}</option>
                                    @endfor
                                </select>
                                @error('rating') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3 d-grid">
                                <button class="btn nav-btn-primary">Kirim Ulasan</button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <textarea name="message" class="form-control" rows="3" placeholder="Tulis ulasan Anda" required>{{ old('message') }}</textarea>
                            @error('message') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </form>
                </div>

                {{-- Tampilkan ulasan yang disetujui --}}
                <div class="row g-3">
                    @forelse($product->reviews as $review)
                        <div class="col-md-6">
                            <div class="review-card">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-grow-1">
                                        <strong>{{ $review->name }}</strong>
                                        <div class="text-muted small">{{ $review->created_at->format('Y-m-d') }}</div>
                                    </div>
                                    <div class="text-warning">
                                        {!! str_repeat('★', $review->rating) !!}
                                    </div>
                                </div>
                                <div class="text-muted">{{ $review->message }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="p-4 text-center text-muted">Belum ada ulasan untuk produk ini. Jadilah yang pertama!</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
