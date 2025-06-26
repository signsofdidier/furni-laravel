<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrdersPerDayChart extends ChartWidget
{
    protected static ?string $heading = 'Orders per day';

    protected int | string | array $columnSpan = '2/2';

    // sort de widget
    protected static ?int $sort = 2;


    protected function getData(): array
    {

        // GEGEVENS VAN FLOWFRAME LARAVEL TREND composer
        $data = Trend::model(Order::class)
            ->between(
                start: now()->subDays(60),
                end: now(), // tot vandaag
            )
            ->perDay() // groepeer per dag
            ->count(); // tel het aantal orders per dag

        // Retourneer datasets en labels voor de grafiek
        return [
            'datasets' => [
                [
                    'label' => 'Orders per day', // Tekst in de legenda
                    // Alle waarden (orders per dag) in een array
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            // X-as: alle datums in de juiste volgorde
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
