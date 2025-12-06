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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Nama Keramik/Interior
        $table->string('category')->nullable(); // Misal: 'Lantai', 'Dinding', 'Wallpaper'
        $table->decimal('price', 10, 2); // Harga (Decimal lebih aman untuk uang)
        $table->integer('stock')->default(0);
        $table->text('description')->nullable();
        $table->string('image')->nullable(); // Untuk foto produk
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
