<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Notifications\InvoiceDueReminder; // Jangan lupa import ini
use Carbon\Carbon;

class SendInvoiceReminders extends Command
{
    // 1. Nama perintah buat si Robot
    protected $signature = 'invoice:remind';

    // 2. Deskripsi tugasnya
    protected $description = 'Kirim notifikasi otomatis untuk tagihan H-3 jatuh tempo';

    // 3. Otak Robotnya (Logika Kerja)
    public function handle()
    {
        $this->info('Sedang memeriksa tagihan bertingkat...');

        // Daftar hari yang mau diingatkan (H-7, H-3, H-2, H-1)
        $intervals = [7, 3, 2, 1];
        $totalSent = 0;

        foreach ($intervals as $days) {

            // Hitung tanggal target. Contoh: Hari ini tgl 9. Target H+7 = Tgl 16.
            $targetDate = Carbon::now()->addDays($days)->format('Y-m-d');

            // Cari order unpaid yang due_date-nya PAS tanggal target tersebut
            $orders = Order::where('payment_status', 'unpaid')
                           ->whereDate('due_date', $targetDate)
                           ->get();

            foreach ($orders as $order) {
                // Kirim Notif dengan parameter sisa hari ($days)
                $order->user->notify(new InvoiceDueReminder($order, $days));

                $this->info("[$days Hari Lagi] Notif dikirim: " . $order->invoice_number);
                $totalSent++;
            }
        }

        $this->info("Selesai! Total $totalSent notifikasi terkirim hari ini.");
    }
}
