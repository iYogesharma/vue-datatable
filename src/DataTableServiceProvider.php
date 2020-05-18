<?php

namespace YS\VueDatatable;

use Illuminate\Support\ServiceProvider;

class DataTableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $dataTableConfig=__DIR__.'/config/datatable.php';

        $this->mergeConfigFrom($dataTableConfig, 'datatable');

        $this->publishes([
            __DIR__.'/config/datatable.php' => config_path('datatable.php'),
        ],'datatable:config');
    }
}
