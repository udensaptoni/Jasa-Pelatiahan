@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center py-5">
    <div class="card shadow-lg" style="max-width:980px; width:100%; border-radius:16px; overflow:hidden;">
        <div class="p-4" style="background:linear-gradient(180deg,#f8fbff, #ffffff);">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h3 class="mb-0 fw-bold">Selesaikan Pembayaran Anda</h3>
                    <small class="text-muted">Order ID: <strong>{{ $payment->external_id }}</strong></small>
                </div>
                <div class="text-end">
                    <small class="text-muted">Langkah</small>
                    <div class="d-flex gap-1 align-items-center">
                        <span class="badge rounded-circle bg-primary text-white">1</span>
                        <span class="badge rounded-circle bg-primary text-white">2</span>
                        <span class="badge rounded-circle bg-light text-muted">3</span>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm p-3" style="min-height:480px;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="mb-0">Pilih Metode Pembayaran QRIS</h5>
                                <small class="text-muted">Scan QR di bawah menggunakan mobile banking / e-wallet pilihan Anda</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-success">QRIS GPN</span>
                            </div>
                        </div>

                        @php
                            $productName = $payment->registration->product->name ?? $payment->registration->product->title ?? 'Pendaftaran';
                        @endphp

                        <div class="d-flex align-items-start gap-4 mt-3 flex-wrap">
                            <div class="qris-qr" style="flex:0 0 420px; max-width:420px; width:100%;">
                                <div class="border rounded-3 p-3 d-flex flex-column align-items-center justify-content-center" style="background:#fff; width:100%;">
                                    @if($payment->qr_string)
                                        <div id="qrcode"></div>
                                    @else
                                        <div class="text-muted">QR belum tersedia</div>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-center gap-2 mt-3 flex-wrap">
                                    <button id="btn-copy" class="btn btn-sm btn-outline-primary"><i class="bi bi-clipboard me-1"></i> Salin Kode</button>
                                    <a id="btn-download" href="{{ route('payments.download_qr', $payment->id) }}" class="btn btn-sm btn-primary"><i class="bi bi-download me-1"></i> Unduh QR</a>
                                    <button id="btn-print" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer me-1"></i> Cetak</button>
                                </div>

                                <div class="mt-3 text-center text-muted small">
                                    <p class="mb-1">Kode ini akan kadaluarsa dalam <span id="countdown">--:--</span></p>
                                    <p class="mb-0">Pastikan nominal pembayaran sesuai dengan total yang tertera</p>
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <div class="card border-0 bg-light p-3">
                                    <h6 class="mb-2">Ringkasan Pesanan Anda</h6>
                                    <p class="mb-1 text-muted">{{ $productName }}</p>
                                    <p class="h3 text-primary mb-1">Rp {{ number_format($payment->amount,0,',','.') }}</p>
                                    <p class="text-muted small">Tanggal: {{ optional($payment->created_at)->format('d M Y') }}</p>

                                    <hr>
                                    <h6 class="mb-2">Cara Melakukan Pembayaran dengan QRIS</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <ul class="list-unstyled small text-muted">
                                                <li>1. Buka aplikasi mobile banking / e-wallet</li>
                                                <li>2. Pilih fitur 'Scan QR' atau Pembayaran QR</li>
                                                <li>3. Arahkan kamera ke QR Code di kiri</li>
                                            </ul>
                                        </div>
                                        <div class="col-6">
                                            <ul class="list-unstyled small text-muted">
                                                <li>4. Pastikan detail (merchant, nominal) benar</li>
                                                <li>5. Konfirmasi pembayaran</li>
                                                <li>6. Pembayaran akan terverifikasi otomatis</li>
                                            </ul>
                                        </div>
                                    </div>
                                    @if(empty($payment->qr_string))
                                        <div class="mt-3">
                                            <div class="alert alert-warning small mb-0">QR belum tersedia. Jika Anda sudah melakukan pembayaran melalui e-wallet lain, klik <a href="{{ route('home') }}">Kembali</a> atau hubungi <a href="mailto:info@example.com">info@example.com</a> untuk bantuan. Anda juga dapat menunggu beberapa saat dan coba klik tombol <strong>Rekonsiliasi</strong> di panel admin.</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card shadow-sm p-3 h-100">
                        <h6 class="mb-3">Ringkasan Pesanan</h6>
                        <div class="d-flex justify-content-between">
                            <div class="text-muted">Nama Produk</div>
                            <div><strong>{{ $productName }}</strong></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <div class="text-muted">Harga</div>
                            <div>Rp {{ number_format($payment->amount,0,',','.') }}</div>
                        </div>
                        @if(!empty($payment->metadata['discount'] ?? null))
                        <div class="d-flex justify-content-between mt-2 text-danger">
                            <div class="text-muted">Diskon</div>
                            <div>- Rp {{ number_format($payment->metadata['discount'],0,',','.') }}</div>
                        </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fw-bold">Total Pembayaran</div>
                            <div class="h5 text-primary fw-bold">Rp {{ number_format($payment->amount,0,',','.') }}</div>
                        </div>

                        <div class="mt-4 d-grid gap-2">
                            @if($payment->payment_url)
                                <a href="{{ $payment->payment_url }}" class="btn btn-primary w-100 mb-2" target="_blank">Buka Halaman Pembayaran</a>
                            @endif
                            <button id="btn-pay-now" class="btn btn-success w-100">Bayar Sekarang</button>
                            <a href="{{ route('payments.invoice', $payment->id) }}?pdf=1" class="btn btn-outline-secondary w-100">Unduh Invoice (PDF)</a>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">Kembali ke Beranda</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Midtrans Snap JS (sandbox/production determined by config) --}}
