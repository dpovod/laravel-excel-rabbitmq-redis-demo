<?php

namespace App\Providers;

use App\Extensions\Queue\Connectors\RabbitmqConnector;
use Illuminate\Support\ServiceProvider;

class QueueServiceProvider extends ServiceProvider
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
        $manager = $this->app['queue'];

        $manager->addConnector('rabbitmq', function()
        {
            return new RabbitmqConnector;
        });
    }
}
