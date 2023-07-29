<?php

namespace Mnmsi\GenCrudOps;

use Illuminate\Support\ServiceProvider;

class GenCrudOpsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //publish config file
        $this->publishes([__DIR__.'/../config/gencrudops.php' => config_path('gencrudops.php')]);

        //default-theme
        $this->publishes([__DIR__.'/stubs/default-views/' => resource_path('gencrudops/views/default-views/')]);

        //and default-layout
        $this->publishes([__DIR__.'/stubs/default-layout.stub' => resource_path('views/default.blade.php')]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/gencrudops.php', 'gencrudops');

        $this->commands(
            'Mnmsi\GenCrudOps\Console\MakeCrud',
            'Mnmsi\GenCrudOps\Console\MakeViews',
        );
    }
}
