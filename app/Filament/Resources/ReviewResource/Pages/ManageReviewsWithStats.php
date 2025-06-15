<?php

namespace App\Filament\Resources\ReviewResource\Pages;

use App\Filament\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Contracts\View\View;

class ManageReviewsWithStats extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = ReviewResource::class;
    protected static string $view = 'filament.resources.review-resource.pages.manage-reviews-with-stats';

    /*GEEN TITEL */
    public function getTitle(): string
    {
        return '';
    }

    /* GEEN BREADCRUMB */
    protected static bool $shouldRegisterBreadcrumbs = false;

    public string $activeTab = 'reviews';

    // COUNT VOOR NIET APPROVED REVIEWS
    public int $pendingReviewCount = 0;

    // refresh pending count na goedkeuring
    protected $listeners = ['refreshPendingCount' => '$refresh'];

    public function mount(): void{
        $this->tableRecordsPerPage = 5; // aantal records per pagina
        $this->pendingReviewCount = Review::where('approved', false)->count();
    }

    // refresh pending count na aanpassing toggle
    public function refreshPendingCount(): void
    {
        $this->pendingReviewCount = Review::where('approved', false)->count();
    }

    protected function getTableQuery()
    {
        if ($this->activeTab === 'reviews') {
            // Voor reviews: gebruik Review model met SoftDeletes
            return Review::withTrashed()->with(['user', 'product'])/*->latest('created_at')*/;

        } else {
            // Voor ratings: gebruik Product model (geen SoftDeletes)
            return Product::query()->withCount('reviews')
                ->withAvg('reviews', 'rating');
        }
    }

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
                    ->sortable(),

                TextColumn::make('title')
                    ->label('Title')
                    ->limit(20),// max 20 tekens

                TextColumn::make('created_at')->label('Date')->dateTime()->sortable(),

                ToggleColumn::make('approved')
                    ->label('Approved')
                    ->sortable()
                    ->toggleable(true)
                    ->afterStateUpdated(function () {
                        $this->refreshPendingCount(); // refresh na aanpassing toggle
                    }),
            ]
            : [
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

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetTable();
    }

    protected function getTableFilters(): array
    {
        if ($this->activeTab === 'reviews') {
            return [
                Tables\Filters\Filter::make('approved')
                    ->label('Approved')
                    ->query(fn ($query) => $query->where('approved', true)),

                Tables\Filters\Filter::make('pending')
                    ->label('Not Approved')
                    ->query(fn ($query) => $query->where('approved', false)),

                Tables\Filters\TrashedFilter::make()
                    ->label('Deleted Reviews')
                    ->trueLabel('All reviews')      // NIET VERWIJDERDE REVIEWS
                    ->falseLabel('Only deleted')    // VERWIJDERDE REVIEWS
                    ->default(true),                // ALLE REVIEWS
            ];
        }

        return [];
    }


    protected function getTableActions(): array
    {
        // Alleen soft delete acties voor reviews tab
        if ($this->activeTab === 'reviews') {
            return [
                //Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),       // Soft delete
                Tables\Actions\ForceDeleteAction::make(),  // Permanent delete
                Tables\Actions\RestoreAction::make(),      // Herstellen
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

    /* SORTERING BIJ START VAN PAGINA */
    protected function getDefaultTableSortColumn(): ?string
    {
        return $this->activeTab === 'reviews' ? 'approved' : 'reviews_avg_rating';
    }
    protected function getDefaultTableSortDirection(): ?string
    {
        return $this->activeTab === 'reviews' ? 'asc' : 'desc';
    }


}
