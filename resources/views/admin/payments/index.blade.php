@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Daftar Payments</h2>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<form class="mb-3">
    <div class="row g-2">
        <div class="col-auto">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                <option value="settlement" {{ request('status')=='settlement' ? 'selected' : '' }}>Settlement</option>
                <option value="capture" {{ request('status')=='capture' ? 'selected' : '' }}>Capture</option>
                <option value="deny" {{ request('status')=='deny' ? 'selected' : '' }}>Deny</option>
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary">Filter</button>
        </div>
    </div>
</form>

<div class="table-responsive">
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Registration</th>
            <th>Product</th>
            <th>Rincian Produk</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Created</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $p)
        <tr>
            <td>{{ $p->id }}</td>
            <td>{{ $p->registration->nama ?? '-' }}</td>
            <td>{{ $p->registration->product->name ?? '-' }}</td>
            <td>
                @php
                    $items = $p->metadata['item_details'] ?? null;
                    if (!$items) {
                        $items = $p->registration ? [[ 'name' => $p->registration->product->name ?? null, 'price' => $p->registration->product->price ?? $p->amount ]] : [];
                    }
                @endphp
                @foreach($items as $it)
                    <div><strong>{{ $it['name'] ?? '-' }}</strong> &times; {{ $it['quantity'] ?? 1 }} <small class="text-muted">(Rp {{ number_format($it['price'] ?? 0,0,',','.') }})</small></div>
                @endforeach
            </td>
            <td>Rp {{ number_format($p->amount,0,',','.') }}</td>
            <td>{{ $p->status }}</td>
            <td>{{ $p->created_at->format('Y-m-d H:i') }}</td>
            <td>
                <button type="button" class="btn btn-sm btn-primary btn-detail" data-payment='@json($p)'>Detail</button>
                <form action="{{ route('admin.payments.reconcile', $p->id) }}" method="POST" style="display:inline-block;" class="reconcile-form">
                    @csrf
                    <button type="button" class="btn btn-sm btn-outline-secondary btn-reconcile">Reconcile</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
    </table>
</div>

{{ $payments->links() }}

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const table = document.querySelector('table');
    if (!table) return;

    table.addEventListener('click', function(e){
        const detailBtn = e.target.closest('.btn-detail');
        if (detailBtn) {
            e.preventDefault();
            try {
                const p = JSON.parse(detailBtn.getAttribute('data-payment'));
                const modal = document.getElementById('paymentDetailModal');
                modal.querySelector('.modal-title').textContent = 'Payment #' + p.id;
                modal.querySelector('.pd-external').textContent = p.external_id || '-';
                modal.querySelector('.pd-status').textContent = p.status || '-';
                modal.querySelector('.pd-amount').textContent = 'Rp ' + (p.amount ? new Intl.NumberFormat('id-ID').format(p.amount) : '-');

                const itemsEl = modal.querySelector('.pd-items');
                itemsEl.innerHTML = '';
                const items = (p.metadata && p.metadata.item_details) ? p.metadata.item_details : [];
                if (items.length === 0 && p.registration && p.registration.product) {
                    items.push({ name: p.registration.product.name, price: p.registration.product.price, quantity:1 });
                }
                items.forEach(it => {
                    const div = document.createElement('div');
                    div.innerHTML = `<strong>${it.name||'-'}</strong> &times; ${it.quantity||1} <small class="text-muted">(Rp ${new Intl.NumberFormat('id-ID').format(it.price||0)})</small>`;
                    itemsEl.appendChild(div);
                });

                const metaEl = modal.querySelector('.pd-metadata');
                try { metaEl.textContent = JSON.stringify(p.metadata || {}, null, 2); } catch(e) { metaEl.textContent = '-'; }

                const bs = new bootstrap.Modal(modal);
                bs.show();
            } catch (err) {
                console.error('Failed to parse payment data', err);
            }
            return;
        }

        const recBtn = e.target.closest('.btn-reconcile');
        if (recBtn) {
            e.preventDefault();
            const form = recBtn.closest('form');
            Swal.fire({
                title: 'Rekonsiliasi?',
                text: 'Kirim permintaan rekonsiliasi ke provider dan perbarui status.',
                icon: 'question',
                showCancelButton:true,
                confirmButtonText:'Ya, Rekonsiliasi',
            }).then(res => { if (res.isConfirmed) form.submit(); });
            return;
        }
    });
});
</script>

<!-- Payment detail modal -->
<div class="modal fade" id="paymentDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        <div class="modal-body">
                <div class="row">
                        <div class="col-md-6">
                                <p><strong>Order ID:</strong> <span class="pd-external">-</span></p>
                                <p><strong>Status:</strong> <span class="pd-status">-</span></p>
                                <p><strong>Jumlah:</strong> <span class="pd-amount">-</span></p>
                        </div>
                        <div class="col-md-6">
                                <h6>Rincian Produk</h6>
                                <div class="pd-items"></div>
                        </div>
                </div>
                        <hr />
                        <h6>Raw Metadata</h6>
                        <pre class="small pd-metadata">-</pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
