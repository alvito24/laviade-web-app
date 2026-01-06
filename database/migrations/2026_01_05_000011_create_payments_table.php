<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_method'); // bank_transfer, e-wallet, cod
            $table->string('payment_channel')->nullable(); // BCA, Mandiri, Gopay, etc
            $table->decimal('amount', 12, 2);
            $table->enum('status', [
                'pending',
                'waiting_confirmation',
                'confirmed',
                'failed',
                'expired',
                'refunded'
            ])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->string('payment_proof')->nullable();
            $table->json('payment_details')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
