@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card shadow border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Edit Produk: {{ $product->name }}</h5>
            </div>
            <div class="card-body">

                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $product->name) }}">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="category" class="form-select">
                                <option value="Lantai" {{ $product->category == 'Lantai' ? 'selected' : '' }}>Lantai</option>
                                <option value="Dinding" {{ $product->category == 'Dinding' ? 'selected' : '' }}>Dinding</option>
                                <option value="Granit" {{ $product->category == 'Granit' ? 'selected' : '' }}>Granit</option>
                                <option value="Interior" {{ $product->category == 'Interior' ? 'selected' : '' }}>Interior</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Produk (Biarkan kosong jika tidak diganti)</label>
                        <input type="file" name="image" class="form-control mb-2">
                        @if($product->image)
                            <small class="text-muted">Gambar saat ini:</small><br>
                            <img src="{{ asset('storage/' . $product->image) }}" width="100" class="rounded border p-1">
                        @endif
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Update Produk</button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection
