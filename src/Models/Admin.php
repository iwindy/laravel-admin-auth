<?php


namespace Iwindy\Auth\Models;


use Encore\Admin\Models\Administrator;
use Spatie\Permission\Traits\HasPermissions;

class Admin extends Administrator
{
    use HasPermissions;

    /**
     * @var int 超级管理员ID
     */
    protected $rootAdminId;

    public function rootAdminId(){
        $this->rootAdminId = 1;
    }
}
