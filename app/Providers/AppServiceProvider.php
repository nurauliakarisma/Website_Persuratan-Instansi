<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
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
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');

        Blade::directive('formatDate', function ($expression) {
            return "<?php echo !empty($expression) ? \Carbon\Carbon::parse($expression)->locale('id')->translatedFormat('d F Y') : '-'; ?>";
        });
    }
}
