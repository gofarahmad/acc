<?php

namespace App\Filament\Resources\Accounts;

use App\Filament\Exports\AccountExporter;
use App\Filament\Imports\AccountImporter;
use App\Filament\Resources\Accounts\Pages\ManageAccounts;
use App\Models\Account;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ImportAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(255),
                    Select::make('box_id')
                    ->label('Box')
                    ->relationship('box', 'name')
                    ->searchable()
                    ->placeholder('Select a box')
                    ->nullable()
                    ->preload(),
                TextInput::make('first_name')
                    ->label('First Name')
                    ->maxLength(255),
                TextInput::make('last_name')
                    ->label('Last Name')
                    ->maxLength(255),
                TextInput::make('date')
                    ->label('Date')
                    ->maxLength(20),
                TextInput::make('month')
                    ->label('Month')
                    ->maxLength(20),
                TextInput::make('year')
                    ->label('Year')
                    ->maxLength(20),
                TextInput::make('gender')
                    ->label('Gender'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->label('Email'),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                return $query->where('user_id', Auth::id());
            })
            ->columns([
                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable(),
                TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable(),
                TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable(),
                TextColumn::make('date')
                    ->label('Date')
                    ->searchable(),
                TextColumn::make('month')
                    ->label('Month')
                    ->searchable(),
                TextColumn::make('year')
                    ->label('Year')
                    ->searchable(),
                TextColumn::make('gender')
                    ->label('Gender')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->tooltip('Click To Copy Email'),
                TextColumn::make('password')
                    ->formatStateUsing(function () {
                        return '********';
                    })
                    ->tooltip('Click To Copy Password')
                    ->copyable()
                    ->copyableState(fn (Account $record) => $record->password),
                    // ->action(function (Account $record, TextColumn $column, \Livewire\Component $livewire) {
                    //     $password = $record->getDecryptedPassword();
                    //     $livewire->dispatch('copyPasswordToClipboard', [
                    //         'password' => $password,
                    //     ]);
                    // }),
                TextColumn::make('created_at')
                    ->formatStateUsing(function ($state) {
                        return $state->diffForHumans();
                    })
                    ->sortable(),
                TextColumn::make('box.name')
                    ->label('Box')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                ImportAction::make()
                    ->label('Import Accounts')
                    ->importer(AccountImporter::class)
                    ->icon(Heroicon::OutlinedArrowUpTray),
                ExportAction::make()
                    ->exporter(AccountExporter::class)
                    ->label('Export Accounts')
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->columnMappingColumns(2),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exporter(AccountExporter::class)
                        ->label('Export Selected Accounts')
                        ->columnMappingColumns(2),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAccounts::route('/'),
        ];
    }
}
