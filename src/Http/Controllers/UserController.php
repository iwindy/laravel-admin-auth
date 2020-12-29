<?php

namespace Iwindy\Auth\Http\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Http\Controllers\AdminController as BaseController;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Table;
use Illuminate\Support\Facades\Auth;
use Iwindy\Auth\Table\Actions\Users\SetPermissions;
use Request;
use Spatie\Permission\Models\Permission;

class UserController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function title()
    {
        return trans('admin.administrator');
    }

    /**
     * Make a table builder.
     *
     * @return Table
     */
    protected function table()
    {
        $userModel = config('admin.database.users_model');
        $user = new $userModel();
        $table = new Table($user);
        // 禁用导出数据
        $table->disableExport();
        // 筛选
        $table->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('username', __('auth.username'));
            $filter->like('name', __('auth.admin_name'));
        });
        $table->column('id', 'ID')->sortable();
        $table->column('username', trans('auth.username'));
        $table->column('name', trans('auth.admin_name'));
        $table->column('created_at', trans('admin.created_at'));
        $table->column('is_enable', trans('auth.status'))->using([0 => '禁用', 1 => '启用']);

        $table->actions(function (Table\Displayers\Actions $actions) use ($user) {
            if ($actions->getKey() == $user->getRootAdminId()) {
                $actions->disableDelete();
            }
            $actions->add(new SetPermissions());
        });

        $table->tools(function (Table\Tools $tools) {
            $tools->batch(function (Table\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        return $table;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        $userModel = config('admin.database.users_model');

        $show = new Show($userModel::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('username', trans('admin.username'));
        $show->field('name', trans('admin.name'));
        $show->field('created_at', trans('admin.created_at'));
        $show->field('updated_at', trans('admin.updated_at'));
        $show->panel()
            ->tools(function ($tools) {
                // $tools->disableEdit();
                // $tools->disableList();
                $tools->disableDelete();
            });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $userModel = config('admin.database.users_model');

        $form = new Form(new $userModel());

        $userTable = config('admin.database.users_table');
        $connection = config('admin.database.connection');

        $form->display('id', 'ID');
        $form->text('username', trans('admin.username'))
            ->creationRules(['required', "unique:{$connection}.{$userTable}"])
            ->updateRules(['required', "unique:{$connection}.{$userTable},username,{{id}}"]);

        $form->text('name', trans('admin.name'))->rules('required');
        $form->image('avatar', trans('admin.avatar'));
        $form->password('password', trans('admin.password'))->rules('required|confirmed');
        $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });

        $form->ignore(['password_confirmation']);

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            }
        });

        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
        });

        return $form;
    }

    public function showPermissions(Content $content)
    {
        $id = Request::input('id');
        $userModel = config('admin.database.users_model');

        $user = $userModel::findOrFail($id);
        $form = new Form($user);
        $form->setAction(route('admin.set-permissions'));
        $form->setTitle(__('auth.set_permissions'));
        $form->hidden('permissions.id')->value($id);

        $form->checkboxGroup('permissions.list', trans('admin.permissions'))->options(function () {
            $permissions = Permission::where('guard_name', config('admin.auth.guard'))->get()->toArray();
            $new_permissions = [];
            foreach ($permissions as $keys => $values) {
                if (strpos($values['name'], '.') !== false) {
                    $group = explode('.', $values['name']);
                    $new_permissions[__('auth.' . $group[0])][__($values['name'])] = __('auth.' . $group[1]);
                } else {
                    $new_permissions[$values['name']] = __('auth.' . $values['name']);
                }
            }
            return $new_permissions;
        })->checked($user->permissions->pluck('name'));

        // 三个全部去掉
        $form->disableFooterCheck();
        $form->tools(function (Form\Tools $tools) {
            // 去掉`列表`按钮
            // $tools->disableList();
            // 去掉`删除`按钮
            $tools->disableDelete();
            // 去掉`查看`按钮
            $tools->disableView();
        });
        return $content->body($form);
    }

    public function setPermissions(Content $content)
    {
        $id = Request::input('permissions.id');
        $permissions = Request::input('permissions.list');
        $userModel = config('admin.database.users_model');
        $user = $userModel::findOrFail($id);

        $diff_add = collect($permissions)->whereNotNull()->diff($user->permissions->pluck('name'))->toArray();
        if (!empty($diff_add)) {
            $user->givePermissionTo($diff_add);
        }
        $diff_del = collect($user->permissions->pluck('name'))->diff($permissions)->toArray();
        if (!empty($diff_del)) {
            $user->revokePermissionTo($diff_del);
        }

        return response()->json([
            'status' => true,
            'message' => '编辑成功'
        ]);
    }

}
