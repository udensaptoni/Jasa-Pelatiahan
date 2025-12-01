@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <img src="{{ asset('favicon.ico') }}" alt="logo" style="height:40px;" class="me-2">
                <strong>Nama Perusahaan Anda</strong>
                <div class="text-muted small">Alamat perusahaan • Telepon • email@domain.com</div>
            </div>
            <div class="text-end">
                <h5 class="text-primary">Rp {{ number_format($payment->amount,0,',','.') }}</h5>
                <small class="text-muted">Tanggal: {{ optional($payment->created_at)->format('d M Y') }}</small>
            </div>
        </div>

        <h6>Invoice untuk:</h6>
        <div class="mb-3">
            <strong>{{ $payment->registration->nama ?? 'Nama Pelanggan' }}</strong><br>
            <small class="text-muted">{{ $payment->registration->email ?? '' }} • {{ $payment->registration->telepon ?? '' }}</small>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payment->metadata['item_details'] ?? [] as $it)
                <tr>
                    <td>{{ $it['name'] ?? 'Item' }}</td>
                    <td class="text-end">{{ $it['quantity'] ?? 1 }}</td>
                    <td class="text-end">Rp {{ number_format($it['price'] ?? 0,0,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td class="text-end"><strong>Total</strong></td>
                    <td class="text-end"><strong>Rp {{ number_format($payment->amount,0,',','.') }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <div class="d-flex justify-content-end">
            <a href="{{ route('payments.invoice', $payment->id) }}?pdf=1" class="btn btn-primary me-2">Download PDF</a>
            <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
