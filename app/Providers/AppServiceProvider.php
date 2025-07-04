<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Message; 
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
    public function boot()
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $unreadIncomingCount = Message::where('receiver_id', Auth::id())
                                              ->where('is_read', 0)
                                              ->count();
    
                $view->with('unreadIncomingCount', $unreadIncomingCount);
            }
        });
    }
    
}
