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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('bsu_id')->constrained('users')->onDelete('cascade'); 
            $table->dateTime('tanggal');
            $table->enum('status',['pending','approved','rejected'])->default('pending');
            $table->decimal('total_amount', 10, 2)->default(0); // Total dari transaction_details
            $table->integer('total_points')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
