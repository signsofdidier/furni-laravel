<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// RelationManager om de orders van een user te tonen
class OrdersRelationManager extends RelationManager
{
    // Dit zegt aan Filament: dit gaat over de 'orders' relatie op User
    protected static string $relationship = 'Orders';

    // Geen formulier nodig (orders maken via user is niet de bedoeling)
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    // dit zorgt ervoor dat de orders worden weergegeven
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                    ->label('Order ID')
                    ->searchable(),

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

                TextColumn::make('payment_method')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('payment_status')
                    ->sortable()
                    ->searchable()
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime()

            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),

                // custom view order action
                Tables\Actions\Action::make('View Order')
                    ->url(fn (Order $record):string => OrderResource::getUrl('view', ['record' => $record]))
                    ->color('info')
                    ->icon('heroicon-m-eye'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
