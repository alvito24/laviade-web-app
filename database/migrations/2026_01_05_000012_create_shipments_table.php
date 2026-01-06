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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('courier'); // JNE, JNT, SiCepat, etc
            $table->string('service')->nullable(); // REG, OKE, YES, etc
            $table->string('tracking_number')->nullable();
            $table->enum('status', [
                'pending',
                'picked_up',
                'in_transit',
                'out_for_delivery',
                'delivered',
                'returned'
            ])->default('pending');
            $table->decimal('weight', 8, 2)->nullable(); // in grams
            $table->decimal('shipping_cost', 12, 2);
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->text('recipient_address');
            $table->json('tracking_history')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
