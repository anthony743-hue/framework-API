<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
// Étape A : endpoint écrit à la main
$routes->get('api/manuel/livres/(:num)', 'Api\LivresManuel::show/$1');
// Étape B : la ressource complète (new et edit servent des formulaires HTML, inutiles pour une API)
$routes->group('api', ['namespace' => 'App\Controllers\Api\v1'], static function ($routes) {
    $routes->get('livres/(:num)/(:num)', 'Livres::index/$1/$2');
    $routes->resource('livres', ['except' => 'new,edit']);
    $routes->resource('members', ['except' => 'new,edit']);
    $routes->group('members', static function ($routes) {
        $routes->get('(:num)/(:num)', 'Members::index/$1/$2');
        $routes->get('(:num)/emprunts', 'Members::emprunts/$1');
    });
});
