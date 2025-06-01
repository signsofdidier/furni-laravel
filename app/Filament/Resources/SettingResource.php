<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Tables;

class SettingResource extends Resource
{
    // Koppel dit Filament‐Resource aan je Eloquent‐model
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    // Groep in de navigatie (dit staat links in het adminpaneel)
    protected static ?string $navigationGroup = 'Settings';

    // Label voor de lijstweergave
    public static function getLabel(): string
    {
        return 'Setting';
    }

    public static function getPluralLabel(): string
    {
        return 'Settings';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('free_shipping_threshold')
                    ->label('Free Shipping Threshold (€)')
                    ->numeric()                              // alleen cijfers/decimalen
                    ->required()                             // maak verplicht
                    ->helperText('Enter the minimum amount from which shipping is free.'),

                Checkbox::make('free_shipping_enabled')
                    ->label('Enable Free Shipping')
                    ->helperText('Toggle to activate or deactivate free shipping.'),
            ]);
    }

    /**
     * Bouw hier de kolommen voor de lijstweergave (List View).
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextInputColumn::make('free_shipping_threshold')
                    ->label('Free shipping from (€)')
                    ->rules(['required', 'numeric'])
                    ->sortable(),

                ToggleColumn::make('free_shipping_enabled')
                    ->label('Enable Free Shipping'),

                TextColumn::make('updated_at')
                    ->label('Last updated')
                    ->dateTime('d-m-Y H:i'),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),   // knop “Edit” in de rij
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSettings::route('/'),
            // 'create' => Pages\CreateSetting::route('/create'),
            'edit'   => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
