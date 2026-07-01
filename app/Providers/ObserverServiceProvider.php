<?php

namespace App\Providers;

use App\Domain\Order\Model\Order;
use App\Models\User;
use App\Observers\OrderObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

/**
 * Class ObserverServiceProvider.
 */
class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function boot()
    {
        User::observe(UserObserver::class);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        //
    }
}
