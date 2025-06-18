<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalRevenueStats extends BaseWidget
{

    protected function getStats(): array
    {
        // Sparkline data
        $last7DaysChart = collect(range(0, 6))->map(fn ($i) =>
        Order::whereDate('created_at', now()->subDays($i))->sum('grand_total')
        )->reverse()->values()->toArray();

        $last30DaysChart = collect(range(0, 29))->map(fn ($i) =>
        Order::whereDate('created_at', now()->subDays($i))->sum('grand_total')
        )->reverse()->values()->toArray();

        return [
            Stat::make('Revenue Today',
                '€ ' . number_format(Order::whereDate('created_at', today())->sum('grand_total'), 2)
            )
                ->description('Total revenue today')
                ->descriptionIcon('heroicon-o-currency-euro', IconPosition::Before)
                ->color('success')
                ->chart($last7DaysChart), // Laatste 7 dagen als sparkline

            Stat::make('Revenue Last 7 Days',
                '€ ' . number_format(Order::where('created_at', '>=', now()->subDays(7))->sum('grand_total'), 2)
            )
                ->description('Weekly trend')
                ->descriptionIcon('heroicon-o-chart-bar', IconPosition::Before)
                ->color('warning')
                ->chart($last7DaysChart), // Zelfde data als bij Today

            Stat::make('Revenue Last 30 Days',
                '€ ' . number_format(Order::where('created_at', '>=', now()->subDays(30))->sum('grand_total'), 2)
            )
                ->description('Monthly overview')
                ->descriptionIcon('heroicon-o-calendar', IconPosition::Before)
                ->color('info')
                ->chart($last30DaysChart),
        ];
    }
}
