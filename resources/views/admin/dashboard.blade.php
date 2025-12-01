@extends('admin.layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">Dashboard</h3>
            <small class="text-muted">Selamat Datang, {{ session('admin_name') ?? 'Admin' }}</small>
        </div>
        <div>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-light"><i class="bi bi-credit-card-2-front-fill me-1"></i> Payments</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-3">
            <a href="{{ route('admin.articles.index') }}" class="card card-elevated p-3 text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <i class="bi bi-journal-text fs-2 me-3 text-primary"></i>
                    <div>
                        <div class="fw-bold">Artikel</div>
                        <div class="text-muted small">Kelola konten artikel</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.products.index') }}" class="card card-elevated p-3 text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <i class="bi bi-box-seam fs-2 me-3 text-primary"></i>
                    <div>
                        <div class="fw-bold">Produk</div>
                        <div class="text-muted small">Kelola produk dan harga</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.registrations.index') }}" class="card card-elevated p-3 text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <i class="bi bi-person-lines-fill fs-2 me-3 text-success"></i>
                    <div>
                        <div class="fw-bold">Registrasi</div>
                        <div class="text-muted small">Daftar peserta</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.testimonials.index') }}" class="card card-elevated p-3 text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <i class="bi bi-chat-quote-fill fs-2 me-3 text-warning"></i>
                    <div>
                        <div class="fw-bold">Testimoni</div>
                        <div class="text-muted small">Kelola ulasan pengguna</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.payments.index') }}" class="card card-elevated p-3 text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <i class="bi bi-credit-card-2-front-fill fs-2 me-3 text-success"></i>
                    <div>
                        <div class="fw-bold">Payments</div>
                        <div class="text-muted small">Kelola pembayaran & rekonsiliasi</div>
                    </div>
                </div>
            </a>
        </div>
    </div>

@endsection
