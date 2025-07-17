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
        Schema::create('kelurahan_details', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis (primary key)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('kode_pos', 10)->nullable(); // Kode pos bisa null
            $table->string('alamat');
            $table->string('kecamatan');
            $table->string('kota');
            $table->string('provinsi');
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**s
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelurahan_details');
    }

};
