@extends('layouts.app')

@section('title', 'Monitoring Piutang')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-gray-800">Monitoring Piutang & Jatuh Tempo</h1>
        <a href="{{ route('receivables.export') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-file-earmark-excel-fill"></i> Download Excel
        </a>
        <a href="{{ route('receivables.completed') }}" class="btn btn-outline-success shadow-sm me-2">
            <i class="bi bi-archive-fill"></i> Arsip Lunas
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Total Tagihan</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($invoices as $inv)
                            @php
                                // Hitung selisih hari
                                $daysOverdue = floor(now()->diffInDays($inv->due_date, false));
                                // diffInDays(..., false) -> kalau lewat jadi minus

                                $isOverdue = $daysOverdue < 0;
                                $rowClass = $isOverdue ? 'table-danger' : '';
                            @endphp

                            <tr class="{{ $rowClass }}">
                                <td>
                                    <span class="fw-bold text-primary">{{ $inv->invoice_number }}</span><br>
                                    <small class="text-muted">{{ $inv->created_at->format('d M Y') }}</small>
                                </td>
                                <td>
                                    {{ $inv->customer->name }}<br>
                                    <small class="text-muted">Sales: {{ $inv->user->name }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold">Rp {{ number_format($inv->total_price, 0, ',', '.') }}</span>
                                    @if ($inv->amount_paid > 0)
                                        <br><small class="text-success">Dibayar:
                                            {{ number_format($inv->amount_paid) }}</small>
                                    @endif
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($inv->due_date)->format('d M Y') }}
                                </td>
                                <td>
                                    @if ($inv->payment_status == 'paid')
                                        <span class="badge bg-success border border-light shadow-sm">
                                            <i class="bi bi-check-circle-fill"></i> LUNAS
                                        </span>
                                        <br><small class="text-muted">Selesai</small>
                                    @else
                                        @if ($isOverdue)
                                            <span class="badge bg-danger">Telat {{ abs($daysOverdue) }} Hari!</span>
                                        @else
                                            <span class="badge bg-success">Aman ({{ $daysOverdue }} hari lagi)</span>
                                        @endif

                                        @if ($inv->payment_status == 'partial')
                                            <span class="badge bg-warning text-dark ms-1">Cicil</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if (Auth::user()->role !== 'sales')
                                        <a href="{{ route('orders.show', $inv->id) }}" class="btn btn-sm btn-light border">
                                            Detail
                                        </a>
                                    @endif

                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                        data-bs-target="#paymentModal{{ $inv->id }}">
                                        <i class="bi bi-cash-stack"></i> Bayar
                                    </button>
                                    <div class="modal fade" id="paymentModal{{ $inv->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title fw-bold">Input Pembayaran</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('orders.pay', $inv->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body text-start">

                                                        <div class="mb-3">
                                                            <label class="form-label">Total Tagihan</label>
                                                            <input type="text" class="form-control fw-bold"
                                                                value="Rp {{ number_format($inv->total_price, 0, ',', '.') }}"
                                                                readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Sudah Dibayar</label>
                                                            <input type="text" class="form-control text-success"
                                                                value="Rp {{ number_format($inv->amount_paid, 0, ',', '.') }}"
                                                                readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Sisa Kekurangan</label>
                                                            <input type="text"
                                                                class="form-control bg-light text-danger fw-bold"
                                                                value="Rp {{ number_format($inv->total_price - $inv->amount_paid, 0, ',', '.') }}"
                                                                readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Input Nominal Bayar
                                                                (Rp)
                                                            </label>
                                                            <input type="number" name="amount"
                                                                class="form-control form-control-lg" required min="1000"
                                                                placeholder="Contoh: 1000000">
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success fw-bold">Simpan
                                                            Pembayaran</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-check-circle-fill fs-1 text-success d-block mb-2"></i>
                                    Tidak ada tagihan yang belum lunas. Cashflow aman!
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
