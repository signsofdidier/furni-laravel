<?php

namespace App\Filament\Resources\ReviewResource\Pages;

use App\Filament\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Contracts\View\View;

// Pagina om reviews te managen, maar met statistieken erbij (2 tabs)
class ManageReviewsWithStats extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    // resource + view
    protected static string $resource = ReviewResource::class;
    protected static string $view = 'filament.resources.review-resource.pages.manage-reviews-with-stats';

    /* Geen titel nodig, laat ik leeg */
    public function getTitle(): string
    {
        return '';
    }

    /* GEEN BREADCRUMB */
    protected static bool $shouldRegisterBreadcrumbs = false;

    // Actieve tab: 'reviews' of 'ratings'
    public string $activeTab = 'reviews';

    // COUNT VOOR NIET APPROVED REVIEWS
    public int $pendingReviewCount = 0;

    // Event listeners: refresh als een review approved is
    protected $listeners = ['refreshPendingCount' => '$refresh'];

    // Bij laden: records per page en meteen tellen hoeveel pending
    public function mount(): void{
        $this->tableRecordsPerPage = 5; // aantal records per pagina
        $this->pendingReviewCount = Review::where('approved', false)->count();
    }

    // Welke query gebruiken voor de tabel? Afhankelijk van de tab
    public function refreshPendingCount(): void
    {
        $this->pendingReviewCount = Review::where('approved', false)->count();
    }

    protected function getTableQuery()
    {
        if ($this->activeTab === 'reviews') {
            // Reviews tab: alles van reviews, ook soft deleted
            return Review::withTrashed()->with(['user', 'product'])/*->latest('created_at')*/;

        } else {
            // Ratings tab: producten ophalen, met rating stats
            return Product::query()->withCount('reviews')
                ->withAvg('reviews', 'rating');
        }
    }

    // Welke kolommen tonen in de TABLE? Verschillend per tab
    protected function getTableColumns(): array
    {
        return $this->activeTab === 'reviews'
            ? [
                TextColumn::make('user.name')->label('User')
                    ->sortable(),

                ImageColumn::make('product.images.0')
                    ->label('Image')
                    ->disk('public')
                    ->height(50),

                TextColumn::make('product.name')->label('Product'),

                TextColumn::make('rating')
                    ->formatStateUsing(fn ($state) => number_format($state) . ' ★')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Title')
                    ->limit(10),// max 10 tekens

                TextColumn::make('created_at')->label('Date')->dateTime()->sortable(),

                // APPROVE KNOP
                ToggleColumn::make('approved')
                    ->label('Approved')
                    ->sortable()
                    ->toggleable(true)
                    ->afterStateUpdated(function () {
                        $this->refreshPendingCount(); // refresh na aanpassing toggle
                    }),
            ] : [
                ImageColumn::make('images.0')->label('Image')->disk('public')
                    ->sortable(),

                TextColumn::make('name')->label('Product')
                    ->sortable(),

                TextColumn::make('reviews_avg_rating')
                    ->label('AVG Rating')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state, 1) . ' ★'),

                TextColumn::make('reviews_count')->label('Total Reviews')->sortable(),
            ];

    }

    // Wisselen tussen tabs
    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetTable();
    }

    // Filters voor reviews tab (approved / pending / deleted)
    protected function getTableFilters(): array
    {
        if ($this->activeTab === 'reviews') {
            return [
                Filter::make('approved')
                    ->label('Approved')
                    ->query(fn ($query) => $query->where('approved', true)),

                Filter::make('pending')
                    ->label('Not Approved')
                    ->query(fn ($query) => $query->where('approved', false)),

                Tables\Filters\TrashedFilter::make()
                    ->label('Deleted Reviews')
                    ->trueLabel('All reviews') // NIET VERWIJDERDE REVIEWS
                    ->falseLabel('Only deleted') // VERWIJDERDE REVIEWS
                    ->default(true), // ALLE REVIEWS
            ];
        }

        return [];
    }

    // Acties per rij in de tabel (alleen voor reviews tab)
    protected function getTableActions(): array
    {
        // Alleen soft delete acties voor reviews tab
        if ($this->activeTab === 'reviews') {
            return [
                // MODAL VOOR REVIEW TE LEZEN
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Review details')
                    ->modalSubheading(fn ($record) => 'By ' . $record->user->name) // naam gebruiker
                    ->modalContent(fn ($record) => view('filament.resources.review-resource.partials.review-modal', [
                        'review' => $record,
                    ]))
                    ->modalSubmitAction(false) // Verwijder standaard 'Submit'
                    ->modalCancelAction(false),// Verwijder standaard 'Cancel'


                Tables\Actions\DeleteAction::make(), // Soft delete
                Tables\Actions\ForceDeleteAction::make(), // Permanent delete
                Tables\Actions\RestoreAction::make(), // Herstellen
            ];
        }

        // Voor ratings tab andere acties of geen acties
        return [];
    }

    /* HOEVEEL PER PAGE VOOR DE PAGINATION */
    public function getTableRecordsPerPageSelectOptions(): array
    {
        return [5, 10, 25, 50];
    }

    /* Welke kolom sorteren bij het openen */
    protected function getDefaultTableSortColumn(): ?string
    {
        return $this->activeTab === 'reviews' ? 'approved' : 'reviews_avg_rating';
    }
    protected function getDefaultTableSortDirection(): ?string
    {
        return $this->activeTab === 'reviews' ? 'asc' : 'desc';
    }
}
