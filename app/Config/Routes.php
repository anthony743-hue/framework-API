<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
// Étape A : endpoint écrit à la main
$routes->get('api/manuel/livres/(:num)', 'Api\LivresManuel::show/$1');
// Étape B : la ressource complète (new et edit servent des formulaires HTML, inutiles pour une API)
$routes->group('api', ['namespace' => 'App\Controllers\Api\v1'], static function ($routes) {

    // Livres
    $routes->get('livres/(:num)/(:num)', 'Livres::index/$1/$2');
    $routes->resource('livres', ['except' => 'new,edit']);

    // Members
    $routes->resource('members', ['except' => 'new,edit']);

    // Sous-routes membres
    $routes->group('members', static function ($routes) {
        $routes->get('(:num)/emprunts', 'Emprunts::empruntsDuMembre/$1');

        $routes->get('(:num)/emprunts/(:num)', 'Emprunts::empruntDuMembre/$1/$2');

        $routes->post('(:num)/emprunts/(:num)', 'Emprunts::createPourMembre/$1/$2');

        $routes->patch('(:num)/emprunts/(:num)', 'Emprunts::update/$1');
    });

    $routes->resource('emprunts', ['only' => 'create,show']);
});
