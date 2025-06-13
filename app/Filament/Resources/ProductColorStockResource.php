<?php

namespace App\Filament\Resources;

use App\Filament\Pages\ManageProductStock;
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

class ProductColorStockResource extends Resource
{
    protected static ?string $model = ProductColorStock::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Product Stock';
    protected static ?string $modelLabel = 'Product Stock';
    protected static ?string $pluralModelLabel = 'Product Stocks';


    // Dit bepaald de volgorde in de sidebar
    protected static ?int $navigationSort = 5;

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
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\ImageColumn::make('product.images')
                    ->label('Image')
                    ->alignCenter()
                    ->getStateUsing(fn ($record) => $record->product->images[0] ?? null)
                    ->size(40),

                Tables\Columns\TextColumn::make('color.name')
                    ->label('Kleur')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable()
                    ->searchable()
            ])
            ->defaultSort('product_id')
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
            //'create' => Pages\CreateProductColorStock::route('/create'),
            'edit' => Pages\EditProductColorStock::route('/{record}/edit'),
        ];
    }

    // PRODUCT STOCK BADGE
    public static function getNavigationBadge(): ?string
    {
        $outOfStock = ProductColorStock::where('stock', 0)->count();
        $lowStock = ProductColorStock::whereBetween('stock', [1, 9])->count();

        return ($outOfStock > 0 || $lowStock > 0)
            ? "{$outOfStock} out / {$lowStock} low"
            : null;
    }
    // PRODUCT STOCK BADGE KLEUR
    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'danger';
    }



}
