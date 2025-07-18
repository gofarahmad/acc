<?php

namespace App\Filament\Resources\Accounts\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ImportListAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->name('import_list');
        $this->label('Import From List');
        $this->icon(Heroicon::OutlinedClipboardDocumentList);
        $this->color('primary');

        $this->schema([
            Select::make('box_id')
                ->label('Box')
                ->relationship('box', 'name')
                ->searchable()
                ->placeholder('Select a box')
                ->nullable()
                ->preload(),
            Textarea::make('import_list')
                ->label('Accounts List')
                ->placeholder('Paste your list here')
                ->helperText(
                    'Line Format: full_name|first_name|last_name|date|month|year|gender|email|password'
                )
                ->required()
                ->rows(10),
            TextInput::make('field_separator')
                ->label('Field Separator')
                ->default('|')
                ->placeholder('Specify the character used to separate fields')
                ->maxLength(1)
                ->minLength(1)
                ->columnSpanFull()
                ->required()
                ->helperText('The character used to separate fields in each account entry. Default is "|"'),
            Toggle::make('skip_header')
                ->label('Skip Header')
                ->default(true)
                ->inline(false)
                ->columnSpanFull()
                ->helperText('If your list has a header row, enable this to skip it'),
        ]);

        $this->action(function (array $data, Action $action) {
            // $encryptionKey = session('__app_encryption_key', null);
            // if (empty($encryptionKey)) {
            //     $action->failureNotification(fn (Notification $notification) => $notification
            //         ->title('Encryption Key Missing')
            //         ->body('Please regenerate your encryption key with logout and login again.')
            //         ->danger());
            //     $action->failure();
            //     return;
            // }

            $accounts = explode("\n", $data['import_list']);
            $accounts = array_map(function ($account) use ($data) {
                $fields = explode($data['field_separator'], $account);

                // $plainPassword = $fields[8] ?? null;
                // $encryptedPassword = null;
                // if (!empty($plainPassword)) {
                //     $encryptedPassword = encrypt_with_password($plainPassword, $encryptionKey);
                // }

                return [
                    'full_name' => trim($fields[0] ?? ''),
                    'first_name' => trim($fields[1] ?? ''),
                    'last_name' => trim($fields[2] ?? ''),
                    'date' => trim($fields[3] ?? ''),
                    'month' => trim($fields[4] ?? ''),
                    'year' => trim($fields[5] ?? ''),
                    'gender' => trim($fields[6] ?? ''),
                    'email' => trim($fields[7] ?? ''),
                    'password' => trim($fields[8] ?? ''),
                    'box_id' => $data['box_id'] ?? null,
                ];
            }, $accounts);

            if ($data['skip_header']) {
                // Skip the first entry if it's a header
                array_shift($accounts);
            }

            $accounts = array_filter($accounts, function ($account) {
                // Try to skip header or invalid entries
                if (str_contains((strtolower(trim($account['full_name']))), 'fullname')) {
                    return false; // Skip header or invalid entries
                }

                return !empty($account['email']);
            });

            if (empty($accounts)) {
                $action->failureNotification(fn (Notification $notification) => $notification
                    ->title('No Valid Accounts Found')
                    ->body('Please ensure your list is formatted correctly.')
                    ->danger());
                $action->failure();
                return;
            }

            $createdCount = 0;
            $updatedCount = 0;

            foreach ($accounts as $accountData) {
                $account = \App\Models\Account::updateOrCreate(
                    ['email' => $accountData['email']],
                    $accountData
                );

                if ($account->wasRecentlyCreated) {
                    $createdCount++;
                } else {
                    $updatedCount++;
                }
            }

            $action->successNotification(fn (Notification $notification) => $notification
                ->title('Import Successful')
                ->body("Imported {$createdCount} new accounts and updated {$updatedCount} existing accounts.")
                ->success());

            $action->success();
        });
    }
}