<script>
    (function(){
        const clientKey = '{{ config('services.midtrans.client_key') }}';
        if (clientKey) {
            const snapScript = document.createElement('script');
            snapScript.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
            snapScript.setAttribute('data-client-key', clientKey);
            document.head.appendChild(snapScript);
        }
    })();
</script>
<script>
    (function(){
        const paymentId = '{{ $payment->id }}';
        const qrPayload = {!! json_encode($payment->qr_string) !!} || {!! json_encode($payment->metadata['qr_string'] ?? null) !!};
        const qrcodeEl = document.getElementById('qrcode');

        // Countdown: prefer metadata.expired_at or metadata.expiry_seconds (seconds from now)
        let expiryTimestamp = null;
        @if(!empty($payment->metadata['expired_at']))
            expiryTimestamp = new Date('{{ $payment->metadata['expired_at'] }}').getTime();
        @elseif(!empty($payment->metadata['expiry_seconds']))
            expiryTimestamp = Date.now() + ({{ intval($payment->metadata['expiry_seconds']) }} * 1000);
        @else
            // default 5 minutes
            expiryTimestamp = Date.now() + (5 * 60 * 1000);
        @endif

        function startCountdown(){
            const cdEl = document.getElementById('countdown');
            if (!cdEl) return;
            function tick(){
                const diff = expiryTimestamp - Date.now();
                if (diff <= 0) {
                    cdEl.textContent = '00:00';
                    clearInterval(timer);
                    return;
                }
                const mins = Math.floor(diff / 60000).toString().padStart(2,'0');
                const secs = Math.floor((diff % 60000)/1000).toString().padStart(2,'0');
                cdEl.textContent = mins + ':' + secs;
            }
            tick();
            const timer = setInterval(tick, 1000);
        }

        if (qrPayload && qrcodeEl) {
            try {
                qrcodeEl.innerHTML = '';
                // Determine container size and choose an appropriate QR size (within min/max)
                const container = qrcodeEl.closest('.qris-qr') || qrcodeEl.parentElement;
                const containerWidth = (container && container.clientWidth) ? container.clientWidth : 420;
                const maxSize = 380;
                const padding = 24; // approximate padding inside the border
                let size = Math.min(maxSize, Math.floor(containerWidth - padding));
                if (size < 160) size = 160;

                // Create QR code at computed size
                new QRCode(qrcodeEl, { text: qrPayload, width: size, height: size, colorDark: '#0b1220', colorLight: '#ffffff' });

                // Buttons: download/copy/print
                document.getElementById('btn-download')?.addEventListener('click', function(){
                    // Try to download provider PNG first if available, else generated canvas/img
                    const actions = {!! json_encode($payment->metadata['actions'] ?? null) !!};
                    if (actions && actions[0] && actions[0].url) {
                        window.open(actions[0].url, '_blank');
                        return;
                    }
                    const img = qrcodeEl.querySelector('img');
                    const canvas = qrcodeEl.querySelector('canvas');
                    if (img && img.src) {
                        const a = document.createElement('a'); a.href = img.src; a.download = 'qris-{{ $payment->external_id }}.png'; document.body.appendChild(a); a.click(); a.remove();
                    } else if (canvas) {
                        const data = canvas.toDataURL('image/png'); const a = document.createElement('a'); a.href = data; a.download = 'qris-{{ $payment->external_id }}.png'; document.body.appendChild(a); a.click(); a.remove();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal mengunduh QR.'});
                    }
                });

                document.getElementById('btn-copy')?.addEventListener('click', function(){
                    if (navigator.clipboard && qrPayload) {
                        navigator.clipboard.writeText(qrPayload).then(()=>{
                            Swal.fire({ icon:'success', title:'Tersalin', text:'Kode QR disalin ke clipboard.', toast:true, position:'top-end', showConfirmButton:false, timer:1500 });
                        }).catch(()=> Swal.fire({ icon:'error', title:'Gagal', text:'Gagal menyalin.' }));
                    } else {
                        Swal.fire({ icon:'info', title:'Tidak tersedia', text:'Fitur penyalinan tidak didukung di browser ini.' });
                    }
                });

                document.getElementById('btn-print')?.addEventListener('click', function(){
                    const w = window.open('', '_blank'); w.document.write('<html><head><title>QR Pembayaran</title></head><body style="display:flex;align-items:center;justify-content:center;padding:30px;">' + qrcodeEl.innerHTML + '</body></html>'); w.document.close(); w.focus(); setTimeout(()=>{ w.print(); w.close(); }, 500);
                });

                // Pay Now handler (Midtrans Snap) — prefer snap token from metadata
                document.getElementById('btn-pay-now')?.addEventListener('click', function(){
                    const snapData = {!! json_encode($payment->metadata['snap'] ?? null) !!};
                    const paymentUrl = {!! json_encode($payment->payment_url) !!};
                    if (snapData && snapData.token) {
                        if (typeof snap !== 'undefined' && snap.pay) {
                            snap.pay(snapData.token, {
                                onSuccess: function(result){ location.reload(); },
                                onPending: function(result){ Swal.fire({ icon:'info', title:'Menunggu Pembayaran', text:'Pembayaran sedang menunggu konfirmasi.' }); },
                                onError: function(err){ Swal.fire({ icon:'error', title:'Gagal', text:'Terjadi kesalahan saat membuka Snap.' }); }
                            });
                            return;
                        } else {
                            Swal.fire({ icon:'info', title:'Menunggu', text:'Tunggu sebentar, Snap belum tersedia.' });
                            return;
                        }
                    }
                    if (paymentUrl) {
                        window.open(paymentUrl, '_blank');
                        return;
                    }
                    Swal.fire({ icon:'info', title:'Tidak tersedia', text:'Metode pembayaran online belum tersedia.' });
                });

                startCountdown();
            } catch (e){ console.error('QR render failed', e); }
        }

        // status polling
        function checkStatus(){
            fetch('/payments/status/' + paymentId)
                .then(r => r.json())
                .then(data => {
                    if (!data || !data.status) return;
                    const statusEls = document.querySelectorAll('#payment-status');
                    statusEls.forEach(el => el.textContent = data.status);
                    if (['settlement','capture','paid'].includes(data.status)) {
                        Swal.fire({ icon:'success', title:'Pembayaran Selesai', text:'Pembayaran telah diterima.', toast:true, position:'top-end', showConfirmButton:false, timer:2500 });
                        setTimeout(()=> location.reload(), 1200);
                    }
                }).catch(err => console.error('status check failed', err));
        }
        setInterval(checkStatus, 5000);
    })();
</script>
@endsection
