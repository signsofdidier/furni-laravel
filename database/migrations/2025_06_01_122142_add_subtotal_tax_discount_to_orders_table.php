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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('sub_total', 10, 2)
                ->before('grand_total')
                ->default(0);
            $table->decimal('tax_amount', 10, 2)
                ->after('grand_total')
                ->default(0);
            $table->decimal('discount_amount', 10, 2)
                ->after('tax_amount')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['sub_total', 'tax_amount', 'discount_amount']);
        });
    }
};
