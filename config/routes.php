<?php

/** @var \Shiblati\Framework\Container $app */
$router = $app['router'];

/*----------------------------------------
 | Standard page routes                  |
 ----------------------------------------*/
 $router->get('/', fn() => (new \ForExample\Controllers\HomeController($app))->view());

/*----------------------------------------
 | Authentication routes                 |
 ----------------------------------------*/
// $router->get('/register', fn() => $register->view());
// $router->post('/register', fn($request, $response) => $register->submit($request, $response));
//
// $router->get('/login', fn() => $login->view());
// $router->post('/login', fn($request, $response) => $login->submit($request, $response));
// $router->post('/logout', fn($request, $response) => $auth->logout($request, $response));
