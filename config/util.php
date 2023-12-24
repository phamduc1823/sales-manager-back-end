<?php

return [
    'SEED_TYPES' => [
        0 => \App\Models\Tenant\Category::class,
        1 => \App\Models\Tenant\Brand::class,
        2 => \App\Models\Tenant\ItemUnit::class
    ],
    'TENANT_MENUS' => [
        [
            'name' => 'dashboard',
            'roles' => ['super-admin', 'admin'],
        ],
        [
            'name' => 'shop',
            'roles' => ['super-admin', 'admin', 'staff'],
        ],
        [
            'name' => 'orders',
            'roles' => ['super-admin', 'admin'],
        ],
        [
            'name' => 'products',
            'roles' => ['super-admin'],
        ],
        [
            'name' => 'storage',
            'roles' => ['super-admin', 'admin'],
        ],
        [
            'name' => 'customers',
            'roles' => ['super-admin'],
        ],
        [
            'name' => 'suppliers',
            'roles' => ['super-admin'],
        ],
        [
            'name' => 'debts',
            'roles' => ['super-admin', 'admin'],
        ],
        [
            'name' => 'users',
            'roles' => ['super-admin', 'admin'],
        ],
        [
            'name' => 'settings',
            'roles' => ['super-admin'],
        ],
    ],
];
