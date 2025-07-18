<?php

namespace App\Filament\Exports;

use App\Models\Account;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class AccountExporter extends Exporter
{
    protected static ?string $model = Account::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('full_name')
                ->label('Full Name'),
            ExportColumn::make('first_name')
                ->label('First Name'),
            ExportColumn::make('last_name')
                ->label('Last Name'),
            ExportColumn::make('date')
                ->label('Date'),
            ExportColumn::make('month')
                ->label('Month'),
            ExportColumn::make('year')
                ->label('Year'),
            ExportColumn::make('gender')
                ->label('Gender'),
            ExportColumn::make('email')
                ->label('Email'),
            ExportColumn::make('password')
                ->label('Password')
                // ->formatStateUsing(function (Account $record) {
                //     return $record->getDecryptedPassword() ?? '********';
                // }),
        ];
    }

    public static function getCsvDelimiter(): string
    {
        return '|';
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your account export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
