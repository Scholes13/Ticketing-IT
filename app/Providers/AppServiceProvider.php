<?php

namespace App\Providers;

use Carbon\Carbon;
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
        // Configure Carbon to use Indonesian locale
        Carbon::setLocale('id');
        
        // Define custom date formats for Indonesian style
        Carbon::macro('formatIndonesian', function () {
            return $this->setTimezone('Asia/Jakarta')
                        ->translatedFormat('d F Y, H:i') . ' WIB';
        });
        
        // Define short date format for Indonesian style
        Carbon::macro('formatIndonesianShort', function () {
            return $this->setTimezone('Asia/Jakarta')
                        ->translatedFormat('d M Y, H:i') . ' WIB';
        });
    }
}
