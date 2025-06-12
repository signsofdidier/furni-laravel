<?php

namespace App\Filament\Resources\ProductColorStockResource\Pages;

use App\Filament\Resources\ProductColorStockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductColorStocks extends ListRecords
{
    protected static string $resource = ProductColorStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

}
