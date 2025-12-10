<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReceivableExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
    * 1. Ambil Data dari Database
    */
    public function collection()
    {
        // Ambil data yang belum lunas (unpaid/partial), urutkan dari yang paling telat
        return Order::with('customer', 'user')
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->orderBy('due_date', 'asc')
            ->get();
    }

    /**
    * 2. Tentukan Judul Kolom (Header Excel)
    */
    public function headings(): array
    {
        return [
            'No. Invoice',
            'Tanggal Order',
            'Nama Toko / Customer',
            'Sales',
            'Jatuh Tempo',
            'Total Tagihan (Rp)',
            'Sudah Dibayar (Rp)',
            'Sisa Hutang (Rp)',
            'Status Keterlambatan',
        ];
    }

    /**
    * 3. Mapping Data (Isi Baris per Baris)
    */
    public function map($invoice): array
    {
        // Hitung telat berapa hari
        $daysOverdue = now()->diffInDays($invoice->due_date, false);

        if ($daysOverdue < 0) {
            $status = "TELAT " . abs($daysOverdue) . " Hari";
        } else {
            $status = "Aman (" . $daysOverdue . " hari lagi)";
        }

        return [
            $invoice->invoice_number,
            $invoice->created_at->format('d-m-Y'),
            $invoice->customer->name,
            $invoice->user->name, // Nama Sales
            $invoice->due_date->format('d-m-Y'),
            $invoice->total_price,
            $invoice->amount_paid,
            ($invoice->total_price - $invoice->amount_paid), // Hitung sisa
            $status,
        ];
    }

    /**
    * 4. Styling (Biar Header Tebal)
    */
    public function styles(Worksheet $sheet)
    {
        return [
            // Baris 1 (Header) di-bold
            1    => ['font' => ['bold' => true]],
        ];
    }
}
