<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Models\OrderItem;
use App\Models\Brand;
use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ProductStats extends BaseWidget
{
    protected function getStats(): array
    {
        // 1. Best verkochte product
        $topProduct = OrderItem::selectRaw('product_id, SUM(quantity) as total')
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->with('product')
            ->first();

        // Neemt de naam van de producten uit de database
        $topProductName = $topProduct?->product?->name ?? 'N/A';
        $topProductQty = $topProduct?->total ?? 0;

        // 2. Meest verkochte merk
        $topBrand = OrderItem::selectRaw('products.brand_id, SUM(order_items.quantity) as total')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.brand_id')
            ->orderByDesc('total')
            ->first();

        $brandName = 'N/A';
        if ($topBrand && $topBrand->brand_id) {
            $brand = Brand::find($topBrand->brand_id);
            $brandName = $brand?->name ?? 'N/A';
        }

        // 3. Meest verkochte categorie
        $topCategory = OrderItem::selectRaw('products.category_id, SUM(order_items.quantity) as total')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.category_id')
            ->orderByDesc('total')
            ->first();

        $categoryName = 'N/A';
        if ($topCategory && $topCategory->category_id) {
            $category = Category::find($topCategory->category_id);
            $categoryName = $category?->name ?? 'N/A';
        }

        return [
            // Stat 1: Top product
            Stat::make('Top Product', $topProductName)
                ->description('Most sold product' . ' (' . $topProductQty . ')')
                ->descriptionIcon('heroicon-o-fire')
                ->color('success'),

            // Stat 2: Top brand
            Stat::make('Top Brand', $brandName)
                ->description('Most sold brand')
                ->descriptionIcon('heroicon-o-tag')
                ->color('info'),

            // Stat 3: Top category
            Stat::make('Top Category', $categoryName)
                ->description('Most sold category')
                ->descriptionIcon('heroicon-o-archive-box')
                ->color('warning'),
        ];
    }
}
