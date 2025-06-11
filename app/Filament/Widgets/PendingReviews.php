<?php

namespace App\Filament\Widgets;

use App\Models\Review;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PendingReviews extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        return [
            Stat::make('Pending Reviews', Review::where('approved', false)->count())
                ->description('Pending Reviews for approval')
                ->descriptionIcon('heroicon-o-clock')
                ->color('danger'),
        ];
    }
}
