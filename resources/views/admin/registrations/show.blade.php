@extends('admin.layout')

@section('content')
<div class="container mt-4">
    <h2>Detail Registrasi Peserta</h2>

    <div class="card">
        <div class="card-body">
            <p><strong>Produk:</strong> {{ $registration->product->name }}</p>
            <p><strong>Nama:</strong> {{ $registration->nama }}</p>
            <p><strong>Email:</strong> {{ $registration->email }}</p>
            <p><strong>Telepon:</strong> {{ $registration->telepon }}</p>
            <p><strong>Catatan:</strong> {{ $registration->catatan ?? '-' }}</p>
            <p><strong>Tanggal Daftar:</strong> {{ $registration->created_at->format('d M Y H:i') }}</p>
        </div>
    </div>

    <a href="{{ route('admin.registrations.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
