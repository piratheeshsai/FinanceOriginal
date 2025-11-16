<?php

namespace App\Providers;

use App\Observers\ActivityObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Schema;

;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();
        
        $this->app->bind('path.public', function() {
        return base_path('../public_html'); // Points to web root
    });
    
        view()->composer('*', function ($view) {
            $view->with('company', \App\Models\Company::first());
        });


    }



}
