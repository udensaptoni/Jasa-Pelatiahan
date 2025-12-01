@extends('admin.layout')

@section('content')
<h3>Edit Artikel</h3>
<form action="{{ route('admin.articles.update',$article->id) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Judul</label>
        <input type="text" name="title" class="form-control" value="{{ $article->title }}">
    </div>
    <div class="mb-3">
        <label>Konten</label>
        <textarea name="content" class="form-control">{{ $article->content }}</textarea>
    </div>
    <div class="mb-3">
        <label>Gambar</label><br>
        @if($article->image)<img src="{{ asset('storage/'.$article->image) }}" width="120">@endif
        <input type="file" name="image" class="form-control mt-2">
    </div>
    <button class="btn btn-success">Update</button>
    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
