<?php

namespace App\Services\Menu;

class MenuManager
{
    protected $menus = [];

    public function __construct()
    {
        $this->menus = config('menus');
    }

    public function add($menu)
    {
        $this->menus[] = $menu;
    }

    public function get()
    {
        return $this->menus;
    }
}
