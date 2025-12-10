<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OrderController extends Controller
{
    // 1. Tampilkan Form Order
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        // Hanya ambil produk yang stoknya ada
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();

        return view('orders.create', compact('customers', 'products'));
    }

    // 2. Proses Simpan Order (Complex Logic)
    public function store(Request $request)
    {
        // A. Validasi
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1', // Harus ada minimal 1 barang
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            // Gunakan Transaction agar data konsisten
            DB::beginTransaction();

            // B. Hitung Total Harga dulu
            $totalPrice = 0;
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $totalPrice += $product->price * $item['quantity'];
            }

            // C. Buat Nomor Invoice Otomatis (Contoh: INV-20231001-0001)
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . mt_rand(1000, 9999);

            // --- LOGIKA BARU: HITUNG JATUH TEMPO (TOP) ---
            $customer = Customer::findOrFail($request->customer_id);
            $topDays = $customer->top_days ?? 0; // Ambil settingan TOP toko

            // Kalau TOP 0, berarti jatuh tempo hari ini (Cash)
            // Kalau TOP 30, berarti hari ini + 30 hari
            $dueDate = now()->addDays($topDays);

            // Default status bayar: Kalau TOP 0 dianggap Cash (Paid?), atau tetap Unpaid tunggu konfirmasi kasir?
            // Aman-nya kita set 'unpaid' dulu sampai uang diterima.
            // ---------------------------------------------

            // D. Simpan Header Order (Update array create)
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_id' => $request->customer_id,
                'invoice_number' => $invoiceNumber,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'notes' => $request->notes,

                // Tambahan Field Baru:
                'payment_status' => 'unpaid',
                'due_date' => $dueDate,
                'amount_paid' => 0,
            ]);

            // E. Simpan Detail Item & Kurangi Stok
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                // Cek stok lagi biar aman
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak cukup!");
                }

                // Simpan ke tabel order_items
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price, // Harga dikunci saat transaksi terjadi
                ]);

                // Kurangi stok produk
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit(); // Kalau semua lancar, simpan permanen
            return redirect()->route('dashboard')->with('success', 'Order berhasil dibuat! No Invoice: ' . $invoiceNumber);

        } catch (\Exception $e) {
            DB::rollBack(); // Kalau ada error, batalkan semua
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // 3. Menampilkan Daftar Order (Riwayat)
    public function index(Request $request)
    {
        // 1. Ambil List Sales untuk Dropdown Filter
        $salesList = User::where('role', 'sales')->orderBy('name')->get();

        // 2. Mulai Query (PERBAIKAN DISINI)
        $query = Order::with(['customer', 'user']);

        // 3. Cek Role User Login
        if (Auth::user()->role === 'sales') {
            // SALES: Hanya lihat order milik sendiri
            $query->where('user_id', Auth::id());
        } else {
            // ADMIN: Cek apakah ada Filter dari Dropdown?
            if ($request->has('sales_id') && $request->sales_id != '') {
                $query->where('user_id', $request->sales_id);
            }
        }

        // 4. Ambil Data
        $orders = $query->latest()->paginate(10);

        // 5. Kirim ke View
        return view('orders.index', compact('orders', 'salesList'));
    }

    // 4. Menampilkan Detail Order (Nota)
    public function show(Order $order)
    {
        // Muat juga relasi items dan product-nya
        $order->load(['customer', 'items.product']);

        return view('orders.show', compact('order'));
    }
    // 5. Method untuk mengubah status jadi 'Process'
    public function markAsProcessed(Order $order)
    {
        // Validasi: Hanya boleh diproses kalau status sekarang 'pending'
        if ($order->status == 'pending') {
            $order->update(['status' => 'process']);
            return back()->with('success', 'Status order berhasil diperbarui menjadi PROSES.');
        }

        return back()->with('error', 'Order ini sudah diproses sebelumnya.');
    }
    // 6. Fitur Pembayaran / Pelunasan
    public function pay(Request $request, Order $order)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        // 1. Tambahkan pembayaran baru ke yang sudah ada
        $newAmountPaid = $order->amount_paid + $request->amount;

        // 2. Tentukan status
        if ($newAmountPaid >= $order->total_price) {
            $status = 'paid';
            // Opsional: Kalau bayar kelebihan, bisa ditangani di sini (misal simpan sisa sebagai deposit)
        } else {
            $status = 'partial';
        }

        // 3. Update Order
        $order->update([
            'amount_paid' => $newAmountPaid,
            'payment_status' => $status
        ]);

        return back()->with('success', 'Pembayaran berhasil dicatat! Sisa tagihan diperbarui.');
    }
}
