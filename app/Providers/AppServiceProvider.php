<?php

namespace App\Providers;

use App\Product;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

       Product::updated(function ($product){ // it  will change when you need t o update you database row after successfully action always
           if ($product->quantity === 0 && $product->isAvailable())
           {
               $product->status = Product::UNAVAILABLE_PRODUCT;
               $product->save();
           }
       });

    }
}
