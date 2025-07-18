<?php

namespace App\Filament\Imports;

use App\Models\Account;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class AccountImporter extends Importer
{
    protected static ?string $model = Account::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('full_name')
                ->label('Full Name'),
            ImportColumn::make('first_name')
                ->label('First Name'),
            ImportColumn::make('last_name')
                ->label('Last Name'),
            ImportColumn::make('date')
                ->label('Date'),
            ImportColumn::make('month')
                ->label('Month'),
            ImportColumn::make('year')
                ->label('Year'),
            ImportColumn::make('gender')
                ->label('Gender'),
            ImportColumn::make('email')
                ->rules(['email']),
            ImportColumn::make('password')
                ->label('Password')
                // ->castStateUsing(function ($state) {
                //     if (blank($state)) {
                //         return null;
                //     }

                //     if (empty(session('__app_encryption_key'))) {
                //         return $state;
                //     }

                //     return encrypt_with_password($state, session('__app_encryption_key'));
                // }),
        ];
    }

    public function resolveRecord(): Account
    {
        return Account::firstOrNew([
            'email' => $this->data['email'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your account import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
