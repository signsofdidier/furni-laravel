<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\ProductColorStock;
use App\Models\Review;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
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
            Stat::make('Pending Reviews', $pendingReviews)
                ->description('Reviews waiting for approval')
                ->descriptionIcon('heroicon-o-clock')
                ->color('danger'),

            // Stat 2: aantal producten volledig uit stock
            Stat::make('Out of Stock', $outOfStock)
                ->description('Products with 0 stock')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),

            // Stat 3: producten met minder dan 10 stuks op voorraad
            Stat::make('Low Stock (<10)', $lowStock)
                ->description('Products with low stock levels')
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->color('warning'),
        ];
    }
}
