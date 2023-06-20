<?php 	

    use App\Http\Controllers\Calendarizacion\ClavePreController;
	use App\Http\Controllers\Calendarización\MetasController;
	use App\Http\Controllers\Calendarización\TechosController;
	use App\Http\Controllers\Calendarización\CalendarizacionCargaMasivaController;

	Route::controller(MetasController::class)->group(function () {
		Route::get('/calendarizacion/metas', 'getIndex')->name('index_metas');
	 	Route::get('/calendarizacion/data', 'getMetas')->name('getMetas');
        Route::get('/calendarizacion/proyecto', 'getProyecto')->name('proyecto');
	});

    Route::controller(TechosController::class)->group(function () {
        Route::get('/calendarizacion/techos', 'getIndex')->name('index_techos');
    });
	Route::controller(ClavePreController::class)->group(function () {
		Route::get('/calendarizacion/claves', 'getPanel');
		Route::get('/calendarizacion-claves-create', 'getCreate');
		Route::get('/calendarizacion/claves-get', 'getClaves');
		Route::get('/cat-regiones', 'getRegiones');
		Route::get('/cat-municipios/{id?}', 'getMunicipios');
		Route::get('/cat-localidad/{id?}', 'getLocalidades');		
		Route::get('/cat-upp', 'getUpp');
		Route::get('/cat-unidad-responsable/{id?}', 'getUnidadesResponsables');
		Route::get('/cat-programa-presupuestario/{upp?}/{id?}', 'getProgramaPresupuestarios');
		Route::get('/cat-subprograma-presupuesto/{ur?}/{id?}', 'getSubProgramas');
		Route::get('/cat-proyecyo/{programa?}/{id?}', 'getProyectos');
		Route::get('/cat-linea-accion/{uppId?}/{id?}', 'getLineaAccion');
		Route::get('/get-presupuesto-asignado', 'getPresupuestoAsignado');
		Route::get('/calendarizacion-claves-presupuesto-fondo', 'getPanelPresupuestoFondo');
		Route::post('/calendarizacion-eliminar-clave', 'postEliminarClave');
		Route::post('/calendarizacion-guardar-clave', 'postGuardarClave');
		Route::get('/calendarizacion/get-calendarizacion-panel', 'getPanelCalendarizacion');
		Route::get('/cat-subSecretaria/{upp?}/{ur?}', 'getSubSecretaria');
		Route::get('/cat-area-funcional/{uppId?}/{id?}', 'getAreaFuncional');
		Route::get('/cat-partidas', 'getPartidas');
		Route::get('/cat-fondos/{id?}', 'getFondos');
		Route::get('/cat-clasificacion-administrativa/{upp?}/{ur?}', 'getClasificacionAdmin');
		Route::get('/presupuesto-upp-asignado/{upp?}/{fonfo?}/{subPrograma?}', 'getPresupuestoPorUpp');
		Route::get('/ver-detalle', 'getConceptosClave')->name('detalle');

		
		
		
		
		
	});

	Route::controller(CalendarizacionCargaMasivaController::class)->group(function () {
		Route::get('/calendarizacion/get-plantilla', 'getExcel')->name('getplantilla');
		Route::post('/calendarizacion/load-Data-Plantilla', 'loadDataPlantilla')->name('load_data_plantilla');

    });
	

?>

	
	
