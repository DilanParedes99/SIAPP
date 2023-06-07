<?php 	
	use App\Http\Controllers\Calendarización\MetasController;
	use App\Http\Controllers\Calendarización\CalendarizacionCargaMasivaController;

	Route::controller(MetasController::class)->group(function () {
		Route::get('/calendarizacion/metas', 'getIndex')->name('index_metas');
	 	Route::get('/calendarizacion/data', 'getMetas')->name('getMetas');
        Route::get('/calendarizacion/proyecto', 'getProyecto')->name('proyecto');

	});

	Route::controller(CalendarizacionCargaMasivaController::class)->group(function () {
			//ruta temporal
    Route::get('/calendarizacion/get-plantilla', 'getExcel')->name('get-plantilla');
	});

?>