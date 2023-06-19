var dao = {
    getData : function(){
		$.ajax({
			type : "GET",
			url : "/calendarizacion/claves-get",
			dataType : "json",
			//data : {},
		}).done(function(_data){
			console.log("🚀 ~ file: init.js:9 ~ _data:", _data)
			let data = [];
			for (let index = 0; index < _data.length; index++) {
				if (_data[index].clave_presupuestal && _data[index].clave_presupuestal != '') {
					const clasificacionAdmin = _data[index].clave_presupuestal.substring(0,5);
					const centroGestor = _data[index].clave_presupuestal.substring(5,21);
					const areaFuncional = _data[index].clave_presupuestal.substring(21,37);
					const periodoPre = _data[index].clave_presupuestal.substring(37,43);
					const posicionPre = _data[index].clave_presupuestal.substring(43,49);
					const fondo = _data[index].clave_presupuestal.substring(49,58);
					const proyectoObra = _data[index].clave_presupuestal.substring(58,64);
					let row = _data[index].clave +" "+'-'+" "+ _data[index].descripcion +" "+'-'+" "+'Presupuesto calendarizado: ';
					let totalByClave = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(_data[index].totalByClave); 
					let id = _data[index].id;
					data.push({'clasificacionAdmin':clasificacionAdmin, 'centroGestor':centroGestor,'areaFuncional':areaFuncional,'periodoPre': periodoPre, 'posicionPre':posicionPre,'fondo':fondo,'proyectoObra': proyectoObra,'row': row,'totalByClave': totalByClave});
				}
				
			}
			_table = $("#claves");
			_columns = [
				{"aTargets" : [0], "mData" : 'clasificacionAdmin'},
				{"aTargets" : [1], "mData" : "centroGestor"},
				{"aTargets" : [2], "mData" : "areaFuncional"},
				{"aTargets" : [3], "mData" : "periodoPre"},
				{"aTargets" : [4], "mData" : "posicionPre"},
				{"aTargets" : [5], "mData" : "fondo"},
				{"aTargets" : [6], "mData" : "proyectoObra"},
				{"aTargets" : [7], "mData" : "totalByClave"},
				{"aTargets" : [8], "mData" : function(o){
					return '<a data-toggle="tooltip" title="Modificar" class="btn btn-sm btn-success" href="#/'+o.id+'">' + '<i class="fa fa-pencil"></i></a>&nbsp;'
					+  '<a data-toggle="tooltip" title="Ver" class="btn btn-sm btn-primary">' + '<i class="fa fa-eye"></i></a>&nbsp;'
					+  '<a data-toggle="tooltip" title="Eliminar" class="btn btn-sm btn-danger">' + '<i class="fa fa-trash"></i></a>&nbsp;';
				}},
				
			];
			_gen.setTableScrollGroupBy(_table, _columns, data);
		});
	},
	getRegiones : function(id){
        $.ajax({
          type : "GET",
          url: '/cat-regiones',
          dataType : "JSON"
        }).done(function(data){
          var par = $('#sel_region');
          par.html('');
          par.append(new Option("-- Selecciona una Region --", ""));
          $.each(data, function(i, val){
            par.append(new Option(data[i].descripcion, data[i].clave + '-'+ data[i].region_id));
          });
          //par.select2().select2("val", id);
        });
    },
	getMunicipiosByRegion : function(id){
        $.ajax({
          	type : "get",
          	url: '/cat-municipios/'+ id,
        }).done(function(data){
          var par = $('#sel_municipio');
          par.html('');
          par.append(new Option("-- Selecciona un Municipio --", ""));
          $.each(data, function(i, val){
            par.append(new Option(data[i].descripcion, data[i].clave + '-' + data[i].municipio_id));
          });
          //par.select2().select2("val", id);
        });
    },
	getLocalidadByMunicipio : function(id){
        $.ajax({
          	type : "get",
          	url: '/cat-localidad/'+ id,
        }).done(function(data){
          var par = $('#sel_localidad');
          par.html('');
          par.append(new Option("-- Selecciona una Localidad --", ""));
          $.each(data, function(i, val){
            par.append(new Option(data[i].descripcion, data[i].clave));
          });
          //par.select2().select2("val", id);
        });
    },
	getUpp : function(id){
		console.log('funcion UPPs')
        $.ajax({
          	type : "get",
          	url: '/cat-upp',
        }).done(function(data){
          var par = $('#sel_upp');
          par.html('');
          par.append(new Option("-- Selecciona una Unidad Programática --", ""));
          $.each(data, function(i, val){
            par.append(new Option(data[i].descripcion, data[i].clave + '-' +data[i].upp_id));
          });
          //par.select2().select2("val", id);
        });
    },
	getUninadResponsableByUpp : function(id){
        $.ajax({
          	type : "get",
          	url: '/cat-unidad-responsable/'+ id,
        }).done(function(data){
          var par = $('#sel_unidad_res');
          par.html('');
          par.append(new Option("-- Selecciona una Unidad Responsable --", ""));
          $.each(data, function(i, val){
            par.append(new Option(data[i].descripcion, data[i].clave + '-' + data[i].ur_id));
          });
          //par.select2().select2("val", id);
        });
    },
	getProgramaPresupuestarioByur : function(id){
        $.ajax({
          	type : "get",
          	url: '/cat-programa-presupuestario/'+ id,
        }).done(function(data){
          var par = $('#sel_programa');
          par.html('');
          par.append(new Option("-- Selecciona un Programa Presupuestario --", ""));
          $.each(data, function(i, val){
            par.append(new Option(data[i].descripcion, data[i].clave + '-'+ data[i].programa_presupuestario_id));
          });
          //par.select2().select2("val", id);
        });
    },
	getSubProgramaByProgramaId : function(id){
        $.ajax({
          	type : "get",
          	url: '/cat-subprograma-presupuesto/'+ id,
        }).done(function(data){
          var par = $('#sel_sub_programa');
          par.html('');
          par.append(new Option("-- Selecciona un Sub Programa Presupuestario --", ""));
          $.each(data, function(i, val){
            par.append(new Option(data[i].descripcion, data[i].clave + '-' + data[i].subprograma_presupuestario_id));
          });
          //par.select2().select2("val", id);
        });
    },
	getProyectoBySubPrograma : function(id){
        $.ajax({
          	type : "get",
          	url: '/cat-proyecyo/'+ id,
        }).done(function(data){
          var par = $('#sel_proyecto');
          par.html('');
          par.append(new Option("-- Selecciona un Proyecto --", ""));
          $.each(data, function(i, val){
            par.append(new Option(data[i].descripcion, data[i].clave ));
          });
          //par.select2().select2("val", id);
        });
    },
	getLineaDeAccionByUpp : function(id){
        $.ajax({
          	type : "get",
          	url: '/cat-linea-accion/'+ id,
        }).done(function(data){
          var par = $('#sel_linea');
          par.html('');
          par.append(new Option("-- Selecciona una Linea de Acción --", ""));
          $.each(data, function(i, val){
            par.append(new Option(data[i].descripcion, data[i].clave ));
          });
          //par.select2().select2("val", id);
        });
    },
	getPartidaByUpp : function(id){
        $.ajax({
          	type : "get",
          	url: '/cat-partidas/'+ id,
        }).done(function(data){
          var par = $('#sel_partida');
          par.html('');
          par.append(new Option("-- Selecciona una Partida --", ""));
          $.each(data, function(i, val){
            par.append(new Option(data[i].descripcion, data[i].clave ));
          });
          //par.select2().select2("val", id);
        });
    },
  getPresupuesAsignado : function(){
    $.ajax({
      type: 'get',
      url: '/get-presupuesto-asignado'
    }).done(function(response){
      console.log("presupuesto: ", response);
      // let totalAsignado = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(response[0].totalAsignado);
      // $('#asignadoUpp').val(totalAsignado);
    });

  },
   getTabla: function(){
		$.ajax({
			type : "GET",
			url : "/ver-detalle",
			dataType : "json"
        }).done(function (data) {
			table = $("#detalleClave");
            let clase;
            for (let i = 0; i < data.length; i++) {
                if(i<=11)
                        clase='centro-gestor';
                if(i>11 && i<=21)
                        clase='area-funcional';
                if(i==22)
                        clase='periodo-presupuestal';
                if(i>22 && i<=27)
                        clase='clasificacion-economica';
                if(i>27 && i<=33)
                        clase='fondo';
                if(i==34)
                        clase='inversion';
                   
                $("#detalleClave").append('<tr><td class="col-md-4 text-left">' + data[i][0]+'</td><td class="col-md-1 '+clase+'">' + data[i][1]+'</td><td class="col-md-7 text-left">' + data[i][2]+'</td></tr>');
                
            }
		});
	}
};


