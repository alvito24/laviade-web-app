<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the ENUM to include 'banner' and 'sale' types
        DB::statement("ALTER TABLE campaigns MODIFY COLUMN type ENUM('hero_slider', 'promotion', 'collection', 'flash_sale', 'banner', 'sale') DEFAULT 'hero_slider'");

        // Make start_date and end_date nullable
        Schema::table('campaigns', function (Blueprint $table) {
            $table->timestamp('start_date')->nullable()->change();
            $table->timestamp('end_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original ENUM
        DB::statement("ALTER TABLE campaigns MODIFY COLUMN type ENUM('hero_slider', 'promotion', 'collection', 'flash_sale') DEFAULT 'hero_slider'");

        // Make dates not nullable again
        Schema::table('campaigns', function (Blueprint $table) {
            $table->timestamp('start_date')->nullable(false)->change();
            $table->timestamp('end_date')->nullable(false)->change();
        });
    }
};
