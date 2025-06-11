<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    // Dit bepaald de volgorde in de sidebar
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Order Information')->schema([
                        Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('payment_method')
                            ->options([
                                'bancontact' => 'Bancontact',
                                'cod' => 'Cash on Delivery',
                            ])
                            ->required(),

                        Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])
                            ->default('pending')
                            ->required(),

                        ToggleButtons::make('status')
                            ->options([
                                'new' => 'New',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->colors([
                                'new' => 'info',
                                'processing' => 'warning',
                                'shipped' => 'success',
                                'delivered' => 'success',
                                'cancelled' => 'warning',
                            ])
                            ->icons([
                                'new' => 'heroicon-m-sparkles',
                                'processing' => 'heroicon-m-arrow-path',
                                'shipped' => 'heroicon-m-truck',
                                'delivered' => 'heroicon-m-check-badge',
                                'cancelled' => 'heroicon-m-x-circle',
                            ])
                            ->inline()
                            ->default('new')
                            ->required(),

                        Select::make('currency')
                            ->options([
                                'EUR' => 'EUR',
                            ])
                            ->default('EUR')
                            ->required(),

                        Select::make('shipping_method')
                            ->options([
                                'truck' => 'Truck Delivery',
                                'pickup' => 'Pickup at store',
                            ]),

                        TextArea::make('notes')
                            ->columnSpanFull(),

                        TextInput::make('transaction_id')
                            ->label('Stripe Transaction-ID')
                            ->readOnly()
                            ->columnSpanFull(),

                    ])->columns(2),

                    Section::make('Order Items')->schema([
                        Repeater::make('items')
                        ->relationship()
                        ->schema([
                            Select::make('product_id')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->distinct() // Voorkomt dubbele keuzes (je mag niet 2x hetzelfde product kiezen)
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems() // Verberg opties die al gekozen zijn in andere rijen van de repeater
                            ->columnSpan(4)
                            ->reactive()
                            // Toont automatisch de prijs bij het selecteren van een product
                            ->afterStateUpdated(fn ($state, Set $set) => $set('unit_amount', Product::find($state)->price ?? 0))
                            ->afterStateUpdated(fn ($state, Set $set) => $set('total_amount', Product::find($state)->price ?? 0)),

                            TextInput::make('quantity')
                                ->numeric()
                                ->required()
                                ->default(1)
                                ->minValue(1)
                                ->columnSpan(2)
                                ->reactive()
                                // Function: neem de state van de quantity, vermenigvuldig met de unit_amount en sla dat op in total_amount
                                ->afterStateUpdated(fn ($state, Set $set, Get $get) => $set('total_amount', $state * $get('unit_amount'))),

                            TextInput::make('unit_amount')
                                ->numeric()
                                ->required()
                                ->disabled()
                                // doordat disabled dingen niet opslaat in de database moet je dehydrated() gebruiken, waarbij je de waarde van de kolom opslaat
                                ->dehydrated()
                                ->columnSpan(3),

                            TextInput::make('total_amount')
                                ->numeric()
                                ->required()
                                ->dehydrated()
                                ->columnSpan(3),


                        ])->columns(12),

                        Placeholder::make('grand_total_placeholder')
                            ->label('Grand Total')
                            ->content(function (Get $get, Set $set){
                                $total = 0;
                                // if repeaters geen items heeft geef je gewoon de totale prijs
                                if(!$repeaters = $get('items')){
                                    return $total;
                                }
                                // else bereken de totale prijs van alle items
                                foreach($repeaters as $key => $repeater){
                                    $total += $get("items.{$key}.total_amount");
                                }

                                // set de totale prijs in de hidden input
                                $set('grand_total', $total);

                                return Number::currency($total, 'EUR');
                            }),

                            // dit is een hidden input om de totale prijs op te slaan
                            Hidden::make('grand_total')
                                ->default(0),

                    ])

                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('grand_total')
                    ->numeric()
                    ->sortable()
                    ->money('EUR'),
                TextColumn::make('payment_method')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('payment_status')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('currency')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('shipping_method')
                    ->sortable()
                    ->searchable(),

                SelectColumn::make('status')
                    ->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('transaction_id')
                    ->label('Transaction-ID')
                    ->copyable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            /* SOFT DELETES */
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]))
            ->filters([
                TrashedFilter::make(), // Verberg standaard en toon met toggle
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

            AddressRelationManager::class
        ];
    }

    // geef een badge met het aantal orders in de sidebar
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    // geef de badge in de sidebar rode kleur bij meer dan 10 orders en naders groen
    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'danger' : 'success';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
