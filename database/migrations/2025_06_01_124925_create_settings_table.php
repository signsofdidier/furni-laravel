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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // Vanaf dit bedrag is er gratis verzending
            $table->decimal('free_shipping_threshold', 10, 2)
                ->default(1000.00)
                ->comment('Amount from which shipping is free');

            // Voeg een boolean-kolom toe, standaard op true (actief)
            $table->boolean('free_shipping_enabled')->default(true);

            $table->timestamps();
        });

        // Direct een rij met default
        // Zo moet de admin na migratie niet eerst handmatig een nieuwe rij toe te voegen.
        DB::table('settings')->insert([
            'free_shipping_threshold' => 1000.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
