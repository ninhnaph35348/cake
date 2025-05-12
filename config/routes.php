<?php

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return function (RouteBuilder $routes): void {
    $routes->setRouteClass(DashedRoute::class);

    // Scope cho user
    $routes->scope('/users', function (RouteBuilder $builder) {
        $builder->connect('/login', ['controller' => 'Users', 'action' => 'login']);
        $builder->connect('/api-login', ['controller' => 'Users', 'action' => 'apiLogin']);
        $builder->fallbacks();
    });

    // Scope cho API
    $routes->scope('/api', function (RouteBuilder $builder) {
        $builder->setExtensions(['json']); // Đảm bảo phản hồi JSON
        $builder->resources('Articles', [
            'prefix' => 'Api', // Ánh xạ đến App\Controller\Api\ArticlesController
            'map' => [
                'index' => ['action' => 'index', 'method' => 'GET', 'path' => ''],
                'view' => ['action' => 'view', 'method' => 'GET', 'path' => '{id}'],
                'add' => ['action' => 'add', 'method' => 'POST', 'path' => ''],
                'edit' => ['action' => 'edit', 'method' => ['PUT', 'PATCH'], 'path' => '{id}'],
                'delete' => ['action' => 'delete', 'method' => 'DELETE', 'path' => '{id}'],
            ]
        ]);
    });

    // Scope cho frontend
    $routes->scope('/', function (RouteBuilder $builder): void {
        $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);
        $builder->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);
        $builder->fallbacks();
    });
};
