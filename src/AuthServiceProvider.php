<?php

namespace Iwindy\Auth;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(Auth $extension)
    {
        if (! Auth::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'admin-auth');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/iwindy/admin-auth')],
                'laravel-admin-auth'
            );
        }

        $this->app->booted(function () {
            Auth::routes(__DIR__.'/../routes/web.php');
        });
    }
}
