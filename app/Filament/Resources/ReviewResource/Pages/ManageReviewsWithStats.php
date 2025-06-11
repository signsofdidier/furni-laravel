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

    public string $activeTab = 'reviews';

    // COUNT VOOR NIET APPROVED REVIEWS
    public int $pendingReviewCount = 0;

    // refresh pending count na goedkeuring
    protected $listeners = ['refreshPendingCount' => '$refresh'];

    public function mount(): void{
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
            return Review::withTrashed()->with(['user', 'product'])->latest('created_at');

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
                TextColumn::make('user.name')->label('User'),
                ImageColumn::make('product.images.0')
                    ->label('Image')
                    ->disk('public')
                    ->height(50),

                TextColumn::make('product.name')->label('Product'),
                TextColumn::make('rating'),
                TextColumn::make('title'),
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
                ImageColumn::make('images.0')->label('Image')->disk('public'),
                TextColumn::make('name')->label('Product'),
                TextColumn::make('reviews_avg_rating')
                    ->label('AVG Rating')
                    ->formatStateUsing(fn ($state) => number_format($state, 1) . ' ★'),
                TextColumn::make('reviews_count')->label('Total Reviews'),
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
                Tables\Filters\TrashedFilter::make()
                    ->label('Deleted Reviews')
                    ->trueLabel('All reviews')      // This shows only NON-deleted records
                    ->falseLabel('Only deleted')    // This shows only deleted records
                    ->default(true),                // This shows all records (active + deleted)
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
}
