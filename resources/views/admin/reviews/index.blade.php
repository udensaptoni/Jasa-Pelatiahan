@extends('admin.layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Moderasi Ulasan</h3>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produk</th>
                    <th>Nama</th>
                    <th>Rating</th>
                    <th>Pesan</th>
                    <th>Tanggal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                <tr>
                    <td>{{ $review->id }}</td>
                    <td><a href="{{ route('produk.detail', $review->product->id) }}" target="_blank">{{ $review->product->name }}</a></td>
                    <td>{{ $review->name }}</td>
                    <td>{{ $review->rating }}</td>
                    <td>{{ Str::limit($review->message, 120) }}</td>
                    <td>{{ $review->created_at->format('Y-m-d') }}</td>
                    <td class="d-flex gap-2">
                        @if(!$review->approved)
                        <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-success">Setujui</button>
                        </form>
                        @endif
                        <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?');">
                            @csrf
                            <button class="btn btn-sm btn-danger">Tolak</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $reviews->links() }}
    </div>
</div>
@endsection
