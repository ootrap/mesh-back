<?php

namespace Star\wechat;

use Illuminate\Support\ServiceProvider;
use Star\wechat\WeOpen;

class WeOpenServiceProvider extends ServiceProvider
{

    /**
     * 延迟加载
     *
     * @var boolean
     */
    protected $defer = true;


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (function_exists('config_path')) {
            $this->publishes([
                __DIR__ . '/config.php' => config_path('wechat.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(WeOpen::class, function($app){
            return new WeOpen;
        });
    }
}
