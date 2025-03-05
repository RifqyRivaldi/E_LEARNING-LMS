<?php

namespace App\Providers\Filament;

use App\Filament\Resources\QuizResource;
use App\Filament\Resources\QuizResource\Pages\Practice;
use App\Filament\Resources\QuizResource\Pages\Tryout;
use App\Filament\Resources\QuizResource\Widgets\StatsOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
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

class UserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('user')
            ->path('user')
            ->login()
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('4em')
            ->darkMode(false)
            ->databaseNotifications()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                QuizResource\Widgets\StatsOverview::class,
            ])->navigationItems([
                NavigationItem::make('Latihan Soal')
                    ->url(fn() => Practice::getUrl())
                    ->icon('heroicon-o-book-open')
                    ->isActiveWhen(fn () => request()->is('user/quizzes/practice*')) // Menjadikan aktif saat di /admin/quizzes/practice-test
                    ->group('Manajemen Tes')->sort(0),
                
                NavigationItem::make('Tryout')
                    ->url(fn() => Tryout::getUrl())
                    ->icon('heroicon-o-academic-cap')
                    ->isActiveWhen(fn () => request()->is('user/quizzes/tryout*')) // Menjadikan aktif saat di /admin/quizzes/practice-test
                    ->group('Manajemen Tes')->sort(0),

                // NavigationItem::make('Penjualan')
                //     ->icon('heroicon-o-banknotes')
                //     ->isActiveWhen(fn () => request()->is('admin/sales*'))
                //     ->group('Manajemen Penjualan')->sort(2),    

                // NavigationItem::make('Feedback')
                //     ->icon('heroicon-o-chat-bubble-left')
                //     ->isActiveWhen(fn () => request()->is('admin/feedback*'))
                //     ->group('Manajemen Feedback')->sort(3),

                // NavigationItem::make('Feedback Soal')
                //     ->icon('heroicon-o-document-chart-bar')
                //     ->isActiveWhen(fn () => request()->is('admin/feedback-question*'))
                //     ->group('Manajemen Feedback')->sort(3),

                // NavigationItem::make('Pengguna')
                //     ->icon('heroicon-o-users')
                //     ->isActiveWhen(fn () => request()->is('admin/users*'))
                //     ->group('Manajemen Pengguna')->sort(4),

                NavigationItem::make('Pengaturan')
                    ->icon('heroicon-o-cog')
                    ->isActiveWhen(fn () => request()->is('admin/settings*'))
                    ->group('Pengaturan')->sort(5),
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
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}