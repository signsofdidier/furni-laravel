<?php

namespace App\Filament\Resources;

use App\Filament\Exports\ProductColorStockExporter;
use App\Filament\Resources\ProductColorStockResource\Pages;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColorStock;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class ProductColorStockResource extends Resource
{
    protected static ?string $model = ProductColorStock::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Product Stock'; // Dit bepaald de label in de sidebar
    protected static ?string $modelLabel = 'Product Stock';
    protected static ?string $pluralModelLabel = 'Product Stocks';


    // Dit bepaald de volgorde in de sidebar
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->label('Product')
                    ->options(Product::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('color_id')
                    ->label('Color')
                    ->options(Color::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                TextInput::make('stock')
                    ->label('Stock Quantity')
                    ->numeric()
                    ->minValue(0)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ExportAction::make()
                    ->exporter(ProductColorStockExporter::class)
            ])
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product')
                    ->sortable()
                    ->searchable(),

                ImageColumn::make('product.images')
                    ->label('Image')
                    ->alignCenter()
                    ->getStateUsing(fn ($record) => $record->product->images[0] ?? null)
                    ->size(40),

                TextColumn::make('color.name')
                    ->label('Kleur')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable()
                    ->searchable()
            ])
            ->defaultSort('product_id')
            ->filters([
                Filter::make('out_of_stock')
                    ->label('Out of Stock')
                    ->query(fn ($query) => $query->where('stock', 0)),

                Filter::make('low_stock')
                    ->label('Low Stock (<10)')
                    ->query(fn ($query) => $query->whereBetween('stock', [1, 9])),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                /*Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),*/
                ExportBulkAction::make()
                    ->exporter(ProductColorStockExporter::class)
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductColorStocks::route('/'),
            //'create' => Pages\CreateProductColorStock::route('/create'),
            'edit' => Pages\EditProductColorStock::route('/{record}/edit'),
        ];
    }

    // PRODUCT STOCK BADGE
    public static function getNavigationBadge(): ?string
    {
        $outOfStock = ProductColorStock::where('stock', 0)->count();
        $lowStock = ProductColorStock::whereBetween('stock', [1, 9])->count();

        return ($outOfStock > 0 || $lowStock > 0) ? "{$outOfStock} out / {$lowStock} low" : null;
    }
    // PRODUCT STOCK BADGE KLEUR
    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'danger';
    }



}
