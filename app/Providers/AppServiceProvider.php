<?php

namespace App\Providers;

use App\Console\Commands\ServeWithUploadLimitsCommand;
use Illuminate\Console\Application as ConsoleApplication;
use Illuminate\Support\ServiceProvider;

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
        if (app()->environment('local')) {
            ConsoleApplication::starting(static function (ConsoleApplication $artisan): void {
                $artisan->add(new ServeWithUploadLimitsCommand);
            });
        }
    }
}
