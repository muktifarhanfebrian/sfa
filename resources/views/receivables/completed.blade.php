@extends('layouts.app')

@section('title', 'Arsip Bon Lunas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold text-gray-800">Arsip Pembayaran Lunas</h1>
    <a href="{{ route('receivables.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Piutang
    </a>
</div>

<div class="card shadow border-0 border-start border-success border-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Invoice</th>
                        <th>Toko / Customer</th>
                        <th>Total Transaksi</th>
                        <th>Tanggal Lunas</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $inv)
                    <tr>
                        <td>
                            <span class="fw-bold text-success">{{ $inv->invoice_number }}</span><br>
                            <small class="text-muted">{{ $inv->created_at->format('d M Y') }}</small>
                        </td>
                        <td>
                            {{ $inv->customer->name }}<br>
                            <small class="text-muted">Sales: {{ $inv->user->name }}</small>
                        </td>
                        <td>
                            <span class="fw-bold">Rp {{ number_format($inv->total_price, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            {{ $inv->updated_at->format('d M Y H:i') }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success shadow-sm">
                                <i class="bi bi-check-circle-fill"></i> LUNAS
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('orders.show', $inv->id) }}" class="btn btn-sm btn-light border">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Belum ada riwayat pelunasan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $invoices->links() }}
        </div>
    </div>
</div>
@endsection
