@extends('layouts.app')
@include('administracion.usuarios.modalCreate')
@include('administracion.usuarios.permisosModal')
@include('administracion.usuarios.modalCreatePermisos')
@include('panels.datatable')
@section('content')
    <div class="container">
        <form action="{{ route('getdata') }}" id="buscarForm" method="GET">
            @csrf
            <div class="row">
                <div class="col-sm-2">
                </div>
        </form>
        <section id="widget-grid" class="conteiner">
            <div class="row">
                <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
                    <div color="darken" class="jarviswidget" id="wid-id-1" data-widget-editbutton="false"
                        data-widget-colorbutton="false" data-widget-deletebutton="false">
                        <header>
                            <h2>Usuarios</h2>
                        </header>
                        <div>
                            <div class="jarviswidget-editbox">
                            </div>
                            <div class="widget-body-toolbar">
                                <div class="row">
                                    <div class="col-xs-9 col-sm-5 col-md-5 col-lg-5">
                                    </div>
                                    <div class="col-xs-3 col-sm-7 col-md-7 col-lg-7 text-right">
                                        <button type="button" class="btn btn-success" data-toggle="modal" id="btnNew"
                                            data-target=".bd-example-modal-lg" data-backdrop="static"
                                            data-keyboard="false">Agregar Usuario</button>
                                            <button type="button" class="btn btn-dark" data-toggle="modal" id="btnNew"
                                            data-target=".bd-permisos-modal-lg" data-backdrop="static"
                                            data-keyboard="false">Permisos  adicionales</button>

                                            {{-- es para añadir permisos al catalogo 
                                                <button type="button" class="btn btn-dark" data-toggle="modal" id="btnNew"
                                            data-target=".createpermiso" data-backdrop="static"
                                            data-keyboard="false">Permisos adicionales</button> --}}
                                    </div>
        
                                </div>
                            </div>
                            <br><br>
                            <div class="widget-body no-padding ">
                                <div class="table-responsive ">
                                    <table id="catalogo" class="table table-hover table-striped ">
                                        <thead>
                                            <tr class="colorMorado">
                                                <th data-hide="phone">Nombre Usuario</th>
                                                <th data-hide="phone">Correo</th>
                                                <th data-hide="phone">Nombre Completo</th>
                                                <th data-hide="phone">Celular</th>
                                                <th data-hide="phone">Perfil</th>
                                                <th data-hide="phone">Grupo</th>
                                                <th data-hide="phone">Estatus</th>
                                                <th class="th-administration">Acciones</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </div>
    <script src="/js/administracion/usuarios/init.js"></script>
    <script src="/js/utilerias.js"></script>
@endsection


