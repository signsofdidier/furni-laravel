<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('colors', function (Blueprint $table) {
            // Haal eerst de foreign key constraint weg (indien van toepassing)
            $table->dropForeign(['product_id']);
            // Verwijder de kolom zelf
            $table->dropColumn('product_id');
        });
    }

    public function down(): void
    {
        Schema::table('colors', function (Blueprint $table) {
            // Terugzetten: maak de kolom weer aan
            $table->foreignId('product_id')
                ->nullable()            // óf ->constrained()->cascadeOnDelete() als je dat wilt
                ->after('id');
        });
    }
};
