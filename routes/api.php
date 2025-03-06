<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route pour la page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Groupe de routes pour l'API
Route::prefix('api')->group(function () {
    // Routes pour les report cases
    Route::resource(
        'reportcases',
        'App\Http\Controllers\ReportcaseController'
    )->except(['create', 'edit'])->middleware('auth:sanctum');

    // Routes pour les diseases
    Route::resource(
        'diseases',
        'App\Http\Controllers\DiseaseController'
    )->except(['create', 'edit'])->middleware('auth:sanctum');

    // Routes pour les localizations
    Route::resource(
        'localizations',
        'App\Http\Controllers\LocalizationController'
    )->except(['create', 'edit'])->middleware('auth:sanctum');

    // Route pour la page de documentation de l'API
    Route::get('documentation', function () {
        $documentation = config('l5-swagger.default');
        $urlToDocs = route('l5-swagger.default.docs');
        $operationsSorter = config('l5-swagger.defaults.operations_sort');
        $configUrl = config('l5-swagger.defaults.additional_config_url');
        $validatorUrl = config('l5-swagger.defaults.validator_url');
        $useAbsolutePath = config('l5-swagger.defaults.paths.use_absolute_path');

        return view('l5-swagger::index', compact(
            'documentation',
            'urlToDocs',
            'operationsSorter',
            'configUrl',
            'validatorUrl',
            'useAbsolutePath'
        ));
    });

    // Route pour rediriger /api vers /api/documentation
    Route::get('/', function () {
        return redirect('/api/documentation');
    });
});
