<?php

/*----------------------------------------
 | Register application controllers      |
 |                                       |
 | You can define your app controllers   |
 | here instead of instantiating them    |
 | directly in the routes file.          |
 |                                       |
 | Instantiate the controller(s) you     |
 | want in the $controllers array below. |
 ----------------------------------------*/

 use ForExample\Controllers\Auth\AuthController;
 use ForExample\Controllers\Auth\LoginController;
 use ForExample\Controllers\Auth\RegisterController;

/** @var \Shiblati\Framework\Container $app */
 $controllers = [
     'auth'     => new AuthController($app),
     'register' => new RegisterController($app),
     'login'    => new LoginController($app),
 ];

return extract($controllers);
