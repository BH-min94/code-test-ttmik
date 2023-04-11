<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UtilController;


/* APIs */
Route::prefix('v1')->group(function () {

    /* POST */

        /* Utils */
        Route::post('/country', [UtilController::class, 'request_get_country']);

        Route::post('/upload', [UtilController::class, 'upload_ip_table']);

    /*  POST END */

});