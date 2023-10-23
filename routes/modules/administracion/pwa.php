<?php

use Illuminate\Support\Facades\Route;

Route::get('/offline', function () {
    return 'No se encuentra en linea';
});
