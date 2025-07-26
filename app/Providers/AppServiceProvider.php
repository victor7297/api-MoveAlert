<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider{

    public function register(): void{
    
    }

    public function boot(): void{

        //Broadcast::routes();
        //require base_path("routes/channels.php");
        
    }
}
