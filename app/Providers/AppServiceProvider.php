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
            retry(5, function () use($user){ // some times our mail is not send for any type of error it is not unexpected. so this region we user laravel helper function retry, first parameter is how many time it will use, second is a callback that excuit it and third parameter is how many time for wait.
                Mail::to($user)->send(new UserCreated($user));
            },100);
        });

        User::updated(function ($user) {
           if ($user->isDirty('email')){ // only when change email
               retry(5, function () use($user) {
                   Mail::to($user)->send(new UserMailChange($user));
               }, 100);

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
