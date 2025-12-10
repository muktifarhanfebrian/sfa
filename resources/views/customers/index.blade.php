@extends('layouts.app')

@section('title', 'Data Pelanggan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-gray-800">Data Toko / Pelanggan</h1>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">
            <i class="bi bi-shop"></i> Tambah Toko Baru
        </a>
    </div>

    @if (Auth::user()->role !== 'sales')
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body py-3">
                <form action="{{ route('customers.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-auto">
                        <span class="fw-bold text-muted"><i class="bi bi-funnel"></i> Filter Sales:</span>
                    </div>
                    <div class="col-auto">
                        <select name="sales_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">-- Tampilkan Semua --</option>
                            @foreach ($salesList as $sales)
                                <option value="{{ $sales->id }}"
                                    {{ request('sales_id') == $sales->id ? 'selected' : '' }}>
                                    {{ $sales->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        @if (request('sales_id'))
                            <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="card shadow border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Toko</th>
                            {{-- Tampilkan kolom Sales hanya untuk Admin/Manager --}}
                            @if (Auth::user()->role !== 'sales')
                                <th>Sales (PIC)</th>
                            @endif
                            <th>Kontak</th>
                            <th>Alamat</th>
                            {{-- Tampilkan kolom Keuangan & Aksi hanya untuk Admin/Manager --}}
                            @if (Auth::user()->role !== 'sales')
                                <th>Keuangan (TOP)</th>
                                <th class="text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                {{-- 1. Nama Toko --}}
                                <td class="fw-bold">{{ $customer->name }}</td>

                                {{-- 2. Sales (PIC) - Khusus Admin --}}
                                @if (Auth::user()->role !== 'sales')
                                    <td>
                                        @if ($customer->user)
                                            <span class="badge bg-secondary">{{ $customer->user->name }}</span>
                                        @else
                                            <span class="text-muted fst-italic">Tanpa Sales</span>
                                        @endif
                                    </td>
                                @endif

                                {{-- 3. Kontak (Perbaikan struktur TD) --}}
                                <td>
                                    {{ $customer->contact_person ?? '-' }}<br>
                                    <small class="text-muted">{{ $customer->phone }}</small>
                                </td>

                                {{-- 4. Alamat --}}
                                <td>{{ Str::limit($customer->address, 40) }}</td>

                                {{-- 5. Keuangan & Aksi - Khusus Admin --}}
                                @if (Auth::user()->role !== 'sales')
                                    <td>
                                        <span class="badge bg-info text-dark">TOP: {{ $customer->top_days }}
                                            Hari</span><br>
                                        <small class="text-muted">Limit: Rp
                                            {{ number_format($customer->credit_limit, 0, ',', '.') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('customers.edit', $customer->id) }}"
                                            class="btn btn-sm btn-warning me-1">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Hapus toko ini?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                {{-- Hitung colspan dinamis agar pesan "Belum ada data" tetap rapi --}}
                                @php
                                    $colspan = Auth::user()->role !== 'sales' ? 6 : 3;
                                @endphp
                                <td colspan="{{ $colspan }}" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Belum ada data toko.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
@endsection
