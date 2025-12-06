@extends('layouts.app')

@section('title', 'Detail Order ' . $order->invoice_number)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <div>
            <button onclick="window.print()" class="btn btn-outline-secondary me-2">
                <i class="bi bi-printer"></i> Cetak
            </button>

            @if ($order->status == 'pending')
                <form action="{{ route('orders.markProcessed', $order->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Apakah Anda yakin ingin memproses order ini?');">
                    @csrf
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="bi bi-check-lg"></i> Proses Order
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="card shadow border-0" id="printArea">
        <div class="card-body p-5">

            <div class="row border-bottom pb-4 mb-4">
                <div class="col-md-6">
                    <h3 class="fw-bold text-primary">INVOICE</h3>
                    <p class="text-muted mb-0">No: <strong>{{ $order->invoice_number }}</strong></p>
                    <p class="text-muted mb-0">Tgl: {{ $order->created_at->format('d F Y') }}</p>
                    <p class="mb-0 mt-2">
                        Status: <span class="badge bg-warning text-dark">{{ strtoupper($order->status) }}</span>
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <h5 class="fw-bold">Bintang Interior & Keramik</h5>
                    <p class="mb-0 text-muted">Jl. Meulaboh - Tapaktuan</p>
                    <p class="mb-0 text-muted">Aceh Barat, Indonesia</p>
                    <p class="mb-0 text-muted">Telp: 0812-3456-7890</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-uppercase text-muted small fw-bold">Kepada Yth:</h6>
                    <h5 class="fw-bold">{{ $order->customer->name }}</h5>
                    <p class="mb-0">{{ $order->customer->address }}</p>
                    <p class="mb-0">Telp: {{ $order->customer->phone ?? '-' }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h6 class="text-uppercase text-muted small fw-bold">Sales:</h6>
                    <p class="fw-bold mb-0">{{ $order->user->name }}</p>
                    <p class="small text-muted">{{ $order->user->email }}</p>
                </div>
            </div>

            <table class="table table-bordered mb-4">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="5%">#</th>
                        <th>Nama Produk</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                {{ $item->product->name }}
                                @if ($item->product->category)
                                    <br><small class="text-muted">{{ $item->product->category }}</small>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold">Rp
                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold text-uppercase">Total Tagihan</td>
                        <td class="text-end fw-bold fs-5 bg-light text-primary">Rp
                            {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            @if ($order->notes)
                <div class="alert alert-light border">
                    <strong>Catatan:</strong> {{ $order->notes }}
                </div>
            @endif

            <div class="text-center mt-5 text-muted small">
                <p>Terima kasih telah berbelanja di Bintang Interior & Keramik.</p>
            </div>

        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #printArea,
            #printArea * {
                visibility: visible;
            }

            #printArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                border: none !important;
                box-shadow: none !important;
            }

            .btn {
                display: none;
            }
        }
    </style>
@endsection
