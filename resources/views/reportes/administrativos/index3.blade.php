<?php
    $titleDesc = "Reportes Administrativos";
    
?>

@extends('layouts.app')
@section('content')

    <div class="container w-100 p-4">

        <h1 class="fw-bold text-center">{{ $titleDesc }}</h1>
        <div class="rounded-pill" style="height: .5em; background-color: rgb(37, 150, 190)"></div>
        <form action="{{route('calendario_fondo_mensual')}}" id="buscarFormA" name="analisis" method="post"> </form>
        <form action="{{route('resumen_capitulo_partida')}}" id="buscarFormB" name="analisis" method="post"><input type="text" id="catalogoB_val"  style="display: none"></form> 
        <form action="{{route('proyecto_calendario_general')}}" id="buscarFormC" name="analisis" method="post"><input type="text" id="catalogoC_val"  style="display: none"></form> 
        <form action="{{route('proyecto_avance_general')}}" id="buscarFormD" name="analisis" method="post"><input type="text" id="catalogoD_val"  style="display: none"></form> 
        <form action="{{route('proyecto_calendario_general_actividad')}}" id="buscarFormE" name="analisis" method="post"><input type="text" id="catalogoE_val"  style="display: none"></form> 
        <form action="{{route('avance_proyecto_actividad_upp')}}" id="buscarFormF" name="analisis" method="post"><input type="text" id="catalogoF_val"  style="display: none"></form> 

        {{-- Form para descargar el archivo y cambiar los select --}}
        <form id="form" action="{{route('downloadReport',['nombre'=>'administrativo'])}}" method="POST"> 
            @csrf
            <div class="col-md-10 col-sm-12 d-md-flex mt-5">
                <div class="col-sm-3 col-md-3 col-lg-2 text-md-end">
                    <label for="anio_filter" class="form-label fw-bold mt-md-1">año: </label>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-2">
                    <select class="form-control filters filters_anio" id="anio_filter" name="anio_filter" autocomplete="anio_filter">
                        @foreach ($anios as $anio)
                            <option value={{$anio->ejercicio}}>{{$anio->ejercicio}}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-sm-3 col-md-3 col-lg-2 text-md-end">
                    <label for="fechaCorte_filter" class="form-label fw-bold mt-md-1">Fecha de corte:</label>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-2">
                    <select class="form-control filters filters_fechaCorte" id="fechaCorte_filter" name="fechaCorte_filter" autocomplete="fechaCorte_filter">
                    </select>
                </div>
            </div>
            <div class="col-md-10 col-sm-12 d-md-flex mt-2 ">
                <div class="col-sm-3 col-md-3 col-lg-2 text-md-end d-none div_upp">
                    <label for="fechaCorte_filter" class="form-label fw-bold mt-md-1">UPP:</label>
                </div>
                <div class="col-md-6 col-sm-12 d-none div_upp">
                    <select class="form-control filters filters_upp" id="upp_filter" name="upp_filter" autocomplete="upp_filter">
                        <option value="">Todos</option>
                        @foreach ($upps as $upp)
                            <option value={{$upp->clave}} {{$upp->descripcion}}>{{$upp->clave}} {{$upp->descripcion}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <input type="text" hidden class="nombre" id="nombre" name="nombre">
            {{-- botones de descarga --}}
            <div class="d-flex flex-wrap justify-content-end">
                <button id="btnPDF" type="submit" formtarget="_blank" class="btn btn-light btn-sm btn-labeled me-3" style="border-color: #6a0f49;" title="Generar Reporte PDF" name="action" value="pdf">
                    <span class="btn-label"><i class="fa fa-file-pdf-o text-danger fs-4 align-middle"></i></span>
                    <span class="d-lg-inline align-middle" style="color:#6a0f49; font-size: 1rem">Exportar a PDF</span> 
                </button>
                <button id="btnExcel" type="submit" formtarget="_blank" class="btn btn-light btn-sm btn-labeled" style="border-color: #6a0f49;" title="Generar Reporte Excel" name="action" value="xls">
                    <span class="btn-label"><i class="fa fa-file-excel-o text-success fs-4 align-middle"></i></span>
                    <span class="d-lg-inline align-middle" style="color:#6a0f49; font-size: 1rem">Exportar a Excel</span>
                </button>
            </div>
        </form>



        <br>
        <ul class="nav nav-tabs nav-justified " id="tabs" role="tablist">
            <li class="nav-item" >
                <button class="nav-link textoMorado active" role="tab" type="button" id="fondoMensual_tab" data-bs-toggle="tab" data-bs-target="#fondoMensual" aria-controls="fondoMensual" aria-selected="true">Calendario fondo mensual</button>
            </li>
            <li class="nav-item" >
                <button class="nav-link textoMorado" role="tab" type="button" id="capituloPartida_tab" data-bs-toggle="tab" data-bs-target="#capituloPartida" aria-controls="capituloPartida" aria-selected="false">Resumen capítulo y partida</button>
            </li>
            <li class="nav-item" >
                <button class="nav-link textoMorado" role="tab" type="button" id="calendarioGeneral_tab" data-bs-toggle="tab" data-bs-target="#calendarioGeneral" aria-controls="calendarioGeneral" aria-selected="false">Proyecto calendario general</button>
            </li>
            <li class="nav-item" >
                <button class="nav-link textoMorado" role="tab" type="button" id="avanceGeneral_tab" data-bs-toggle="tab" data-bs-target="#avanceGeneral" aria-controls="avanceGeneral" aria-selected="false">Proyecto avance general</button>
            </li>
            <li class="nav-item" >
                <button class="nav-link textoMorado" role="tab" type="button" id="calendarioGeneralActividad_tab" data-bs-toggle="tab" data-bs-target="#calendarioGeneralActividad" aria-controls="calendarioGeneralActividad" aria-selected="false">Proyecto calendario general de actividades</button>
            </li>
            <li class="nav-item" >
                <button class="nav-link textoMorado" role="tab" type="button" id="avanceProyectoActividadUPP_tab" data-bs-toggle="tab" data-bs-target="#avanceProyectoActividadUPP" aria-controls="avanceProyectoActividadUPP" aria-selected="false">Proyecto calendario general de actividades</button>
            </li>
        
        </ul>

        <div class="tab-content" >
            {{-- fondo mensual --}}
            <div class="tab-pane active" id="fondoMensual" role="tabpanel" aria-labelledby="fondoMensual_tab" >    
                <div class="row mx-auto" >
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="tableRowStyle table table-hover table-bordered order-table text-center tableSize align-middle"
                                    id="catalogoA" data-right="2,3,4,5,6,7,8,9,10,11,12,13,14" data-left="0,1" data-center="0,1,2,3,4,5,6,7,8,9,10,11,12,13,14" style="width:100%">
                                    <thead  class="colorMorado">
                                        <tr>
                                            <th class="exportable align-middle text-light">Ramo</th>
                                            <th class="exportable align-middle text-light" style="width: 300px ;">Fondo</th>
                                            <th class="exportable align-middle text-light">Enero</th>
                                            <th class="exportable align-middle text-light">Febrero</th>
                                            <th class="exportable align-middle text-light">Marzo</th>
                                            <th class="exportable align-middle text-light">Abril</th>
                                            <th class="exportable align-middle text-light">Mayo</th>
                                            <th class="exportable align-middle text-light">Junio</th>
                                            <th class="exportable align-middle text-light">Julio</th>
                                            <th class="exportable align-middle text-light">Agosto</th>
                                            <th class="exportable align-middle text-light">Septiembre</th>
                                            <th class="exportable align-middle text-light">Octubre</th>
                                            <th class="exportable align-middle text-light">Noviembre</th>
                                            <th class="exportable align-middle text-light">Diciembre</th>
                                            <th class="exportable align-middle text-light sum">Importe total</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="colorMorado">
                                        <tr>
                                            <td class="align-middle text-start" colspan="14">TOTAL</td>
                                            <td class="align-middle text-end total" style="width: 20em;" id="total"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--Capitulo y partida-->
            <div class="tab-pane" id="capituloPartida" role="tabpanel" aria-labelledby="capituloPartida_tab" >
                <div class="row mx-auto">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="tableRowStyle table table-hover table-bordered order-table text-center tableSize align-middle"
                                    id="catalogoB" data-right="2" data-left="0,1" data-center="0,1,2" style="width:100%">
                                    <thead  class="colorMorado">
                                        <tr>
                                            <th class="exportable align-middle text-light">Capítulo</th>
                                            <th class="exportable align-middle text-light">Partida</th>
                                            <th class="exportable align-middle text-light sum">Importe</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="colorMorado">
                                        <tr>
                                            <td class="align-middle text-start" colspan="2">TOTAL</td>
                                            <td class="align-middle text-end total" style="width: 20em;" id="total"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Proyecto calendario general --}}
            <div class="tab-pane" id="calendarioGeneral" role="tabpanel" aria-labelledby="calendarioGeneral_tab" >    
                <div class="row mx-auto" >
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="tableRowStyle table table-hover table-bordered order-table text-center tableSize align-middle"
                                    id="catalogoC" data-right="1,2,3,4,5,6,7,8,9,10,11,12,13" data-left="0" data-center="0,1,2,3,4,5,6,7,8,9,10,11,12,13" style="width:100%">
                                    <thead  class="colorMorado">
                                        <tr>
                                            <th class="exportable align-middle text-light">Clave presupuestal</th>
                                            <th class="exportable align-middle text-light sum">Monto anual</th>
                                            <th class="exportable align-middle text-light">Enero</th>
                                            <th class="exportable align-middle text-light">Febrero</th>
                                            <th class="exportable align-middle text-light">Marzo</th>
                                            <th class="exportable align-middle text-light">Abril</th>
                                            <th class="exportable align-middle text-light">Mayo</th>
                                            <th class="exportable align-middle text-light">Junio</th>
                                            <th class="exportable align-middle text-light">Julio</th>
                                            <th class="exportable align-middle text-light">Agosto</th>
                                            <th class="exportable align-middle text-light">Septiembre</th>
                                            <th class="exportable align-middle text-light">Octubre</th>
                                            <th class="exportable align-middle text-light">Noviembre</th>
                                            <th class="exportable align-middle text-light">Diciembre</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Proyecto avance general --}}
            <div class="tab-pane" id="avanceGeneral" role="tabpanel" aria-labelledby="avanceGeneral_tab" >    
                <div class="row mx-auto" >
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="tableRowStyle table table-hover table-bordered order-table text-center tableSize align-middle"
                                    id="catalogoD" data-right="3,4,5,6" data-left="0,1,2,7" data-center="0,1,2,3,4,5,6,7" style="width:100%">
                                    <thead class="colorMorado">
                                        <tr>
                                            <th class="exportable align-middle text-light">Unidad programática presupuestaría</th>
                                            <th class="exportable align-middle text-light">Fondo</th>
                                            <th class="exportable align-middle text-light">Capítulo</th>
                                            <th class="exportable align-middle text-light">Monto anual</th>
                                            <th class="exportable align-middle text-light">Calendarizado</th>
                                            <th class="exportable align-middle text-light">Disponible</th>
                                            <th class="exportable align-middle text-light">% de avance</th>
                                            <th class="exportable align-middle text-light">Estatus</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Proyecto calendario actividades general --}}
            <div class="tab-pane" id="calendarioGeneralActividad" role="tabpanel" aria-labelledby="calendarioGeneralActividad_tab" >    
                <div class="row mx-auto" >
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="tableRowStyle table table-hover table-bordered order-table text-center tableSize align-middle"
                                    id="catalogoE" data-right="1,2,3,4,5,6,7,8,9,10,11,12,13" data-left="0" data-center="0,1,2,3,4,5,6,7,8,9,10,11,12,13" style="width:100%">
                                    <thead  class="colorMorado">
                                        <tr>
                                            <th class="exportable align-middle text-light">UPP</th>
                                            <th class="exportable align-middle text-light">UR</th>
                                            <th class="exportable align-middle text-light">Programa</th>
                                            <th class="exportable align-middle text-light">Subprograma</th>
                                            <th class="exportable align-middle text-light">Proyecto</th>
                                            <th class="exportable align-middle text-light">Fondo</th>
                                            <th class="exportable align-middle text-light">Actividad</th>
                                            <th class="exportable align-middle text-light">Cantidad Beneficiarios</th>
                                            <th class="exportable align-middle text-light">Beneficiarios</th>
                                            <th class="exportable align-middle text-light">U. de medida</th>
                                            <th class="exportable align-middle text-light">Tipo de actividad</th>
                                            <th class="exportable align-middle text-light sum">Meta anual</th>
                                            <th class="exportable align-middle text-light">Enero</th>
                                            <th class="exportable align-middle text-light">Febrero</th>
                                            <th class="exportable align-middle text-light">Marzo</th>
                                            <th class="exportable align-middle text-light">Abril</th>
                                            <th class="exportable align-middle text-light">Mayo</th>
                                            <th class="exportable align-middle text-light">Junio</th>
                                            <th class="exportable align-middle text-light">Julio</th>
                                            <th class="exportable align-middle text-light">Agosto</th>
                                            <th class="exportable align-middle text-light">Septiembre</th>
                                            <th class="exportable align-middle text-light">Octubre</th>
                                            <th class="exportable align-middle text-light">Noviembre</th>
                                            <th class="exportable align-middle text-light">Diciembre</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Avance de proyectos con actividades por upp --}}
            <div class="tab-pane" id="avanceProyectoActividadUPP" role="tabpanel" aria-labelledby="avanceProyectoActividadUPP_tab" >    
                <div class="row mx-auto" >
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="tableRowStyle table table-hover table-bordered order-table text-center tableSize align-middle"
                                    id="catalogoF" data-right="1,2,3" data-left="0,4" data-center="0,1,2,3,4" style="width:100%">
                                    <thead class="colorMorado">
                                        <tr>
                                            <th class="exportable align-middle text-light">Unidad programática presupuestaría</th>
                                            <th class="exportable align-middle text-light">Cantidad de proyectos</th>
                                            <th class="exportable align-middle text-light">Cantidad de proyectos con actividades</th>
                                            <th class="exportable align-middle text-light">% de avance</th>
                                            <th class="exportable align-middle text-light">Estatus</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
      @isset($dataSet)
      @include('panels.datatableMultiple')
      @endisset  

      <script type="text/javascript">
        //inicializamos el data table
        var tabla;
        var letter;
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            getDataFechaCorte($('#anio_filter').val());
            $("#nombre").val('fondo_base_mensual');
    
            $('button[data-bs-toggle="tab"]').on('click', function (e) {
                var id = e.target.id;
                selectTable(id);
            });
    
        $( window ).resize(function() {
                redrawTable(tabla);
        });
             var dt = $('#catalogoA');
             tabla="#catalogoA";
            letter="A";
            dt.DataTable().clear().destroy();
            getData(tabla,letter);
            
           
            let form = document.getElementById("form");
            
            $("#form").on('change','.filters',function(){
                var id = $(".active")[1].id;
                selectTable(id);
            });
            
            function selectTable(id){
    
        switch(id){
            case "fondoMensual_tab":
                var dt = $('#catalogoA');
                tabla="#catalogoA";
                letter="A";
                $('.div_upp').addClass('d-none');
                $("#nombre").val('calendario_fondo_mensual');
                dt.DataTable().clear().destroy();
                getData(tabla,letter);                    
                break;
            case "capituloPartida_tab":
                var dt = $('#catalogoB');
                tabla="#catalogoB";
                letter="B";
                $('.div_upp').addClass('d-none');
                $("#nombre").val('reporte_resumen_por_capitulo_y_partida');
                dt.DataTable().clear().destroy();
                getData(tabla,letter);
                break;
            case "calendarioGeneral_tab":
                var dt = $('#catalogoC');
                tabla="#catalogoC";
                letter="C";
                $('.div_upp').removeClass('d-none');
                $("#nombre").val('calendario_general');
                dt.DataTable().clear().destroy();
                getData(tabla,letter);
                break;
            case "avanceGeneral_tab":
                var dt = $('#catalogoD');
                tabla="#catalogoD";
                letter="D";
                $('.div_upp').removeClass('d-none');
                $("#nombre").val('avance_general');
                dt.DataTable().clear().destroy();
                getData(tabla,letter);
                break;
            case "calendarioGeneralActividad_tab":
                var dt = $('#catalogoE');
                tabla="#catalogoE";
                letter="E";
                $('.div_upp').removeClass('d-none');
                $("#nombre").val('calendario_general_actividad');
                dt.DataTable().clear().destroy();
                getData(tabla,letter);
                break;
            case "avanceProyectoActividadUPP_tab":
                var dt = $('#catalogoF');
                tabla="#catalogoF";
                letter="F";
                $('.div_upp').addClass('d-none');
                $("#nombre").val('avance_proyectos_actividades_upp');
                dt.DataTable().clear().destroy();
                getData(tabla,letter);
                break;
    
         }
    }
    
    });
    
    $("#form").on("change",".filters_anio",function(e){
        dt.DataTable().clear().destroy();
        getData(tabla,letter);
        getDataFechaCorte($('#anio_filter').val());
    });
    
    $("#form").on("change",".filters_fechaCorte",function(e){
        dt.DataTable().clear().destroy();
        getData(tabla,letter);
    });

    $("#form").on("change",".filters_upp",function(e){
        dt.DataTable().clear().destroy();
        getData(tabla,letter);
    });
         
    
    </script>


@endsection