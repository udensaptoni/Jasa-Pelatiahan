@extends('admin.layout')

@section('content')
<div class="container mt-4">
    <h2>Tambah Tentang Kami</h2>
    <form action="{{ route('admin.about.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Judul</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Isi</label>
            <textarea name="content" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label>Gambar (Opsional)</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button class="btn btn-success">Simpan</button>
    <a href="{{ route('admin.about.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
