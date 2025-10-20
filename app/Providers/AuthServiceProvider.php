<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Request as RequestModel; 
use App\Policies\RequestPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [

        RequestModel::class => RequestPolicy::class, 
    ];


    public function boot(): void
    {

        $this->registerPolicies();

    }
}