<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the name of your application that will be displayed
    | on the browser tab when the dashboard page is opened.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#61-title
    |
    */

    'title' => 'Admin Panel',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#62-favicon
    |
    */

    'use_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your application and also the logo sidebar
    | to custom long or short logo.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#63-logo
    |
    */

    'logo' => '<b>Admin</b>LTE',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_alt' => 'AdminLTE Logo',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#64-user-menu
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_default_icon' => 'fas fa-user',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can define all the menu items that should appear in the sidebar.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#65-menu
    |
    */

    // --- MULAI DARI SINI: Ini adalah bagian array 'menu' yang saya berikan sebelumnya.
    //     Salin array menu Anda yang terakhir (yang Anda kirimkan) ke sini.
    //     Pastikan Anda MENUTUP array 'menu' ini dengan '],'.
    'menu' => [
        // Navbar items
        [
            'type'         => 'navbar-search',
            'text'         => 'search',
            'topnav_right' => true,
        ],
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => true,
        ],

        // Sidebar items
        [
            'text' => 'blog',
            'url'  => 'admin/blog',
            'can'  => 'manage-blog', // Contoh permission
        ],
        ['header' => 'ADMINISTRASI'],
        [
            'text'        => 'Dashboard',
            'url'         => 'dashboard',
            'icon'        => 'fas fa-fw fa-tachometer-alt',
            'active'      => ['dashboard'],
        ],
        [
            'text' => 'Manajemen Berita',
            'icon' => 'fas fa-fw fa-newspaper',
            'can'  => 'create news', // Hanya yang punya permission ini yang bisa lihat
            'submenu' => [
                [
                    'text' => 'Daftar Berita',
                    'url'  => 'admin/news_articles',
                    'icon' => 'fas fa-fw fa-list',
                    'active' => ['admin/news_articles*'],
                    'can'  => 'edit news', // Siapa saja yang bisa melihat daftar berita
                ],
                [
                    'text' => 'Tambah Berita Baru',
                    'url'  => 'admin/news_articles/create',
                    'icon' => 'fas fa-fw fa-plus-square',
                    'active' => ['admin/news_articles/create'],
                    'can'  => 'create news',
                ],
            ],
        ],
        [
            'text' => 'Manajemen Kategori',
            'icon' => 'fas fa-fw fa-tags',
            'url'  => 'admin/categories',
            'active' => ['admin/categories*'],
            'can'  => 'manage categories', // Hanya Admin dan Editor
        ],
        [
            'text' => 'Manajemen User',
            'icon' => 'fas fa-fw fa-users',
            'url'  => 'admin/users',
            'active' => ['admin/users*', 'admin/users/*/edit'], // Agar menu tetap aktif saat di halaman edit user
            'can'  => 'manage users', // Hanya Admin
        ],
        ['header' => 'PENGATURAN AKUN'],
        [
            'text' => 'Profil',
            'url'  => 'profile',
            'icon' => 'fas fa-fw fa-user',
            'active' => ['profile'],
        ],
    ], // <-- PASTIKAN KURUNG PENUTUP DAN KOMA INI ADA UNTUK ARRAY 'menu'

    // --- AKHIR DARI BAGIAN ARRAY 'menu' ---

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can define some filters who will be applied to the menu items.
    |
    | For more detailed instructions can be found here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#66-menu-filters
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can define that a plugin should be loaded and activated when
    | a page is loaded here.
    |
    | For more detailed instructions can be found here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#67-plugins
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@11',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/pace-js@1.2.4/themes/blue/pace-theme-flat-top.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/pace-js@1.2.4/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For more detailed instructions can be found here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#68-livewire
    |
    */

    'livewire' => false,
]; // <-- PASTIKAN KURUNG PENUTUP DAN SEMICOLON INI ADA DI AKHIR FILE