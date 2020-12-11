<?php

namespace Pensoft\Maintenance;

use System\Classes\PluginBase;
use Pensoft\Maintenance\Providers\MaintenanceServiceProvider;

class Plugin extends PluginBase
{
    public function register(){
        $this->app->register(MaintenanceServiceProvider::class);
    }
}
