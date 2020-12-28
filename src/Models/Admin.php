<?php


namespace Iwindy\Auth\Models;


use Encore\Admin\Models\Administrator;
use Spatie\Permission\Traits\HasPermissions;

class Admin extends Administrator
{
    use HasPermissions;
    protected $guard_name = 'admin';
}
