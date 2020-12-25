<?php

use Iwindy\Auth\Http\Controllers\AdminController;
use Iwindy\Auth\Http\Controllers\RolesController;

$router->resource('auth/roles', RolesController::class);
$router->resource('auth/admin', AdminController::class);

