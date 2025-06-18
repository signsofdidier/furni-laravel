<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStats extends BaseWidget
{
    use HasWidgetShield;

    protected function getStats(): array
    {
        return [
            // NEW
            Stat::make('New Orders', Order::query()->where('status', 'new')->count())
                ->description('Orders waiting to be handled')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            // PROCESSING
            Stat::make('Order Processing', Order::query()->where('status', 'processing')->count())
                ->description('Orders in processing')
                ->descriptionIcon('heroicon-o-arrow-path')
                ->color('info'),

            // SHIPPED
            Stat::make('Order Shipped', Order::query()->where('status', 'shipped')->count())
                ->description('Orders sent to customers')
                ->descriptionIcon('heroicon-o-truck')
                ->color('success'),


            // GEMIDDELDE PRIJS
            Stat::make('Average Price',
                Number::currency(
                    Order::query()->avg('grand_total') ?? 0, 'EUR'))
                ->description('Average value per order')
                ->descriptionIcon('heroicon-o-currency-euro')
                ->color('gray'),
        ];
    }
}
