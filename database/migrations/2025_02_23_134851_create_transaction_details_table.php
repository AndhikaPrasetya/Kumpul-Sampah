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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade'); 
            $table->foreignId('sampah_id')->constrained('sampahs')->onDelete('cascade'); 
            $table->decimal('berat', 8, 2); // Berat dalam KG
            $table->decimal('subtotal', 10, 2); // berat * harga_per_kg
            $table->enum('status',['pending','approved','rejected'])->default('pending');
            $table->integer('points')->default(0);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