$(document).ready(function(){
  dao.getTabla();
	$('#modalNewClave').modal({
        backdrop: 'static',
        keyboard: false
    });
	//$('.select2').select2();
	$('#sel_region').change(function(e){
		e.preventDefault();
		let val = this.value;
		let id = val.substring(3,5);
    document.getElementById("region").innerHTML = id;
		dao.getMunicipiosByRegion(id);
	});
	$('#sel_municipio').change(function(e){
		e.preventDefault();
		let val = this.value;
		let id = val.substring(4);
    document.getElementById("municipio").innerHTML = id;
		dao.getLocalidadByMunicipio(id);
	});
  $('#sel_localidad').change(function (e) {
    let val = this.value;
    console.log('localidad',val);
    document.getElementById('localidad').innerHTML = val;
  });
	$('#sel_upp').change(function(e){
		e.preventDefault();
		let val = this.value;
		console.log("🚀 ~ file: init.js:220 ~ $ ~ val UPP:", val)
    let clave = val.substring(0,3);
		let id = val.substring(4);
		console.log("🚀 ~ file: init.js:213 ~ $ ~ id:", id)
    document.getElementById('upp').innerHTML = clave;
		dao.getUninadResponsableByUpp(id);
		dao.getPartidaByUpp(id);
	});
	$('#sel_unidad_res').change(function(e){
		e.preventDefault();
		let val = this.value;
		let id = val.substring(3);
    let clave = val.substring(0,2);
    document.getElementById('ur').innerHTML = clave;
		dao.getProgramaPresupuestarioByur(id);
		dao.getLineaDeAccionByUpp(id);
	});
	$('#sel_programa').change(function(e){
		e.preventDefault();
		let val = this.value;
		console.log("🚀 ~ file: init.js:240 ~ $ ~ val programa:", val)
		let id = val.substring(3);
    let clave = val.substring(0,2);
    document.getElementById('programaPre').innerHTML = clave;
		dao.getSubProgramaByProgramaId(id);
	});
	$('#sel_sub_programa').change(function(e){
		e.preventDefault();
		let val = this.value;
		console.log("🚀 ~ file: init.js:249 ~ $ ~ val subProgama:", val)
		let id = val.substring(4);
    console.log("🚀 ~ file: init.js:251 ~ $ ~ id subPrograma:", id)
    let clave = val.substring(0,3);
    document.getElementById('subPrograma').innerHTML = clave;
		dao.getProyectoBySubPrograma(id);
	});
  $('#sel_proyecto').change(function (e) {
    e.preventDefault();
    let val = this.value;
    // console.log("🚀 ~ file: init.js:257 ~ val proyecto:", val)
    // let clave = val.substring(0,3);
    document.getElementById('proyectoPre').innerHTML = val;
  });
  $('#sel_linea').change(function (e) {
    e.preventDefault();
    let clave = this.value;
    console.log("🚀 ~ file: init.js:265 ~ clave: linea Accion", clave)
    document.getElementById('lineaAccion').innerHTML = clave;
  });
  $('#sel_periodo').change(function (e) {
    e.preventDefault();
    let clave = this.value;
    console.log("🚀 ~ file: init.js:265 ~ clave: linea Accion", clave)
    document.getElementById('mesAfectacion').innerHTML = clave;
  });
  $('#sel_partida').change(function (e) {
    e.preventDefault();
    let clave = this.value;
    console.log("🚀 ~ file: init.js:265 ~ clave: partida", clave)
    document.getElementById('partidaEpecifica').innerHTML = clave;
  });

});