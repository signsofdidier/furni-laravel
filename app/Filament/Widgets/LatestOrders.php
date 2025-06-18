<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{

    use HasWidgetShield;

    protected int | string | array $columnSpan = '2/2';

    // sort de widget
    protected static ?int $sort = 2;


    public function table(Table $table): Table
    {
        return $table
            // dit toont de laatste 5 orders
            ->query(OrderResource::getEloquentQuery())
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(3)

            ->columns([
                /*TextColumn::make('id')
                    ->label('Order ID')
                    ->searchable(),*/

                TextColumn::make('user.name')
                    //->searchable()
                    ->sortable(),

                Textcolumn::make('grand_total')
                    ->label('Grand Total')
                    ->money('EUR'),

                TextColumn::make('status')
                    ->badge()
                    // geef de status badge de juiste kleur
                    ->color(fn (string $state):string => match($state){
                        'new' => 'primary',
                        'processing' => 'warning',
                        'shipped' => 'success',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->icon(fn (string $state):string => match($state){
                        'new' => 'heroicon-m-sparkles',
                        'processing' => 'heroicon-m-arrow-path',
                        'shipped' => 'heroicon-m-truck',
                        'delivered' => 'heroicon-m-truck',
                        'cancelled' => 'heroicon-m-x-circle',
                    })
                    ->sortable(),

                /*TextColumn::make('payment_method')
                    ->sortable()
                    ->searchable(),*/

                /*TextColumn::make('payment_status')
                    ->sortable()
                    //->searchable()
                    ->badge(),*/

                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->since()
            ])
            ->actions([
                // custom View Order action: view de order
                Action::make('View Order')
                    ->label('')
                    ->url(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-m-eye')
            ]);
    }
}
