<?php

namespace App\Filament\Widgets;

use App\Models\Product;
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
        // Tel alle reviews die nog niet goedgekeurd zijn
        $pendingReviews = Review::where('approved', false)->count();

        // Laad alle producten met voorraadgegevens
        $products = Product::with('productColorStocks')->get();

        // Tel producten met totaal 0 voorraad
        $outOfStock = $products->filter(
            fn ($product) => $product->productColorStocks->sum('stock') <= 0
        )->count();

        // Tel producten met voorraad kleiner dan 10, maar meer dan 0
        $lowStock = $products->filter(
            fn ($product) => $product->productColorStocks->sum('stock') < 10 && $product->productColorStocks->sum('stock') > 0
        )->count();

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
