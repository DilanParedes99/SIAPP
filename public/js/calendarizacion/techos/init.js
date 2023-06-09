var total = 0;
var actividades = [];
var ultimo = 1;
var dao = {
    setStatus: function (id, estatus) {
        Swal.fire({
            title: 'Confirmar Activación/Desactivación',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "/adm-usuarios/status",
                    data: {
                        id: id,
                        estatus: estatus
                    }
                }).done(function (data) {
                    if (data != "done") {
                        Swal.fire(
                            'Error!',
                            'Hubo un problema al querer realizar la acción, contacte a soporte',
                            'Error'
                        );
                    } else {
                        Swal.fire(
                            'Éxito!',
                            'La acción se ha realizado correctamente',
                            'success'
                        );
                        getData();
                    }
                });
            }
        });
    },
    getAnio: function () {
        let anio = [2022, 2023, 2024, 2025];
        var par = $('#anio_filter');
        par.html('');
        par.append(new Option("-- Año--", ""));
        document.getElementById("anio_filter").options[0].disabled = true;
        $.each(anio, function (i, val) {
            par.append(new Option(anio[i], anio[i]));
        });

        var ex = $('#anio_filter_export');
        ex.html('');
        ex.append(new Option("Todos", 0));
        document.getElementById("anio_filter_export").options[0].disabled = false;
        $.each(anio, function (i, val) {
            ex.append(new Option(anio[i], anio[i]));
        });
        
        var pdf = $('#anio_filter_pdf');
        pdf.html('');
        pdf.append(new Option("Todos", 0));
        document.getElementById("anio_filter_pdf").options[0].disabled = false;
        $.each(anio, function (i, val) {
            pdf.append(new Option(anio[i], anio[i]));
        });

        var pdf = $('#anio_filter_presupuestos');
        pdf.html('');
        pdf.append(new Option("Todos", 0));
        document.getElementById("anio_filter_presupuestos").options[0].disabled = false;
        $.each(anio, function (i, val) {
            pdf.append(new Option(anio[i], anio[i]));
        });
    },
    limpiarFormularioCrear: function () {
        $('#fondos').empty()
        $('#fondos').append('<thead>\n' +
            '     <tr class="colorMorado">\n' +
            '         <th>Tipo</th>\n' +
            '         <th>ID Fondo</th>\n' +
            '         <th>Monto</th>\n' +
            '         <th>Ejercicio</th>\n' +
            '         <th>Acciones</th>\n' +
            '     </tr>\n' +
            ' </thead>')

        $('#uppSelected').removeClass('is-invalid')
        $('#uppSelected').val(0)
        $('#total-presupuesto').val("")
        ultimo=1;
    },
    eliminaFondo: function (i) {
        document.getElementById(i).outerHTML=""
        totalP();
    },
    cargaMasiva: function () {
        var form = $('#importPlantilla')[0];
        var data = new FormData(form);
        $.ajax({
            type: "POST",
            url: '/import-Plantilla',
            data: data,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
        }).done(function (response) {
            console.log(response);
            if (response != 'done') {
              Swal.fire(
                  {
                      showCloseButton: true,
                      title: response.title,
                      text: response.text,
                      icon: response.icon,
                  }
              );
            }else{
                Swal.fire({
                    title: 'Guardado',
                    text: "Se guardo correctamente",
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                  }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href="/calendarizacion/techos";
                    }
                  });
              
            }
        }).fail(function (response){
            console.log(response);
            Swal.fire(
                {
                    showCloseButton: true,
                    title: response.title,
                    text: response.text,
                    icon: response.icon,
                }
            );
        })
    },
    filtroPresupuesto: function (i){
        var tecla = event.key;
        if (['.','e','-'].includes(tecla)){
            event.preventDefault()
        }
    },
    validCero: function (i) { 
        if($('#presupuesto_'+i).val() == 0){
            $("#frm_create_techo").find('#presupuesto_'+i).addClass('is-invalid');
        }else{
            $('input').removeClass('is-invalid');
        }
    },
};
var init = {
    validateCreate: function (form) {
        _gen.validate(form, {
            rules: {
                tipo: { required: true },
                fondo: { required: true },
                presupuesto: { required: true },
            },
            messages: {
                tipo: { required: "Este campo es requerido" },
                fondo: { required: "Este campo es requerido" },
                presupuesto: { required: "Este campo es requerido" },
            }
        });
    },
    validateFile: function(form){
        _gen.validate(form, {
            rules: {
                cmFile: { required: true },
            },
            messages: {
                cmFile: { required: "Este campo es requerido" },
            }
        });
    },
};

