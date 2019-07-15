<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use App\Models\Trainee as Trainee;

class LoginedtraineeProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Trainee::class, function ($app) {
            $request = $app->make(Request::class);
            return Trainee::find($request->session()->get('logined_trainee')['trainee_id']);
        });
    }
}
