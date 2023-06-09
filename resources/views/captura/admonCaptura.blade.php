<?php
$titleDesc = 'Administración de Captura';

?>

@extends('layouts.app')
@section('content')
    <div class="container w-100 p-4">
        <header>
            <h1 class="fw-bold text-center">{{ $titleDesc }} Ejercicio {{ $anio }}</h1>
            <div class="rounded-pill" style="height: .5em; background-color: rgb(37, 150, 190)"></div>
            <form action="{{ route('claves_presupuestarias') }}" id="buscarFormA" name="buscarFormA" method="post"></form>
            <form action="{{ route('metas_actividades') }}" id="buscarFormB" name="buscarFormB" method="post"></form>
        </header>

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <h4>{{ session()->get('success') }}</h4>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
         @endif

         @if($errors->any())
         <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
             <h4>{{$errors->first()}}</h4>
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
         </div>
         @endif

        <br>
        {{-- Form para cambiar los select --}}
        <form id="form" method="POST">
            @csrf
            <div class="col-md-10 col-sm-12 d-md-flex">
                <div class="col-sm-3 col-md-3 col-lg-2 text-md-end mt-md-1">
                    <label for="estatus_filter" class="form-label fw-bold">Estatus:</label>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-2">
                    <select class="form-control filters filters_estatus" id="estatus_filter" name="estatus_filter"
                        autocomplete="estatus_filter">
                        <option value="">Todos</option>
                        <option value="Abierto">Abierto</option>
                        <option value="Cerrado">Cerrado</option>
                    </select>
                </div>
            </div>
        </form>

        {{-- botón modal --}}
        <div class="d-flex flex-wrap justify-content-md-end justify-content-center mt-lg-0 mt-3">
            <button id="btnAperturaCierre" name="btnAperturaCierre" type="button" class="btn btn-light btn-sm btn-labeled me-3 colorMorado" title="Apertura y cierre de captura" data-target="#aperturaCierreModal" data-backdrop="static"
            data-keyboard="false" data-toggle="modal">
                <span class="btn-label"><i class="fa fa-rotate-right text-light fs-5 align-middle p-1"></i></span>
                <span class="d-lg-inline align-middle">Apertura y cierre de captura</span> 
            </button>
        </div>

        {{-- Llamada al modal --}}
        @include('captura.modalAperturaCierre')
        
        <br>
        
        <ul class="nav nav-tabs " id="tabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link textoMorado active" role="tab" type="button" id="clavePresupuestaria_tab"
                    data-bs-toggle="tab" data-bs-target="#clavePresupuestaria" aria-controls="clavePresupuestaria"
                    aria-selected="true">Claves presupuestarias</button>
            </li>
            <li class="nav-item">
                <button class="nav-link textoMorado" role="tab" type="button" id="metasActividad_tab"
                    data-bs-toggle="tab" data-bs-target="#metasActividad" aria-controls="metasActividad"
                    aria-selected="false">Metas de actividades</button>
            </li>
        </ul>

        <div class="tab-content">
            {{-- Claves presupuestarias A --}}
            <div class="tab-pane active" id="clavePresupuestaria" role="tabpanel" aria-labelledby="clavePresupuestaria_tab">
                <div class="row mx-auto">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <table
                                    class="tableRowStyle table table-hover table-bordered order-table text-center tableSize align-middle"
                                    id="catalogoA" style="width:100%">
                                    <thead class="colorMorado">
                                        <tr>
                                            <th class="exportable align-middle text-light">Clave UPP</th>
                                            <th class="exportable align-middle text-light text-center">UPP</th>
                                            <th class="exportable align-middle text-light">Fecha de último cambio</th>
                                            <th class="exportable align-middle text-light">Estatus</th>
                                            <th class="exportable align-middle text-light">Usuario que actualizó</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Metas de actividades B -->
            <div class="tab-pane" id="metasActividad" role="tabpanel" aria-labelledby="metasActividad_tab">
                <div class="row mx-auto">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <table
                                    class="tableRowStyle table table-hover table-bordered order-table text-center tableSize align-middle"
                                    id="catalogoB" style="width:100%">
                                    <thead class="colorMorado">
                                        <tr>
                                            <th class="exportable align-middle text-light">Clave UPP</th>
                                            <th class="exportable align-middle text-light text-center">UPP</th>
                                            <th class="exportable align-middle text-light">Fecha de último cambio</th>
                                            <th class="exportable align-middle text-light">Estatus</th>
                                            <th class="exportable align-middle text-light">Usuario que actualizó</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @isset($dataSet)
            @include('panels.datatableAdmonCaptura')
        @endisset
    </div>

    <script type="text/javascript">
        //inicializamos el data table
        var tabla;
        var letter;
        $(document).ready(function() {

            $(".alert").delay(4000).slideUp(200, function() {
                $(this).alert('close');
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('button[data-bs-toggle="tab"]').on('click', function(e) {
                var id = e.target.id;
                selectTable(id);
            });

            $(window).resize(function() {
                redrawTable(tabla);
            });

            var dt = $('#catalogoA');
            tabla = "#catalogoA";
            letter = "A";
            dt.DataTable().clear().destroy();
            getData(tabla, letter);

            let form = document.getElementById("form");

            $("#form").on('change', '.filters', function() {
                var id = $(".active")[1].id;
                selectTable(id);
            });

            function selectTable(id) {
                switch (id) {
                    case "clavePresupuestaria_tab":
                        var dt = $('#catalogoA');
                        tabla = "#catalogoA";
                        letter = "A";
                        dt.DataTable().clear().destroy();
                        getData(tabla, letter);
                        break;
                    case "metasActividad_tab":
                        var dt = $('#catalogoB');
                        tabla = "#catalogoB";
                        letter = "B";
                        dt.DataTable().clear().destroy();
                        getData(tabla, letter);
                        break;
                }
            }

        });

        $("#form").on("change", ".filters_estatus", function(e) {
            dt.DataTable().clear().destroy();
            getData(tabla, letter);
        });

        // Comprobar datos del modal
        function mostrarAdv(text) {
            var title = 'Asegúrese de ingresar los parametros requeridos.';

            event.preventDefault();
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                confirmButtonText: 'De acuerdo',
            });
        }

        function comprobarModulo() {
            var checarModulo = false;
            if ($('#modulo_filter').val() == null || $('#modulo_filter').val() == 0) {
                var textAux = 'El parametro modulo es necesario para continuar.';
                mostrarAdv(textAux);
            }else checarModulo = true;

            return checarModulo;
        }

        function comprobarRadio(){
            var checarRadio = false;
            if (!$('#capturaRadioH').is(':checked')  && !$('#capturaRadioD').is(':checked')) {
                var textAux = 'Elija la opción habilitar o deshabilitar la captura para continuar.';
                mostrarAdv(textAux);
            }else checarRadio = true;

            return checarRadio;
        }

        // Comprobar si hay algún estado encendido en la tabla programación presupuesto
        var upps = {!! json_encode((array)$comprobarEstado) !!};
        var checarEstado = false;
        var obtenerUPP = "";
       
        $('#upp_filter').on('change', function (e) {
            obtenerUPP = $(this).find('option').filter(':selected').val();
            checarEstado = false;
        });

        let arregloUPP = [];
        $('#btnSave').on("click", function () {
            upps.forEach(upp => {
                if(upp['upp'] == obtenerUPP && upp['estado'] == 1 ) checarEstado = true;
                if(obtenerUPP == "" && upp['estado'] == 1 ){
                    checarEstado = true;
                } 
                if(upp['estado'] == 1) arregloUPP.push(upp['upp']);
                
                obtenerUPP = arregloUPP;
                // console.log(arregloUPP);
            });
            comprobarModulo();
            comprobarRadio();
            if(comprobarModulo() && comprobarRadio() && checarEstado){
                event.preventDefault();
                Swal.fire({
                    title: "¿Quieres revertir el estado?",
                    text: "Las siguiente(s) UPP tiene el estado activo: \n"+arregloUPP,
                    icon: 'question',
                    confirmButtonColor: '#00ff00',
                    confirmButtonText: 'Sí',
                    showCloseButton: true,
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#estado").val("activo");
                        $("#aperturaCierreForm").submit();
                    }
                });
            }
        });

    </script>
@endsection
