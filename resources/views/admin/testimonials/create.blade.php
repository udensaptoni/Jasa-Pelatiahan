@extends('admin.layout')

@section('content')
<h2>Tambah Testimoni</h2>
<form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email (opsional)</label>
        <input type="email" name="email" class="form-control">
    </div>
    <div class="mb-3">
        <label>Pesan</label>
        <textarea name="pesan" class="form-control" rows="4" required></textarea>
    </div>
    <div class="mb-3">
        <label>Foto (opsional)</label>
        <input type="file" name="foto" class="form-control">
    </div>
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary me-2">Kembali</a>
    <button type="submit" class="btn btn-success">Simpan</button>
</form>
@endsection
