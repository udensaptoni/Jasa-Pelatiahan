@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-center min-vh-100 bg-gradient" 
    style="background: linear-gradient(135deg, #f0f4ff, #e8f0ff, #f7faff);">
    
    <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5" style="max-width: 480px; width: 100%; background: white;">
        <div class="text-center mb-4">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                style="width: 80px; height: 80px; animation: pulse 2s infinite;">
                <i class="fas fa-wallet fa-2x"></i>
            </div>
            <h2 class="fw-bold text-dark mb-1">Pembayaran Anda</h2>
            <p class="text-muted small">Transaksi aman & cepat menggunakan Midtrans</p>
        </div>

        <div class="text-center mb-4 border rounded-4 py-3 bg-light">
            <p class="text-muted mb-1 fw-medium">Produk</p>
            <h5 class="fw-semibold text-dark">{{ $registration->product->name }}</h5>
            <hr class="my-2">
            <p class="text-muted mb-1">Jumlah Pembayaran</p>
            <h3 class="fw-bold text-success mb-0">
                Rp {{ number_format($registration->product->price ?? 10000, 0, ',', '.') }}
            </h3>
        </div>

        <form id="payment-form" action="{{ route('payments.store', $registration->id) }}" method="POST">
            @csrf
            @php $midtransKey = config('services.midtrans.server_key'); @endphp

            @if(empty($midtransKey))
                <div class="alert alert-danger text-start rounded-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Oops — Midtrans server key belum dikonfigurasi.<br>
                    <small>Silakan hubungi admin untuk pengaturan pembayaran.</small>
                </div>
                <button class="btn btn-lg btn-secondary w-100 mt-3" disabled>
                    <i class="fas fa-lock me-2"></i> Tidak Dapat Melanjutkan
                </button>
            @else
                <button id="pay-btn" class="btn btn-lg btn-primary w-100 shadow-sm rounded-3 fw-semibold" type="submit" style="transition: all .3s;">
                    <i class="fas fa-qrcode me-2"></i> Lanjutkan Pembayaran
                </button>
            @endif
        </form>

        <!-- Creative Wave Loader -->
        <div id="loading" class="text-center mt-5" style="display:none;">
            <div class="wave-loader mx-auto mb-3">
                <div class="wave"></div>
                <div class="wave"></div>
                <div class="wave"></div>
                <div class="icon"><i class="fas fa-qrcode"></i></div>
            </div>
            <h6 class="text-primary fw-semibold">Memproses pembayaran...</h6>
            <p class="text-muted small mb-0">Jangan tutup halaman ini</p>
        </div>

        <div class="mt-4 text-center text-muted small">
            <p class="mb-0">
                Dengan melanjutkan, Anda menyetujui 
                <a href="#" class="text-decoration-none text-primary fw-semibold">Syarat & Ketentuan</a>.
            </p>
        </div>
    </div>
</div>

{{-- FontAwesome + Script --}}
@push('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('payment-form');
    const btn = document.getElementById('pay-btn');
    const loading = document.getElementById('loading');

    form.addEventListener('submit', function(e) {
        btn.disabled = true;
        btn.classList.add('opacity-75');
        btn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Sedang Memproses...`;
        loading.style.display = 'block';
    });
});
</script>

<style>
@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(13,110,253,0.4); }
    70% { box-shadow: 0 0 0 15px rgba(13,110,253,0); }
    100% { box-shadow: 0 0 0 0 rgba(13,110,253,0); }
}

.btn-primary:hover {
    background: linear-gradient(90deg, #0d6efd, #4f9cff);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(13,110,253,0.25);
}

/* Wave Loader Animation */
.wave-loader {
    position: relative;
    width: 90px;
    height: 90px;
}
.wave {
    position: absolute;
    width: 100%;
    height: 100%;
    border: 4px solid rgba(13,110,253,0.2);
    border-radius: 50%;
    animation: ripple 1.6s infinite;
}
.wave:nth-child(2) { animation-delay: 0.3s; }
.wave:nth-child(3) { animation-delay: 0.6s; }

.icon {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0d6efd;
    font-size: 1.8rem;
    background: rgba(13,110,253,0.08);
    border-radius: 50%;
    z-index: 2;
}

@keyframes ripple {
    0% { transform: scale(0.6); opacity: 1; }
    100% { transform: scale(1.8); opacity: 0; }
}
</style>
@endpush
@endsection
