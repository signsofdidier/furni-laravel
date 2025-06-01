<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFreeShippingEnabledToSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            // Voeg een boolean-kolom toe, standaard op true (actief)
            $table->boolean('free_shipping_enabled')->default(true)->after('free_shipping_threshold');
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('free_shipping_enabled');
        });
    }
}
