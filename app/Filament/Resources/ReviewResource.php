<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('rating')->numeric()->minValue(1)->maxValue(5)->required(),
                Forms\Components\TextInput::make('title'),
                Forms\Components\Textarea::make('body'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                TextColumn::make('product.name')->label('Product')->sortable()->searchable(),
                TextColumn::make('rating')->label('Rating'),
                TextColumn::make('title')->label('Title'),
                TextColumn::make('body')->label('Review')->limit(50),
                TextColumn::make('created_at')->label('Submitted')->dateTime(),
                ToggleColumn::make('approved')->label('Approved')->sortable()->toggleable(true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(), // Soft delete
                ForceDeleteAction::make(), // Hard delete
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
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
            'index' => Pages\ManageReviewsWithStats::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }

    // DE TITEL VAN DE RESOURCE
    public static function getLabel(): string
    {
        return 'Reviews & Ratings';
    }

    // DE COUNT BADGE IN DE SIDEBAR
    public static function getNavigationBadge(): ?string
    {
        $count = Review::where('approved', false)->count();
        return $count > 0 ? (string) $count : null;
    }

    // DE COUNT BADGE IN DE SIDEBAR KLEUR
    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'danger'; // rood
    }

    public static function canCreate(): bool
    {
        return false; // alleen lezen of beheer
    }

    // SOFT DELETES
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }

}
