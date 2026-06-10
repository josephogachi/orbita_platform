<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeAdminWidget extends Widget
{
    // High priority sorting to stay at the top
    protected static ?int $sort = -10;

    // Full width across the dashboard
    protected int | string | array $columnSpan = 'full';

    // Links to the visual file we will create in Step 2
    protected static string $view = 'filament.widgets.welcome-admin-widget';
}