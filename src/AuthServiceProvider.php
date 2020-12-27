<?php

namespace Iwindy\Auth;

use Encore\Admin\Form;
use Illuminate\Support\ServiceProvider;
use Iwindy\Auth\Form\Field\CheckboxGroup;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(Auth $extension)
    {
        if (!Auth::boot()) {
            return;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'admin-auth');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/iwindy/laravel-admin-auth')],
                'assets'
            );
        }

        if ($this->app->runningInConsole() && $lang = $extension->lang()) {
            $this->publishes([$lang => resource_path('lang')], 'lang');
        }

        if ($this->app->runningInConsole()) {
            $this->publishes(
                [__DIR__ . '/../routes/web.php' => admin_path('routes.php')],
                'routes'
            );
        }

        Form::extend('checkboxGroup', CheckboxGroup::class);
    }
}
