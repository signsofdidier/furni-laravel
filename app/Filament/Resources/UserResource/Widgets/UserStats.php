<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStats extends BaseWidget
{
    protected function getStats(): array
    {
        // Stat 1: Top buyer
        $topUser = \App\Models\User::withSum('orders', 'grand_total')
            ->orderByDesc('orders_sum_grand_total')
            ->first();

        // Stat 3: Growth calculation
        $current = User::whereBetween('created_at', [now()->subDays(30), now()])->count();
        $previous = User::whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])->count();

        $diff = $current - $previous;
        $percentage = $previous > 0 ? round(($diff / $previous) * 100, 1) : 100;
        $color = $percentage >= 0 ? 'success' : 'danger';

        return [
            // Stat 1: Top buyer
            Stat::make('Top Buyer', $topUser ? "{$topUser->name} (€" . number_format($topUser->orders_sum_grand_total, 2) . ")" : 'N/A')
                ->description('Highest spending customer')
                ->descriptionIcon('heroicon-o-star')
                ->color('success'),

            // Stat 2: New users
            Stat::make('New Users (30d)', $current)
                ->description('Joined in last 30 days')
                ->descriptionIcon('heroicon-o-user-plus')
                ->color('info'),

            // Stat 3: Growth
            Stat::make('Growth', ($diff >= 0 ? '+' : '') . $percentage . '%')
                ->description('User growth vs previous 30 days')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color($color),
        ];
    }
}
