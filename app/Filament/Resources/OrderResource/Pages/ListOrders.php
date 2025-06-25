<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    // button om nieuw order te maken
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // widgets bovenaan
    protected function getHeaderWidgets(): array
    {
        return [
            OrderStats::class
        ];
    }

    // TABS VOOR DE STATUS QUERY FILTERS
    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'new' => Tab::make('New'),
            'processing' => Tab::make('Processing')->query(fn ($query) => $query->where('status' , 'processing')),
            'shipped' => Tab::make('Shipped')->query(fn ($query) => $query->where('status', 'shipped')),
            'delivered' => Tab::make('Delivered')->query(fn ($query) => $query->where('status', 'delivered')),
            'cancelled' => Tab::make('Cancelled')->query(fn ($query) => $query->where('status', 'cancelled')),
        ];
    }

}
