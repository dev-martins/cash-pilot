<?php

namespace App\Providers;

use App\Events\TransferCompleted;
use App\Listeners\ProcessCashback;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        TransferCompleted::class => [
            ProcessCashback::class,
        ],
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
