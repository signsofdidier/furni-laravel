<?php

namespace App\Filament\Exports;

use App\Models\Product;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ProductExporter extends Exporter
{
    // Werkt met het Product model
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('category_id'),
            ExportColumn::make('brand_id'),
            ExportColumn::make('name'),
            ExportColumn::make('slug'),
            ExportColumn::make('images'),
            ExportColumn::make('description'),
            ExportColumn::make('price'),
            ExportColumn::make('is_active'),
            ExportColumn::make('is_featured'),
            ExportColumn::make('in_stock'),
            ExportColumn::make('on_sale'),
            ExportColumn::make('shipping_cost'),
            ExportColumn::make('deleted_at'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    // Bericht dat je krijgt als de export klaar is
    public static function getCompletedNotificationBody(Export $export): string
    {
        // Basisbericht met aantal succesvol geëxporteerde rijen
        $body = 'Your product export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        // Als er mislukte rijen zijn, voeg dat toe aan het bericht
        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body; // Toon het bericht
    }
}
