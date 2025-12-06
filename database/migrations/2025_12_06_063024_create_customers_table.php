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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Toko / Nama Klien
            $table->string('contact_person')->nullable(); // Nama Pemilik
            $table->string('phone')->nullable();
            $table->text('address');

            // Opsional: Koordinat Maps (Lat/Long) untuk fitur Check-in nanti
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            // Di dalam Schema::create('customers'...)
            $table->integer('top_days')->default(0); // Term of Payment (hari), misal 30
            $table->decimal('credit_limit', 12, 2)->default(0); // Batas maksimal utang (opsional)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
