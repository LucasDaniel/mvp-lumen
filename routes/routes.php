<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
*/

$app->group(['prefix' => 'api/v1'], function($app)
{
	/**
	 * Simulados
	*/
	$app->group(['prefix' => 'simulado'], function($app)
	{
    	// Insert Simulado
	    $app->post('/', 'SimuladosController@insert');

    	// Update Simulado
	    $app->put('/{code}', 'SimuladosController@update');

	    // List Simulado
	    $app->get('/{limit}/{offset}', 'SimuladosController@list');
    });

});

$app->get('/', function() use ($app) {
    return "Access denied for user. See API documentation";
});