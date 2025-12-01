@extends('admin.layout')

@section('content')
<h3>Tambah Artikel</h3>
<form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label>Judul</label>
        <input type="text" name="title" class="form-control">
    </div>
    <div class="mb-3">
        <label>Konten</label>
        <textarea name="content" class="form-control"></textarea>
    </div>
    <div class="mb-3">
        <label>Gambar</label>
        <input type="file" name="image" class="form-control">
    </div>
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
