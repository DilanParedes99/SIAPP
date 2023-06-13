<?php 	
	use App\Http\Controllers\Administracion\SistemasController;
	use App\Http\Controllers\Administracion\UsuarioController;
	use App\Http\Controllers\Administracion\GrupoController;
	use App\Http\Controllers\Administracion\PermisoController;
	use App\Http\Controllers\Administracion\BitacoraController;
	use App\Http\Controllers\ReporteController;

	Route::controller(SistemasController::class)->group(function () {
		Route::get('/sistemas/panel', 'getPanel');
	});

	Route::controller(UsuarioController::class)->group(function () {
		Route::get('adm-usuarios', 'getIndex')->name('index_usuario');
		Route::get('adm-usuarios/data', 'getData')->name('getdata');
		Route::post('adm-usuarios/status', 'postStatus');
		Route::get('adm-usuarios/create', 'getCreate');
		Route::post('adm-usuarios/store', 'postStore');
		Route::get('adm-usuarios/update/{id?}', 'getUpdate');
		Route::post('adm-usuarios/put-usuario', 'postUpdate');
		Route::get('adm-usuarios/grupos/{idUsuario?}', 'getGrupos');
		Route::post('adm-usuarios/eliminar', 'postDelete');
		Route::post('adm-usuarios/grupos', 'postGrupos');
		Route::get('grupos', 'grupos');
	});

	Route::controller(GrupoController::class)->group(function () {
		Route::get('/adm-grupos', 'getIndex')->name('index_grupo');
		Route::post('adm-grupos/dataGroups', 'getData')->name('getGroups');
		Route::get('/adm-grupos/create', 'getCreate');
		Route::post('/adm-grupos/store', 'postStore')->name('postStore');
		Route::get('/adm-grupos/update/{id?}', 'getGrupo');
		Route::post('/adm-grupos/put-grupo', 'postUpdate')->name('postUpdate');
		Route::post('/adm-grupos/eliminar', 'postDelete')->name('postDelete');
	});

	Route::controller(PermisoController::class)->group(function () {
		Route::get('/adm-permisos/grupo/{idGrupo?}', 'getGrupo');
		Route::post('/adm-permisos/asigna', 'postAsigna');
		Route::post('/adm-permisos/remueve', 'postRemueve');
		Route::post('/adm-permisos/sasigna', 'postSasigna');
		Route::post('/adm-permisos/sremueve', 'postSremueve');
		Route::post('/adm-permisos/masigna', 'postMasigna');
		Route::post('/adm-permisos/mremueve', 'postMremueve');
		Route::post('/adm-permisos/all-permission', 'postAllPermission');
	});

	Route::controller(BitacoraController::class)->group(function () {
		Route::get('/adm-bitacora', 'getIndex');
		Route::post('/adm-bitacora/data/{fecha?}', 'getBitacora')->name('getBitacora');
	});

	Route::controller(ReporteController::class)->group(function(){
		Route::get('/Reportes/ley-planeacion','indexPlaneacion')->name('index_planeacion');
		// Route::get('/Reportes/ley-planeacion','indexAdministrativo')->name('index_administrativo');
		// Route::get('/Reportes/administrativo','indexAdministrativo')->name('index_administrativo');

		Route::post('/Reportes/data-fecha-corte/{ejercicio?}','getFechaCorte')->name('get_fecha_corte');

		Route::post('/Reportes/get-ley-planeacion','reportePlaneacion')->name('get_reporte_planeacion');
		Route::post('/Reportes/get-administrativo{nombre}','reporteAdministrativo')->name('get_reporte_administrativo');

		Route::post('/Reportes/download/{nombre}', 'downloadReport')->name('downloadReport');
		// Route::post('/download/{name?}-{anio?}-{date?}', 'downloadReport')->name('downloadReport');
	});
?>