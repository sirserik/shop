<?php
$router->get('/', function () {
    echo 'Homepage';
}, 'home'); // Именованный маршрут 'home'

$router->get('/users/{id}', function ($params) {
    echo 'User ID: ' . $params['id'];
}, 'user.profile'); // Именованный маршрут 'user.profile'
