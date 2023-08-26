<?php


use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes) {

    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder) {

        $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);


        $builder->connect('/pages/*', 'Pages::display');


        $builder->fallbacks();
    });


    $routes->scope('/bookmarks', ['controller' => 'Bookmarks'], function (RouteBuilder $builder) {
        $builder->connect('/tagged/*', ['action' => 'tags']);
    });
};