$(document).ready(function () {
    getData();
    dao.getAnio();
    $('#cmFile').val(null);
    $('#fondo_filter').selectpicker({ search: true });
    $('#upp_filter').selectpicker({ search: true });

    $('#btnNew').on('click',function (e) {
        e.preventDefault();
        anio = new Date().getFullYear() + 1;
        $('#anioOpt').val(anio)
    })
    $('#agregar_fondo').on('click', function (e){
        e.preventDefault()

        if($('#uppSelected').val() != 0){
            selectFondo = ''
            $('#uppSelected').removeClass('is-invalid')
            table = document.getElementById('fondos')
            table_lenght = (table.rows.length)

            $.ajax({
                type: "GET",
                url: '/calendarizacion/techos/get-fondos',
                dataType: "JSON"
            }).done(function (data) {
                selectFondo = '<select class="form-control filters" id="fondo_'+ultimo+'" name="fondo_'+ultimo+'" placeholder="Seleccione un fondo" required>';
                selectFondo += '<option value="">Seleccione fondo</option>';
                data.forEach(function(item){
                    selectFondo += '<option value="'+item.clv_fondo_ramo+'" >'+item.clv_fondo_ramo+" - "+item.fondo_ramo+'</option>'
                });
                selectFondo += '</select>';
            });

            row = table.insertRow(table_lenght).outerHTML='<tr id="'+ultimo+'">\n' +
                '<td>' +
                '       <select class="form-control filters" id="tipo_'+ultimo+'" name="tipo_'+ultimo+'" placeholder="Seleccione una tipo" required>\n' +
                '           <option value="">Seleccione un tipo</option>\n'+
                '           <option value="Operativo">Operativo</option>\n'+
                '           <option value="RH">RH</option>\n' +
                '       </select>' +
                '</td>\n' +
                '<td>'
                    + selectFondo +
                '</td>\n' +
                '<td>' +
                '<input type="number" class="form-control totales" min="0" id="presupuesto_'+ultimo+'" name="presupuesto_'+ultimo+'" placeholder="$0" onkeydown="dao.filtroPresupuesto()" onkeyup="dao.validCero('+ultimo+')" onchange="totalP()" required>' +
                '</td>\n' +
                '  <td><input type="number" value="2024" class="form-control" id="ejercicio_'+ultimo+'" name="ejercicio_'+ultimo+'" disabled placeholder="2024"></td>\n' +
                '<td>' +
                '   <input type="button" value="Eliminar" onclick="dao.eliminaFondo('+ultimo+')" title="Eliminar fondo" class="btn btn-danger delete" >' +
                '</td>\n' +
                '</tr>';
            ultimo ++;
            totalP()
        }else{
            $('#uppSelected').addClass('is-invalid')
        }
    });
    $('#btnCarga').on('click', function () {
        $('#carga').modal('show');
    });
    $('#carga').modal({
        backdrop: 'static',
        keyboard: false
    });
    $('#btnCancelar').click(function () {
        $("#carga").modal('hide');
        $('#cmFile').val(null);
    });
    $('#btnClose').click(function () {
        $("#carga").modal('hide');
        $('#cmFile').val(null);
    });
    $('#btnSaveM').click(function () {
        if ($('#importPlantilla').valid()) {
            dao.cargaMasiva();
        }
    });

    $('#btnExport').on('click',function(){
        document.all["formExport"].submit();
        $('#exportExcel').modal('hide')
    })
    
    $('#btnExportPDF').on('click',function(){
        document.all["formExportPDF"].submit();
        $('#exportPDF').modal('hide')
    })

    $('#btnExportPresupuestos').on('click',function(){
        document.all["formExportPresupuestos"].submit();
        $('#exportPresupuestos').modal('hide')
    })

   /*  $('#btnExport').on('click', function(e){
        console.log("Export Excel")

        $.ajax({
            type: "GET",
            url: '/calendarizacion/techos/export-excel',
            dataType: "JSON"
        }).done(function (data) {
            console.log(data)
        });
    }) */
});

function totalP(){
    total = 0;

    for(let l=1;l<ultimo;l++){
        if(!($('#presupuesto_'+l).val() === undefined)){
            if(isNaN(parseInt($('#presupuesto_'+l).val()))) {
                total += 0;
            }else{
                total += parseInt($('#presupuesto_'+l).val());
            }
        }
    }
    $('#total-presupuesto').val('$ '+total);
}

function verificarCeros(){
    for(let l=1;l<=ultimo;l++){
        if( $('#presupuesto_'+l).hasClass('is-invalid') == true || $('#presupuesto_'+l).val() == 0){
            $('#presupuesto_'+l).addClass('is-invalid')
            return false; 
        }
    }
    return true;
}

$('#btnSave').click(function (e) {
    e.preventDefault();
    const verificado = verificarCeros();

    if(verificado == true){
        $('input').removeClass('is-invalid');
        $('select').removeClass('is-invalid');

        var form = $('#frm_create_techo')[0];
        var data = new FormData(form);


        $.ajax({
            type: "POST",
            url: '/calendarizacion/techos/add-techo',
            data: data,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000
        }).done(function (response) {
            console.log(response.status)
            if(response.status == 200){
                $('#cerrar').trigger('click');
                Swal.fire({
                    icon: 'success',
                    title: 'Techo financiero creado con éxito',
                    showConfirmButton: false,
                    timer: 1500
                });
                dao.limpiarFormularioCrear();
                getData();
            }else if(response.status == 400){
                Swal.fire({
                    icon: 'warning',
                    title: 'Datos faltantes',
                    showConfirmButton: true
                });
            }else if(response.status == 'Repetidos'){

                $("#frm_create_techo").find("#"+response.etiqueta[0]).addClass('is-invalid');
                $("#frm_create_techo").find("#"+response.etiqueta[1]).addClass('is-invalid');

                Swal.fire({
                    icon: 'warning',
                    title: 'Hay fondos repetidos',
                    showConfirmButton: true
                });
            }
            else{
                Swal.fire({
                    icon: 'error',
                    title: 'Hubo un error',
                    showConfirmButton: true
                });
            }
        }).fail(function (error) {
            let arr = Object.keys(error.responseJSON.errors)
            arr.forEach(function (item) {
                $("#frm_create_techo").find("#"+item).addClass('is-invalid');
            })
            Swal.fire({
                icon: 'warning',
                title: 'Hubo un error, campos vacíos',
                showConfirmButton: true
            });

        });
    }else{
        Swal.fire({
            icon: 'warning',
            title: 'No se puede registrar fondo con un presupuesto de $0',
            showConfirmButton: true
        });
    }
});