<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Group;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?int $navigationSort = 9;

    // Resource voor blogs in admin
    public static function form(Form $form): Form
    {
        ini_set('memory_limit', '512M'); // instelling voor de image editor en compressie die meer geheugen nodig heeft

        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Blog')->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->required()
                                ->live(onBlur: true)
                                // Automatisch slug maken bij create (maar niet bij edit)
                                ->afterStateUpdated(function (string $operation, $state, Set $set) {
                                    // Stop als we een bestaand record aan het bewerken zijn — alleen doorgaan bij 'create'
                                    if ($operation !== 'create') {
                                        return;
                                    }
                                    // slug genereren
                                    $set('slug', Str::slug($state));
                                })
                                ->maxLength(255),

                            TextInput::make('slug')
                                ->required()
                                ->maxLength(255)
                                ->disabled() // niet manueel aanpasbaar
                                ->dehydrated() // zorgt dat disabled velden ook worden opgeslagen in de database
                                // Slug moet uniek zijn (ook bij edit)
                                ->unique('blogs', 'slug', ignoreRecord: true),
                            // ignoreRecord: true = controleer uniekheid, maar negeer het huidige record als je aan het bewerken bent”.
                        ]),

                        TextArea::make('excerpt')
                            ->label('Excerpt')
                            ->maxLength(255)
                            ->required(),

                        /*MarkdownEditor::make('content')
                            ->required(),*/

                        MarkdownEditor::make('content')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'link',
                                'orderedList',
                                'bulletList',
                                'blockquote',
                                'codeBlock',
                                'undo',
                                'redo',
                            ])
                            ->columnSpanFull(),

                        /* AI INTEGRATIE KNOP VOOR DE CONTENT,
                        EXCERPT EN BLOCKQUOTE OP BASIS VAN TITEL EN GEKOZEN CATEGORIES */
                        Forms\Components\Actions::make([
                            // Maakt een actie-knop aan voor het genereren van blog content
                            Action::make('generateBlogContent')
                                ->label('Generate Blog Content')  // Label dat op de knop verschijnt
                                ->action(function (Forms\Set $set, Forms\Get $get) {
                                    // Haal de ingevoerde titel en categorieën op uit het formulier
                                    $title = $get('title');
                                    $categories = $get('categories');

                                    // Controleer of er een titel is ingevuld
                                    if (!$title) {
                                        $set('content', 'Please enter a blog title first'); // Toon foutmelding
                                        return; // Stop de functie als er geen titel is
                                    }

                                    try {
                                        $categoryInstruction = '';
                                        // Als er categorieën geselecteerd zijn, voeg deze toe aan de instructies
                                        if (!empty($categories)) {
                                            // Haal de namen van de geselecteerde categorieën op
                                            $categoryNames = \App\Models\Category::whereIn('id', $categories)
                                                ->pluck('name')
                                                ->implode(', ');
                                            $categoryInstruction = " Focus specifically on: {$categoryNames}.";
                                        }

                                        // Verstuur een API-aanvraag naar Groq voor AI-contentgeneratie
                                        $response = Http::withoutVerifying()
                                            ->withToken(env('GROQ_API_KEY')) // Gebruik API key uit .env
                                            ->timeout(60) // Timeout na 60 seconden
                                            ->post('https://api.groq.com/openai/v1/chat/completions', [
                                                'model' => 'llama3-8b-8192', // AI model dat gebruikt wordt
                                                'messages' => [
                                                    [
                                                        'role' => 'system', // Systeeminstructies voor de AI
                                                        'content' => 'You are an interior design expert. Generate:'.
                                                            '1. Blog content (2-3 paragraphs) about the given topic.'.
                                                            '2. A UNIQUE, specific quote (max 12 words) that directly relates to the content.'.
                                                            '3. An appropriate designer/author who would say this quote.'.
                                                            'RULES:'.
                                                            '- NEVER use these overused quotes: "Less is more", "Form follows function", "God is in the details"'.
                                                            '- ALWAYS create a fresh, context-specific quote'.
                                                            '- Choose DIFFERENT designers each time (e.g., Philippe Starck, Kelly Wearstler, Patricia Urquiola)'.
                                                            '- The quote must contain a SPECIFIC insight about the topic'.
                                                            '- Format as JSON: {"content":"text","quote":"quote","author":"name"}'.
                                                            $categoryInstruction // Categorie-specifieke instructies
                                                    ],
                                                    [
                                                        'role' => 'user', // Gebruikersvraag aan de AI
                                                        'content' => "Topic: {$title}. ".
                                                            "Generate content with 2-3 clear paragraphs. ". // 2-3 alinea's
                                                            "Include a fresh quote (not overused) and matching designer. ". // Unieke quote
                                                            "Style context: ".
                                                            (!empty($categories) ? $categoryNames : "general interior design") // Stijlcontext
                                                    ]
                                                ],
                                                'response_format' => ['type' => 'json_object'], // Verwacht JSON antwoord
                                                'max_tokens' => 600, // Maximale lengte van het antwoord
                                                'temperature' => 0.65 // Creativiteitsniveau (0-1)
                                            ]);

                                        // Als het API-verzoek succesvol was
                                        if ($response->successful()) {
                                            $content = $response->json(); // Zet JSON om naar array
                                            $responseData = json_decode($content['choices'][0]['message']['content'] ?? '{}', true);

                                            // Verwerk de gegenereerde content
                                            $blogContent = $responseData['content'] ?? '';
                                            // Verwijder eventuele markdown headers en trim whitespace
                                            $cleanContent = Str::of($blogContent)
                                                ->replaceMatches('/^#+.+/m', '') // Verwijder headers zoals # Titel
                                                ->trim();

                                            // Verwerk de quote - verwijder overbodige aanhalingstekens
                                            $quote = Str::of($responseData['quote'] ?? '')
                                                ->trim('"\'')
                                                ->toString();

                                            // Gebruik de meegeleverde auteur of een standaardwaarde
                                            $author = $responseData['author'] ?? 'Design Expert';

                                            // Vul de formuliervelden in met de gegenereerde content
                                            $set('content', (string) $cleanContent); // Hoofdcontent
                                            $set('excerpt', Str::of($blogContent)->limit(150)); // Korte samenvatting
                                            $set('blockquote', $quote); // Inspiratiequote
                                            $set('blockquote_author', $author); // Auteur van de quote
                                        }
                                    } catch (\Exception $e) {
                                        // Vang errors op en toon een foutmelding
                                        $set('content', 'Error generating content: ' . $e->getMessage());
                                    }
                                }),
                        ]),

                        // Zet de user automatisch op ingelogde user
                        // Als je een nieuwe blog aanmaakt, wordt automatisch het user_id veld (dus: wie is de auteur?) gevuld met de huidige user (degene die nu is ingelogd).
                        Hidden::make('user_id')
                            ->default(auth()->id()),

                        Select::make('categories')
                            ->label('Categories (max 2)')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required()
                            ->rules(['array', 'max:2']) // max 2 categories

                    ])->columns(1),

                ])->columnSpan(2),

                /* Rechter groep */
                Group::make()->schema([

                    /* Image section */
                    Section::make('Image')->schema([
                        FileUpload::make('image')
                            ->directory('blogs')
                            ->required()
                            ->image()
                            ->imageEditor() //editor voor afbeeldingen
                            ->imageCropAspectRatio('200:167')
                            ->imageResizeTargetWidth(1400)
                            ->imageResizeTargetHeight(1169)
                            ->optimize('webp')
                            ->resize(50)
                            ->maxSize(2048),
                    ]),

                    /* Blockquote section */
                    Section::make('Blockquote')->schema([
                        TextArea::make('blockquote')
                            ->maxLength(255)
                            ->nullable(),

                        TextInput::make('blockquote_author')
                            ->maxLength(50)
                            ->nullable(),
                    ]),
                ])->columnSpan(1)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Author')
                    ->sortable(),

                ImageColumn::make('image')
                    ->alignCenter()
                    ->size(60),

                TextColumn::make('title'),

                TextColumn::make('slug')->toggleable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('categories.name')
                    ->label('Categories')
                    ->getStateUsing(fn ($record) => $record->categories->pluck('name')->join(', ')) // Join de namen van de categories met komma's
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created at')
                    ->dateTime()
                    ->sortable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated at')
                    ->since() // bv "3 minuten geleden"
                    ->toggleable()
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->modifyQueryUsing(function (Builder $query) {

                // SOFT DELETES altijd tonen (ook verwijderde blogs zichtbaar)
                $query->withoutGlobalScopes([SoftDeletingScope::class,]);

                // Als je een blog author bent: toon enkel je eigen blogs
                if (auth()->user()->hasRole('blog_author')) {
                    $query->where('user_id', auth()->id());
                }

                return $query;
            })
            ->filters([
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }


    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery(); // dit toont alle blogs

        // ENKEL EIGEN BLOGS ZIEN
        if (auth()->user()?->hasRole('blog_author')) {
            return $query->where('user_id', auth()->id());
        }

        return $query;
    }
}
