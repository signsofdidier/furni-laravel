<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\ProductColorStock;
use App\Models\Review;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    use HasWidgetShield;

    // Bepaalt de plaats van deze widget op het dashboard
    protected static ?int $sort = 1;

    // Hier definieer je de 3 stat-kaarten naast elkaar
    protected function getStats(): array
    {
        // Reviews die nog niet goedgekeurd zijn
        $pendingReviews = Review::where('approved', false)->count();

        // Totaal aantal uitverkochte kleurcombinaties
        $outOfStock = ProductColorStock::where('stock', 0)->count();

        // Kleurcombinaties met lage voorraad (tussen 1 en 9)
        $lowStock = ProductColorStock::whereBetween('stock', [1, 9])->count();

        return [
            // Stat 1: aantal reviews die nog goedgekeurd moeten worden
            Stat::make('🕓 Pending Reviews', $pendingReviews)
                ->description('Reviews waiting for approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->extraAttributes(['class' => 'bg-yellow-50']),

            // Stat 2: aantal producten volledig uit stock
            Stat::make('❌ Out of Stock', $outOfStock)
                ->description('Product colors with 0 stock')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger')
                ->extraAttributes(['class' => 'bg-red-50']),

            // Stat 3: producten met minder dan 10 stuks op voorraad
            Stat::make('⚠️ Low Stock (<10)', $lowStock)
                ->description('Colors with low stock')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('gray')
                ->extraAttributes(['class' => 'bg-orange-50']),
        ];
    }
}
