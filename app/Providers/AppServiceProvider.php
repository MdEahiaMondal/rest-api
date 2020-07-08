<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Mail\UserMailChange;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Mail;
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

        User::created(function ($user) { /*($user) you can call instance*/
            Mail::to($user)->send(new UserCreated($user));
        });

        User::updated(function ($user) {
           if ($user->isDirty('email')){ // only when change email
               Mail::to($user)->send(new UserMailChange($user));
           }
        });

       Product::updated(function ($product){ // it  will change when you need t o update you database row after successfully action always
           if ($product->quantity === 0 && $product->isAvailable())
           {
               $product->status = Product::UNAVAILABLE_PRODUCT;
               $product->save();
           }
       });

    }
}
