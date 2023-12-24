<?php

namespace App\Traits;

use App\Services\Menu\MenuManager;

trait TMenu
{
    public function callApp()
    {
        $this->configMenuAdmin();
    }

    private function configMenuAdmin()
    {
        $this->app->singleton('menu', function () {
            return new MenuManager();
        });
    }
}
