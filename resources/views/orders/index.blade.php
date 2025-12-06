@extends('layouts.app')

@section('title', 'Riwayat Order')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold text-gray-800">Riwayat Transaksi</h1>
    <a href="{{ route('orders.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Order Baru
    </a>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No Invoice</th>
                        <th>Pelanggan</th>
                        <th>Sales</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                    <tr>
                        <td class="fw-bold text-primary">{{ $order->invoice_number }}</td>
                        <td>{{ $order->customer->name }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td class="fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>
                            @if($order->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($order->status == 'process')
                                <span class="badge bg-info text-dark">Proses</span>
                            @elseif($order->status == 'completed')
                                <span class="badge bg-success">Selesai</span>
                            @else
                                <span class="badge bg-danger">Batal</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Belum ada transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
