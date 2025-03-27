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
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bsu_id')->constrained('users')->onDelete('cascade'); 
            $table->string('name');
            $table->string('deskripsi');
            $table->string('image')->nullable();
            $table->decimal('points',10, 2)->default(0);
            $table->integer('stok');
            $table->date('tanggal_expired');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};
