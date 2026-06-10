<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
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
            ->login()
            ->favicon(asset('favicon.png'))
            ->passwordReset() 
            ->profile()        
            
            ->brandName('Orbita Kenya')
            ->brandLogoHeight('2.5rem')
            
            ->colors([
                'primary' => Color::hex('#C5A059'), // Professional Gold/Brass for Orbita
                'gray'    => Color::Slate,
                'info'    => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'danger'  => Color::Rose,
            ])
            
            ->font('Plus Jakarta Sans')
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(MaxWidth::Full)

            // 🔔 NOTIFICATIONS SETUP
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s') // Auto-check for new leads every 30s
            
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // The LeadStatsOverview widget will appear here automatically 
                // once created in the Widgets folder.
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