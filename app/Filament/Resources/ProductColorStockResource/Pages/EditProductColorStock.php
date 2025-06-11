<?php

namespace App\Filament\Resources\ProductColorStockResource\Pages;

use App\Filament\Resources\ProductColorStockResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductColorStock extends EditRecord
{
    protected static string $resource = ProductColorStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
