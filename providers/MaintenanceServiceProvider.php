<?php

declare(strict_types=1);

namespace Pensoft\Maintenance\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Pensoft\Maintenance\Contracts\ResponseMaker;
use Pensoft\Maintenance\Classes\MaintenanceResponder;

final class MaintenanceServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ResponseMaker::class, MaintenanceResponder::class);

        $this->registerMaintenanceHandler();
    }

    protected function registerMaintenanceHandler()
    {
        $this->app->booted(static function (Application $app) {
            if($app->isDownForMaintenance() && !$app->runningInConsole()){
                $responder = $app->make(ResponseMaker::class);
                $responder->getResponse()->send();
                exit;
            }
        });
    }
}
