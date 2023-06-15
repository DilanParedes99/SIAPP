let actividades = [];
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
        /*      $.ajax({
                 type: "GET",
                 url: 'grupos',
                 dataType: "JSON"
             }).done(function (data) {
                 var par = $('#id_grupo');
                 par.html('');
                 par.append(new Option("-- Selecciona Perfil --", ""));
                 document.getElementById("id_grupo").options[0].disabled = true;
                 $.each(data, function (i, val) {
                     par.append(new Option(data[i].nombre_grupo, data[i].id));
                 });
             }); */
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

        inps = [
            'id_user',
            'username',
            'nombre',
            'p_apellido',
            's_apellido',
            'email',
            'password',
            'in_pass_conf',
            'in_celular',
            'id_grupo'
        ];
        inps.forEach(e => {
            $('#' + e).val('').removeClass('has-error').removeClass('d-block');
            $('#' + e + '-error').text("").removeClass('has-error').removeClass('d-block');

        });
        $("#id_grupo").find('option').remove();
        dao.getAnio();
        $('.form-group').removeClass('has-error');
        $("#id_grupo").show();
        $("#labelGrupo").show();
        $("#label_idGrupo").text("").hide();

    },
    eliminaFondo: function (i) {
        document.getElementById(i).outerHTML=""
    }
};
var init = {
    validateCreate: function (form) {
        _gen.validate(form, {
            rules: {
                username: { required: true },
                nombre: { required: true },
                p_apellido: { required: true },
                s_apellido: { required: true },
                email: { required: true, email: true },
                password: { required: true },
                in_pass_conf: { required: true, equalTo: "#password" },
                in_celular: {
                    required: true,
                    phoneUS: true
                },
                id_grupo: { required: true }

            },
            messages: {
                username: { required: "Este campo es requerido" },
                nombre: { required: "Este campo es requerido" },
                p_apellido: { required: "Este campo es requerido" },
                s_apellido: { required: "Este campo es requerido" },
                email: { required: "Este campo es requerido" },
                password: { required: "Este campo es requerido" },
                in_pass_conf: { required: "Este campo es requerido" },
                in_celular: { required: "Este campo es requerido" },
                id_grupo: { required: "Este campo es requerido" }

            }
        });
    },
};

$(document).ready(function () {
    getData();
    dao.getAnio();

    $('#fondo_filter').selectpicker({ search: true });
    $('#upp_filter').selectpicker({ search: true });

    $('#btnNew').on('click',function (e) {
        e.preventDefault();
        anio = new Date().getFullYear() + 1;
        $('#anioOpt').val(anio)
    })
    $('#agregar_fondo').on('click', function (e){
        e.preventDefault()
        selectFondo = ''

        table = document.getElementById('fondos')
        table_lenght = (table.rows.length)

        $.ajax({
            type: "GET",
            url: '/calendarizacion/techos/get-fondos',
            dataType: "JSON"
        }).done(function (data) {
            selectFondo = '<select class="form-control filters" id="fondo_'+table_lenght+'" name="fondo_'+table_lenght+'" placeholder="Seleccione un fondo">';
            selectFondo += '<option value="0" selected>Seleccione fondo</option>';
            data.forEach(function(item){
                selectFondo += '<option value="'+item.clv_fondo_ramo+'" >'+item.clv_fondo_ramo+" - "+item.fondo_ramo+'</option>'
            });
            selectFondo += '</select>';
        });

        row = table.insertRow(table_lenght).outerHTML='<tr id="'+table_lenght+'">\n' +
            '<td>' +
            '       <select class="form-control filters" id="tipo_'+table_lenght+'" name="tipo_'+table_lenght+'" placeholder="Seleccione una tipo">\n' +
            '           <option value="">Seleccione un tipo</option>\n' +
            '           <option value="Operativo">Operativo</option>\n' +
            '           <option value="RH">RH</option>\n' +
            '       </select>' +
            '</td>\n' +
            '<td>' +
            selectFondo
            +
            '</td>\n' +
            '<td>' +
            '<input type="number" class="form-control" id="presupuesto_'+table_lenght+'" name="presupuesto_'+table_lenght+'" placeholder="$1000">' +
            '</td>\n' +
            '  <td><input type="number" value="2024" class="form-control" id="ejercicio_'+table_lenght+'" name="ejercicio_'+table_lenght+'" disabled placeholder="2024"></td>\n' +
            '<td>' +
            '   <input type="button" value="Eliminar" onclick="dao.eliminaFondo('+table_lenght+')" title="Eliminar fondo" class="btn btn-danger delete" >' +
            '</td>\n' +
            '</tr>'

    })

    $('#exampleModal').modal({
        backdrop: 'static',
        keyboard: false
    })

    $("#email").change(function () {
        var regex = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
        regex.test($("#email").val());
        var text = "Ingresa un correo electrónico válido";
        if (regex.test($("#email").val())) {
            $('#email-error').text("").removeClass('d-block').removeClass('has-error');
            $('#email').removeClass('has-error').removeClass('d-block');
        } else {
            $('#email-error').text(text).addClass('d-block').addClass('has-error');
            $('#email').addClass('has-error').addClass('d-block');
        }
    });
    $("#in_celular").change(function () {
        var regex = /^[a-zA-Z ]+$/;
        var bol = regex.test($("#in_celular").val());
        if ($("#in_celular").val() == '') {
            $('#in_celular-error').text("Este campo es requerido").addClass('d-block').addClass('has-error');
            $('#in_celular').addClass('d-block').addClass('has-error');
        }
        else {
            if (bol != true) {
                if ($("#in_celular").val().length != 14) {
                    $('#in_celular-error').text("El Telefono debe contar con 10 digitos").addClass('d-block').addClass('has-error');
                    $('#in_celular').addClass('d-block').addClass('has-error');
                } else {
                    $('#in_celular-error').text("").removeClass('d-block').removeClass('has-error');
                    $('#in_celular').removeClass('d-block').removeClass('has-error');
                }
            } else {
                $('#in_celular-error').text("El telefono no puede llevar letras").addClass('d-block').addClass('has-error');
                $('#in_celular').addClass('d-block').addClass('has-error');
            }
        }
    });
    $('#in_celular').mask('00-00-00-00-00');
});

$('#btnSave').click(function (e) {
    e.preventDefault();
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
        $('#cerrar').trigger('click');
        /*Swal.fire({
            icon: 'success',
            title: 'Your work has been saved',
            showConfirmButton: false,
            timer: 1500
        });*/
        dao.limpiarFormularioCrear();
        getData();
    });
});