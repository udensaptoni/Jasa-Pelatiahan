@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h3 class="mb-4">Dashboard</h3>
    <div class="card p-3">
        @if($payments->isEmpty())
            <p class="text-muted">Belum ada riwayat pembayaran.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Produk</th>
                        <th>Status</th>
                        <th class="text-end">Jumlah</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $p)
                    <tr>
                        <td>{{ $p->external_id }}</td>
                        <td>{{ $p->registration->product->title ?? 'Pendaftaran' }}</td>
                        <td>{{ $p->status }}</td>
                        <td class="text-end">Rp {{ number_format($p->amount,0,',','.') }}</td>
                        <td class="text-end"><a href="{{ route('payments.show', $p->id) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
