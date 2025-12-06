<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Nempel ke bon mana
        $table->foreignId('product_id')->constrained(); // Barang apa

        $table->integer('quantity');
        $table->decimal('price', 12, 2); // Harga saat transaksi (PENTING! Jangan ambil dari tabel product, takut harga naik nanti)

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
