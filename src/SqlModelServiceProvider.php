<?php

namespace Geeksdevelop\Sqlmodel;

use Illuminate\Support\ServiceProvider;

class SqlModelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if($this->app->runningInConsole()){
            $this->commands([
                Commands\SqlModelCommand::class,
                Commands\ModelMake::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
