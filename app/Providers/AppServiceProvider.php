<?php

namespace App\Providers;

use App\Models\PaymentMethods;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Currency;
use App\Models\Settings;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Category;
use Illuminate\Support\Facades\View;
use App\Models\Ads;
use App\Models\BastaatWorks;
use Illuminate\Support\Facades\Validator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //check that app is local
        if ($this->app->isLocal()) {
            //if local register your services you require for development
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
            $this->app['request']->server->set('HTTPS', false);

        }else{
            //else register your services you require for production
            $this->app['request']->server->set('HTTPS', false);
        }
        $this->app->singleton('settings', function () {
            return Settings::first();
        });


    }

    public function sharedViews()
    {

        View::share([
            'settings' => app('settings'),

        ]);
    }

}
