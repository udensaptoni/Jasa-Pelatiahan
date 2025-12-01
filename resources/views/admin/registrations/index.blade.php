@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Data Registrasi Peserta</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Kembali ke Dashboard</a>
</div>

{{-- Tombol Export --}}
<div class="mb-3">
    <a href="{{ route('admin.registrations.export.excel') }}" class="btn btn-success">Export Excel</a>
    <a href="{{ route('admin.registrations.export.pdf') }}" class="btn btn-danger">Export PDF</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Produk</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Catatan</th> {{-- Tambahan kolom --}}
            <th>Tanggal Daftar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($registrations as $key => $reg)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $reg->product->title }}</td>
            <td>{{ $reg->nama }}</td>
            <td>{{ $reg->email }}</td>
            <td>{{ $reg->telepon }}</td>
            <td>{{ $reg->catatan ?? '-' }}</td> {{-- Menampilkan catatan --}}
            <td>{{ $reg->created_at->format('d M Y H:i') }}</td>
            <td>
                <a href="{{ route('admin.registrations.show', $reg->id) }}" class="btn btn-info btn-sm">Detail</a>
                <form action="{{ route('admin.registrations.destroy', $reg->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
