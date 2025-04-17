<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Client;
use App\Models\UserSession;
use App\Models\ActivityLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SsoStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),
                
            Stat::make('Services', Client::count())
                ->description('Connected services')
                ->descriptionIcon('heroicon-o-key')
                ->color('success'),
                
            Stat::make('Active Sessions', UserSession::where('expires_at', '>', now())->count())
                ->description('Current active sessions')
                ->descriptionIcon('heroicon-o-identification')
                ->color('warning'),
                
            Stat::make('Today\'s Logins', ActivityLog::where('action', 'login')
                ->whereDate('created_at', today())
                ->count())
                ->description('Logins today')
                ->descriptionIcon('heroicon-o-arrow-right-on-rectangle')
                ->color('info'),
        ];
    }
} 