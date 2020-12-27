<?php

namespace Iwindy\Auth;

use Encore\Admin\Extension;
use Iwindy\Auth\Traits\BuiltinRoutes;

class Auth extends Extension
{

    public $name = 'laravel-admin-auth';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public $lang = __DIR__.'/../resources/lang';


    public $menu = [
        'title' => 'Auth',
        'path'  => 'laravel-admin-auth',
        'icon'  => 'fa-gears',
    ];

    public function lang(){
        return $this->lang;
    }


}
