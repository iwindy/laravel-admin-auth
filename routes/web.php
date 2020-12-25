<?php

use Iwindy\Auth\Http\Controllers\AuthController;

Route::get('laravel-admin-auth', AuthController::class.'@index');