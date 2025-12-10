<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\InvoiceDueReminder;
use App\Exports\ReceivableExport;
use Maatwebsite\Excel\Facades\Excel;

class ReceivableController extends Controller
{
    public function index()
    {
        // Ambil order yang BELUM LUNAS ('unpaid' atau 'partial')
        // Urutkan berdasarkan Jatuh Tempo terdekat (ASC) biar yang mau telat muncul duluan
        $invoices = Order::with(['customer', 'user'])
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->orderBy('due_date', 'asc')
            ->paginate(10);

        return view('receivables.index', compact('invoices'));
    }
    // Method Baru: Cek Tagihan H-3
    public function sendReminders()
    {
        // Cari order yang belum lunas DAN jatuh temponya 3 hari lagi dari sekarang
        $orders = Order::where('payment_status', 'unpaid')
            ->whereDate('due_date', Carbon::now()->addDays(3)->format('Y-m-d'))
            ->get();

        $count = 0;
        foreach ($orders as $order) {
            // Kirim notifikasi ke Sales pemilik order tersebut
            // Cek dulu biar gak spam (opsional), tapi ini kita kirim langsung
            $order->user->notify(new InvoiceDueReminder($order));
            $count++;
        }

        return back()->with('success', "Berhasil! $count notifikasi dikirim ke sales.");
    }
    public function export()
    {
        // Download file bernama 'laporan_piutang_tgl_xxx.xlsx'
        $fileName = 'laporan_piutang_' . date('d-m-Y') . '.xlsx';

        return Excel::download(new ReceivableExport, $fileName);
    }
    // Halaman Arsip Lunas
    public function completed()
    {
        // Ambil hanya yang statusnya PAID
        $invoices = Order::with(['customer', 'user'])
                    ->where('payment_status', 'paid')
                    ->latest() // Urutkan dari yang terbaru
                    ->paginate(10);

        return view('receivables.completed', compact('invoices'));
    }
}
