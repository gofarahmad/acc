<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

use function Filament\Support\original_request;

class GenerateEncryptionKeySession
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        Log::debug('Generating encryption key for user', ['user_id' => $event->user->id]);

        $salt = $event->user->email;
        $key = hash('sha256', $salt);
        $deriveKey = generate_encryption_key($key, $salt);

        session(['__app_encryption_key' => $deriveKey]);
    }
}
