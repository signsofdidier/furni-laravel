<?php

namespace App\Filament\Resources;

use App\Filament\Exports\ProductExporter;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Color;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Product Information')->schema([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Set $set) {
                                if ($operation !== 'create') {
                                    return;
                                }
                                $set('slug', Str::slug($state));
                            })
                            ->maxLength(255),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated()
                            ->unique(Product::class, 'slug', ignoreRecord: true),
                    ])->columns(2),

                    Section::make('Description')->schema([
                        MarkdownEditor::make('description')
                            ->label('')
                            ->toolbarButtons([
                                'bold',          // Maakt tekst vetgedrukt
                                'italic',        // Maakt tekst cursief
                                'link',         // Voegt hyperlinks toe
                                'orderedList',   // Maakt genummerde lijsten
                                'bulletList',   // Maakt opsommingen met bullets
                                'blockquote',   // Formatteert als citaat
                                'codeBlock',    // Maakt codeblokken
                                'undo',         // Ongedaan maken functie
                                'redo',         // Opnieuw uitvoeren functie
                            ])
                            ->columnSpanFull(), // Neemt volledige breedte in

                        /* MARKDOWN MET AI INTEGRATIE */
                        Forms\Components\Actions::make([
                            Action::make('generateDescription')
                                ->label('Generate Description')
                                ->action(function (Forms\Set $set, Forms\Get $get) {
                                    $productName = $get('name');
                                    if (!$productName) {
                                        $set('description', 'Please enter a product name first');
                                        return;
                                    }

                                    try {
                                        // API call naar Groq voor AI beschrijving
                                        $response = Http::withoutVerifying()
                                            ->withToken(env('GROQ_API_KEY'))
                                            ->timeout(15)
                                            ->post('https://api.groq.com/openai/v1/chat/completions', [
                                                'model' => 'llama3-8b-8192',
                                                'messages' => [
                                                    [
                                                        'role' => 'system',
                                                        'content' => 'Generate product descriptions with: 1) 1-2 short descriptive sentences, ' .
                                                            '2) 2-3 bullet points with small key features (no more than 3 words). Use **bold** only for important ' .
                                                            'terms in the description. For bullets use *. Do not repeat the product name.'
                                                    ],
                                                    [
                                                        'role' => 'user',
                                                        'content' => "Create product description for: {$productName}"
                                                    ],
                                                ],
                                                'max_tokens' => 150,  // Iets meer tokens voor betere beschrijving
                                                'temperature' => 0.5  // Balans tussen creativiteit en consistentie
                                            ]);

                                        if ($response->successful()) {
                                            $content = $response->json();
                                            $description = $content['choices'][0]['message']['content'] ?? '';

                                            // Zuivert de gegenereerde tekst
                                            $cleanDescription = Str::of($description)
                                                ->replace(['```markdown', '```', '# ', 'Product description:', 'Description:'], '')
                                                ->replaceMatches('/(- |•|\*) /', '* ')
                                                ->trim();

                                            // Zorgt voor juiste opmaak
                                            $cleanDescription = (string) Str::of($cleanDescription)
                                                ->replaceMatches('/\n+/', "\n")  // Verwijdert dubbele newlines
                                                ->prepend("\n");                 // Voegt newline toe voor consistentie

                                            $set('description', trim($cleanDescription));
                                        }
                                    } catch (\Exception $e) {
                                        // Foutafhandeling
                                        $set('description', 'Error generating description. Please try again.');
                                    }
                                }),
                        ]),
                    ])->collapsible(),

                    Section::make('Product Images')->schema([
                        FileUpload::make('images')
                            ->label('')
                            ->multiple()
                            ->reorderable()
                            ->required()
                            ->directory('products')
                            ->image()
                            ->imageEditor()
                            ->imageCropAspectRatio('125:161')
                            ->imageResizeTargetWidth(1000)
                            ->imageResizeTargetHeight(1288)
                            ->optimize('webp')
                            ->maxSize(6048)
                            ->required(),
                    ]),

                    Section::make('Color & Stock')->schema([
                        Repeater::make('productColorStocks')
                            ->relationship('productColorStocks')
                            ->label('Color & Stock')
                            ->schema([
                                Select::make('color_id')
                                    ->label('Color')
                                    ->options(Color::all()->pluck('name', 'id'))
                                    ->required(),

                                TextInput::make('stock')
                                    ->label('Stock')
                                    ->numeric()
                                    ->required(),
                            ])
                            ->columns(1)
                            ->grid(3),
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
                            ->preload()
                            ->relationship('category', 'name'),

                        Select::make('brand_id')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('brand', 'name'),
                    ]),

                    Section::make('status')->schema([
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
            ->headerActions([
                ExportAction::make()
                    ->exporter(ProductExporter::class)
            ])
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                ImageColumn::make('images')
                    ->alignCenter()
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
                    ->boolean()
                    ->sortable(),
                IconColumn::make('on_sale')
                    ->boolean()
                    ->sortable(),
                IconColumn::make('in_stock')
                    ->label('In Stock')
                    ->boolean()
                    ->sortable()
                    ->getStateUsing(fn ($record) => $record->in_stock), // Kijkt als er stock is
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class, // Zorgt ervoor dat de trashed records worden weergegeven
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
                    ExportBulkAction::make()
                        ->exporter(ProductExporter::class)
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

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
