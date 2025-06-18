<?php

namespace App\Providers\Filament;

use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use App\Filament\Widgets\LatestOrders;
use App\Filament\Widgets\OrdersPerDayChart;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TestWidget;
use App\Filament\Widgets\TotalRevenueStats;
use App\Http\Middleware\EnsureUserHasRole;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use App\Filament\Widgets\DashboardStats;
use App\Filament\Widgets\PendingReviews;
use App\Filament\Widgets\ProductStockStats;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandLogo(asset('assets/img/furni-black-3.png'))
            ->favicon(asset('assets/img/favicon.png'))
            ->login()
            ->colors([
                /*'primary' => Color::Amber,*/
                'primary' => Color::hex('#F76B6A'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            // zoekt automatisch naar widgets in andere mappen
            //->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                TotalRevenueStats::class,
                DashboardStats::class,
                LatestOrders::class,
                OrdersPerDayChart::class,

            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ;
    }

}
