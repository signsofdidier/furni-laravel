<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductRatingResource\Pages;
use App\Filament\Resources\ProductRatingResource\RelationManagers;
use App\Models\ProductRating;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductRatingResource extends Resource
{
    protected static ?string $model = ProductRating::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Afbeelding van het product (eerste image)
                ImageColumn::make('product.images.0')
                    ->label('Image')
                    ->disk('public')
                    ->height(50),

                // Productnaam
                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),

                // Gemiddelde score via product relatie
                TextColumn::make('average_rating')
                    ->label('AVG Rating')
                    ->html()
                    ->sortable()
                    // tonen van de sterren
                    ->formatStateUsing(function ($state) {
                        $filled = floor($state);
                        $empty = 5 - $filled;
                        $starStyle = 'font-size: 1.25rem; line-height: 1;';
                        return str_repeat('<span style="color:#FFAE00; ' . $starStyle . '">★</span>', $filled)
                            . str_repeat('<span style="color:#B2B2B2; ' . $starStyle . '">☆</span>', $empty)
                            . ' <span style="color:#555; font-size: 0.9rem;">(' . $state . ')</span>';
                    })
                    ->getStateUsing(fn($record) => number_format($record->product?->averageRating())),

                // Aantal ratings via relatie
                TextColumn::make('ratings_count')
                    ->label('Rated')
                    ->getStateUsing(fn($record) => $record->product?->ratings()->count()),
            ])
            ->defaultSort('average_rating', 'desc') // standaard sorteren op gemiddelde score
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
            'index' => Pages\ListProductRatings::route('/'),
            'create' => Pages\CreateProductRating::route('/create'),
            'edit' => Pages\EditProductRating::route('/{record}/edit'),
        ];
    }

    // niemand moet dit aanmaken
    public static function canCreate(): bool
    {
        return false;
    }

    // niemand moet dit bewerken
    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->select('product_ratings.*')
            ->join('products', 'products.id', '=', 'product_ratings.product_id')
            ->selectRaw('(SELECT ROUND(AVG(r.rating)) FROM product_ratings r WHERE r.product_id = products.id) AS average_rating');
    }

}
