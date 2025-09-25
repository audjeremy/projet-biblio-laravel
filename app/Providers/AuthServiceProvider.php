<?php

namespace App\Providers;

use App\Models\Order;
use App\Policies\OrderPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Order::class => \App\Policies\OrderPolicy::class,
    ];

    public function boot(): void
    {
        // rien d'autre à faire
    }
}
