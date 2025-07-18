<?php

namespace App\Filament\Widgets;

use App\Models\Account;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class AccountStat extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $accounts = Account::query()
            ->where('user_id', Auth::id())
            ->get();
        return [
            Stat::make('Total Accounts', $accounts->count())
                ->icon('heroicon-o-users')
                ->color('primary'),
        ];
    }
}
