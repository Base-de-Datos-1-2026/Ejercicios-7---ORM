<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return [
        'project' => 'Bookstore Eloquent Lab',
        'description' => 'Dominio de libreria en linea modelado con Laravel y Eloquent.',
        'queries' => 'Las consultas Eloquent estan en app/Examples/EloquentQueries.php.',
    ];
});
