<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Data Dashboard Umum
        // Hitung Visit Hari Ini (Hanya yang SUDAH SELESAI / COMPLETED)
        $todayVisits = \App\Models\Visit::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereDate('completed_at', Carbon::today())
            ->count();

        $visitTarget = $user->daily_visit_target ?? 0;
        $visitPercentage = $visitTarget > 0 ? ($todayVisits / $visitTarget) * 100 : 0;

        // Ambil Rencana Kunjungan
        $plannedVisits = \App\Models\Visit::with('customer')
            ->where('user_id', $user->id)
            ->where('status', 'planned')
            ->whereDate('visit_date', Carbon::today())
            ->get();

        // Data Widget Dasar
        $totalOrders = Order::count();
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        $lowStockCount = Product::where('stock', '<', 10)->count();

        // --- 2. LOGIKA KEUANGAN (PERBAIKAN ERROR) ---

        // Total Penjualan (Omset Kotor)
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_price');

        // Uang Diterima (Cash In) --> INI YANG BIKIN ERROR TADI
        $cashReceived = Order::where('status', '!=', 'cancelled')->sum('amount_paid');

        // Sisa Piutang (Outstanding) --> INI JUGA
        $totalReceivable = $totalRevenue - $cashReceived;


        // 3. Data Grafik & Leaderboard (Seperti sebelumnya)
        $chartLabels = [];
        $chartData = [];
        $topSales = [];
        $salesNames = [];
        $salesRevenue = [];

        // Logika Grafik (Untuk Admin/Semua)
        $monthlySales = array_fill(1, 12, 0);
        $salesData = \App\Models\Order::selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->whereYear('created_at', date('Y'))
            ->where('status', '!=', 'cancelled')
            ->groupBy('month')
            ->pluck('total', 'month');

        foreach ($salesData as $month => $total) {
            $monthlySales[$month] = $total;
        }
        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartData = array_values($monthlySales);

        // Logika Leaderboard (Khusus Admin)
        if ($user->role !== 'sales') {
            $topSales = \App\Models\User::where('role', 'sales')
                ->withSum(['orders' => function($q) {
                    $q->whereMonth('created_at', date('m'))
                      ->whereYear('created_at', date('Y'))
                      ->where('status', '!=', 'cancelled');
                }], 'total_price')
                ->withCount(['visits' => function($q) {
                    $q->whereMonth('created_at', date('m'))
                      ->whereYear('created_at', date('Y'))
                      ->where('status', 'completed');
                }])
                ->orderByDesc('orders_sum_total_price')
                ->take(5)
                ->get();

            $salesNames = $topSales->pluck('name')->toArray();
            $salesRevenue = $topSales->pluck('orders_sum_total_price')->toArray();
        }

        // 4. KIRIM SEMUA KE VIEW
        return view('dashboard', compact(
            // Data Visit
            'todayVisits', 'visitTarget', 'visitPercentage', 'plannedVisits',

            // Data Widget & Keuangan (PASTIKAN cashReceived ADA DISINI)
            'totalOrders', 'todayOrders', 'lowStockCount',
            'totalRevenue', 'cashReceived', 'totalReceivable',

            // Data Grafik & Leaderboard
            'chartLabels', 'chartData', 'topSales', 'salesNames', 'salesRevenue'
        ));
    }
}
