@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card shadow border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Tambah Produk Baru</h5>
            </div>
            <div class="card-body">

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Keramik Roman 40x40">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="category" class="form-select">
                                <option value="Lantai">Lantai</option>
                                <option value="Dinding">Dinding</option>
                                <option value="Granit">Granit</option>
                                <option value="Interior">Interior</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok Awal</label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}">
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}">
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Produk</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Produk</button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection
