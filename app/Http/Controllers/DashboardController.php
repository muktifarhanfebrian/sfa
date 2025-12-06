<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // --- LOGIKA SALES ---
        // 1. Hitung Visit Hari Ini (Khusus User yang Login)
        $todayVisits = \App\Models\Visit::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // 2. Ambil Target Harian (Default 0 jika null)
        $visitTarget = $user->daily_visit_target ?? 0;

        // 3. Hitung Persentase (Cegah pembagian dengan nol)
        $visitPercentage = $visitTarget > 0 ? ($todayVisits / $visitTarget) * 100 : 0;

        // --- LOGIKA GLOBAL (Untuk Widget Lain) ---
        $totalOrders = Order::count(); // Admin lihat semua
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        $totalRevenue = Order::sum('total_price');
        $lowStockCount = Product::where('stock', '<', 10)->count();

        return view('dashboard', compact(
            'todayVisits',
            'visitTarget',
            'visitPercentage',
            'totalOrders',
            'todayOrders',
            'totalRevenue',
            'lowStockCount'
        ));
    }
}
