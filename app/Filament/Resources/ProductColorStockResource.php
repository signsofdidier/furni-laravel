<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductColorStockResource\Pages;
use App\Filament\Resources\ProductColorStockResource\RelationManagers;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColorStock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductColorStockResource extends Resource
{
    protected static ?string $model = ProductColorStock::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Product Stock';
    protected static ?string $modelLabel = 'Product Stock';
    protected static ?string $pluralModelLabel = 'Product Stocks';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->options(Product::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('color_id')
                    ->label('Color')
                    ->options(Color::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('stock')
                    ->label('Stock Quantity')
                    ->numeric()
                    ->minValue(0)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->label('Product'),
                Tables\Columns\TextColumn::make('color.name')->label('Color'),
                Tables\Columns\TextColumn::make('stock')->label('Stock'),
                Tables\Columns\TextColumn::make('updated_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'create' => Pages\CreateProductColorStock::route('/create'),
            'edit' => Pages\EditProductColorStock::route('/{record}/edit'),
        ];
    }

    // PRODUCT STOCK BADGE
    public static function getNavigationBadge(): ?string
    {
        // Producten opvragen
        $outOfStock = Product::with('productColorStocks')
            ->get() // Producten opvragen
            // Producten waarbij de som van stock op 0 staat
            ->filter(fn ($product) => $product->productColorStocks->sum('stock') <= 0)
            ->count(); // Totaal aantal producten

        $lowStock = Product::with('productColorStocks')
            ->get() // Producten opvragen
             // Producten waarbij de som van stock lager dan 10 is
            ->filter(fn ($product) => $product->productColorStocks->sum('stock') < 10 && $product->productColorStocks->sum('stock') > 0)
            ->count(); // Totaal aantal producten

        return ($outOfStock > 0 || $lowStock > 0) ? "{$outOfStock} out / {$lowStock} low " : null;

    }
    // PRODUCT STOCK BADGE KLEUR
    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'danger';
    }



}
