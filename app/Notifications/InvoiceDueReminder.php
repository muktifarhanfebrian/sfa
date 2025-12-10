<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class InvoiceDueReminder extends Notification
{
    use Queueable;

    public $order;
    public $daysLeft; // Tambahkan variabel ini

    // Terima Order DAN Sisa Hari
    public function __construct(Order $order, $daysLeft)
    {
        $this->order = $order;
        $this->daysLeft = $daysLeft;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        // Ubah pesan & warna ikon berdasarkan urgensi
        $urgencyColor = $this->daysLeft <= 2 ? 'text-danger' : 'text-warning';
        $iconType = $this->daysLeft <= 1 ? 'bi-alarm-fill' : 'bi-clock-history';

        return [
            'order_id' => $this->order->id,
            'title' => $this->daysLeft == 1 ? '⚠️ BESOK Jatuh Tempo!' : 'Tagihan Jatuh Tempo',
            'message' => 'Invoice ' . $this->order->invoice_number . ' (' . $this->order->customer->name . ') jatuh tempo ' . $this->daysLeft . ' hari lagi.',
            'link' => route('orders.show', $this->order->id),
            'icon' => $iconType . ' ' . $urgencyColor, // Ikon merah kalau mepet
        ];
    }
}
