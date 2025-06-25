<?php

namespace App\Filament\Exports;

use App\Models\ProductColorStock;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

// Exporter voor de voorraad van kleuren per product
class ProductColorStockExporter extends Exporter
{
    // Werkt met het ProductColorStock model
    protected static ?string $model = ProductColorStock::class;

    // Kolommen die we mee exporteren
    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('product.name'),
            ExportColumn::make('color.name'),
            ExportColumn::make('stock'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    // Berichtje als export klaar is (voor de notificatie)
    public static function getCompletedNotificationBody(Export $export): string
    {
        // Basisbericht met aantal goeie rijen
        $body = 'Your product color stock export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        // Als er mislukte rijen zijn, voeg dat toe aan het bericht
        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body; // Toon het bericht
    }
}
