<?php


namespace Iwindy\Auth\Http\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Show;
use Encore\Admin\Table;
use Encore\Admin\Http\Controllers\AdminController;

class RolesController extends AdminController
{
    protected  $roleClass;
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '角色管理';

    public function __construct()
    {
        $this->title = __('admin.roles');
        $this->roleClass = config('permission.models.role');
    }

    /**
     * Make a table builder.
     *
     * @return Table
     */
    protected function table()
    {
        $table = new Table(app($this->roleClass));
        $table->actions(function ($actions) {
            // 去掉查看
            $actions->disableView();
        });
        // 禁用导出
        $table->disableExport();

        $table->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('name', __('auth.role_name'));
        });
        $table->column('name',__('auth.role_name'));
        $table->column('created_at',__('admin.created_at'));
        $table->column('updated_at',__('admin.updated_at'));

        return $table;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(app($this->roleClass)->findOrFail($id));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(app($this->roleClass));
        $form->tools(function (Form\Tools $tools) {
            // 去掉`查看`按钮
            $tools->disableView();
        });

        $form->text('name', __('role_name'));


        return $form;
    }
}
