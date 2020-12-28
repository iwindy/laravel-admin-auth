<?php


namespace Iwindy\Auth\Table\Actions\Users;


use Encore\Admin\Actions\RowAction;

class SetPermissions extends RowAction
{
    /**
     * @return array|null|string
     */
    public function name()
    {
        return __('auth.set_permissions');
    }

    /**
     * @return string
     */
    public function href()
    {
        return "{$this->getResource()}/showPermissions?id={$this->getKey()}";
    }
}
