<?php 	
	use App\Http\Controllers\Calendarización\MetasController;
	use App\Http\Controllers\Calendarización\TechosController;

	Route::controller(MetasController::class)->group(function () {
		Route::get('/calendarizacion/metas', 'getIndex')->name('index_metas');
	 	Route::get('/calendarizacion/data', 'getMetas')->name('getMetas');
        Route::get('/calendarizacion/proyecto', 'getProyecto')->name('proyecto');
	});

    Route::controller(TechosController::class)->group(function () {
        Route::get('/calendarizacion/techos', 'getIndex')->name('index_techos');
        Route::get('/calendarizacion/techos/data', 'getTechos')->name('getTechos');
    });
?>