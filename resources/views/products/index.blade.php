@extends('layouts.app')

@section('title', 'Data Produk')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-gray-800">Daftar Produk</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Produk
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" class="rounded me-3"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted me-3"
                                                style="width: 50px; height: 50px;">
                                                <small>IMG</small>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $product->name }}</h6>
                                            <small class="text-muted">{{ Str::limit($product->description, 30) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-info text-dark">{{ $product->category ?? '-' }}</span></td>
                                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td>
                                    @if ($product->stock > 10)
                                        <span class="badge bg-success">{{ $product->stock }} Unit</span>
                                    @else
                                        <span class="badge bg-danger">Low: {{ $product->stock }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('products.edit', $product->id) }}"
                                        class="btn btn-sm btn-warning me-1">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>

                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus produk ini? Data tidak bisa dikembalikan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <p class="mb-0">Belum ada data produk.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $products->links() }}
            </div>

        </div>
    </div>
@endsection
