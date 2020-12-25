<?php

namespace Iwindy\Auth;

use Encore\Admin\Extension;

class Auth extends Extension
{
    public $name = 'laravel-admin-auth';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public $menu = [
        'title' => 'Auth',
        'path'  => 'laravel-admin-auth',
        'icon'  => 'fa-gears',
    ];
}