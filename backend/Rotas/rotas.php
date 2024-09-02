<?php

use Backend\Api\Controllers\UserController;

return [
    'GET' => [
        '/users' => [UserController::class, 'getAllUsers'],
        '/users/{id}' => [UserController::class, 'getUserById'],
    ],
    'POST' => [
        '/users' => [UserController::class, 'createUser'],
    ],
    'PUT' => [
        '/users/{id}' => [UserController::class, 'updateUser'],
    ],
    'DELETE' => [
        '/users/{id}' => [UserController::class, 'deleteUser'],
    ],
];
