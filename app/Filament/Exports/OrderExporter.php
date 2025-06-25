<?php

namespace App\Filament\Exports;

use App\Models\Order;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class OrderExporter extends Exporter
{
    // Werkt met het orders model
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('user_id'),
            ExportColumn::make('address_id'),
            ExportColumn::make('transaction_id'),
            ExportColumn::make('grand_total'),
            ExportColumn::make('payment_method'),
            ExportColumn::make('payment_status'),
            ExportColumn::make('status'),
            ExportColumn::make('currency'),
            ExportColumn::make('shipping_amount'),
            ExportColumn::make('shipping_method'),
            ExportColumn::make('notes'),
            ExportColumn::make('sub_total'),
            ExportColumn::make('tax_amount'),
            ExportColumn::make('deleted_at'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    // Bericht dat je krijgt als de export klaar is
    public static function getCompletedNotificationBody(Export $export): string
    {
        // Basisbericht met aantal succesvol geëxporteerde rijen
        $body = 'Your order export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        // Als er mislukte rijen zijn, voeg dat toe aan het bericht
        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body; // Toon het bericht
    }
}
