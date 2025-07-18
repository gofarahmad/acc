<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ClearEncyptionKeySession
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
    public function handle(Logout $event): void
    {
        Log::debug('Clearing encryption key session for user', ['user_id' => $event->user->id]);

        // Clear the encryption key session or any related data
        if (session()->has('encryption_key')) {
            session()->forget('encryption_key');
        }
    }
}
