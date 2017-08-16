<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/statistics'], function (Router $router) {
    $router->get('dashboards', [
        'as' => 'admin.statistics.dashboard.index',
        'uses' => 'DashboardController@index'
    ]);
    $router->get('export', [
        'as' => 'admin.statistics.dashboard.export',
        'uses' => 'DashboardController@export'
    ]);
});
