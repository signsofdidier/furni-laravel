<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    // Dit bepaald de volgorde in de sidebar
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Product Information')->schema([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            // Maak automatisch de slug aan bij het createn maar verander niet bij bewerken
                            ->afterStateUpdated(function (string $operation, $state, Set $set) {
                                // Stop als we een bestaand record aan het bewerken zijn — alleen doorgaan bij 'create'
                                if ($operation !== 'create') {
                                    return;
                                }
                                // Zet de slug op een gesluggede versie van de ingevoerde naam
                                $set('slug', Str::slug($state));
                            })
                            ->maxLength(255),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated()
                            // Zorg dat de slug uniek is in de 'products' tabel, maar negeer het huidige record bij het bewerken
                            ->unique(Product::class, 'slug', ignoreRecord: true),

                        Select::make('colors')
                            ->multiple()
                            ->relationship('colors', 'name')
                            ->label('Available Colors')
                            ->preload() // laadt alle kleuren in één keer
                            ->searchable(), // doorzoekbaar indien veel kleuren

                        MarkdownEditor::make('description')
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('products')

                    ])->columns(2),

                    Section::make('Images')->schema([
                        FileUpload::make('images')
                            ->multiple()
                            ->directory('products')
                            ->maxFiles(5)
                            ->reorderable()
                            ->imageEditor() //editor voor afbeeldingen
                    ])

                ])->columnSpan(2),

                Group::make()->schema([
                    Section::make('Price')->schema([
                        TextInput::make('price')
                        ->numeric()
                        ->required()
                        ->prefix('EUR'),

                        TextInput::make('shipping_cost')
                            ->label('Shipping cost / unit')
                            ->numeric()
                            ->required()
                            ->prefix('EUR'),

                    ]),

                    Section::make('Associations')->schema([
                        Select::make('category_id')
                        ->required()
                        ->searchable()
                        ->preload() // preload alle categoriën voor sneller laden
                        ->relationship('category', 'name'), // relatie met de gelinkte category model

                        Select::make('brand_id')
                            ->required()
                            ->searchable()
                            ->preload() // preload alle categoriën voor sneller laden
                            ->relationship('brand', 'name'), // relatie met de gelinkte category model
                    ]),

                    Section::make('status')->schema([
                        Toggle::make('in_stock')
                            ->required()
                            ->default(true),

                        Toggle::make('is_active')
                            ->required()
                            ->default(true),

                        Toggle::make('is_featured')
                            ->required(),


                        Toggle::make('on_sale')
                            ->required(),
                    ])

                ])->columnSpan(1)

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                ImageColumn::make('images')
                    ->alignCenter()
                    // Zet de eerste afbeelding als preview
                    ->getStateUsing(fn ($record) => $record->images[0] ?? null),
                TextColumn::make('category.name')
                    ->sortable(),
                TextColumn::make('brand.name')
                    ->sortable(),
                TextColumn::make('price')
                    ->money('EUR')
                    ->sortable(),
                TextColumn::make('shipping_cost')
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_featured')
                ->boolean(),
                IconColumn::make('on_sale')
                ->boolean(),
                IconColumn::make('in_stock')
                ->boolean(),
                IconColumn::make('is_active')
                ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Verberg standaard en toon met toggle
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Verberg standaard en toon met toggle

            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]))
            ->filters([
                SelectFilter::make('categories')
                    ->relationship('category', 'name'),
                SelectFilter::make('brands')
                    ->relationship('brand', 'name'),
                TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // GLOBAL SEARCH Multiple columns
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'description'];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
