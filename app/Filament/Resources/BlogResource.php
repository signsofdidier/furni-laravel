<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use App\Models\Product;
use Filament\Forms;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Blog')->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
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
                                ->unique('blogs', 'slug', ignoreRecord: true),
                        ]),

                        TextArea::make('excerpt')
                            ->label('Excerpt')
                            ->maxLength(255)
                            ->required(),

                        MarkdownEditor::make('content')
                            ->required(),

                        Hidden::make('user_id')
                            ->default(auth()->id()),

                        Select::make('categories')
                            ->label('Categories (max 2)')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
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
                            ->imageEditor(), //editor voor afbeeldingen

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
                TextColumn::make('title'),

                TextColumn::make('slug')->toggleable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('categories.name')
                    ->label('Categories')
                    ->getStateUsing(fn ($record) => $record->categories->pluck('name')->join(', '))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated at')
                    ->since() // optioneel: bijv. "3 minuten geleden"
                    ->toggleable(),

                TextColumn::make('user.name')
                    ->label('Author'),
            ])
            ->defaultSort('updated_at', 'desc')
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
