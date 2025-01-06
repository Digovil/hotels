<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('list_routes')) {
    function list_routes($routeFiles)
    {
        foreach ($routeFiles as $routeFile) {
            // Omitir archivos
            // if (
            //     basename($routeFile) === 'NameFile.php' 
            // ) {
            //     continue;
            // }

            require $routeFile;
        }
    }
}

Route::prefix('v1')->group(function () {
    list_routes(glob(app_path('../routes/API/V1/*/*.php')));
});
