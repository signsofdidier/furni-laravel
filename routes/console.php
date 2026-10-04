<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:seed-if-empty', function () {
    if (\App\Models\User::query()->exists()) {
        $this->info('Database bevat al data, seeden overgeslagen.');
        return;
    }

    $this->call('db:seed', ['--force' => true]);
})->purpose('Seed de database enkel bij de eerste deploy');
