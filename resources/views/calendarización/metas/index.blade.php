@extends('layouts.app')
@include('panels.datatable')
@section('content')
    <div class="container">
        <section id="widget-grid" class="conteiner">
            <div class="row">
                <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
                    <div color="darken" class="jarviswidget" id="wid-id-1" data-widget-editbutton="false"
                        data-widget-colorbutton="false" data-widget-deletebutton="false">
                        <header class="d-flex justify-content-center" style=" border-bottom: 5px solid #17a2b8;">
                            <h2>Calendarización de metas</h2>
                        </header>
                        <br>
                        <div>
                            <div class="widget-body-toolbar">
                                <div class="row">
                                    <div class="col-md-5">
                                        <select class="form-control filters" id="ur_filter" name="ur_filter"
                                            autocomplete="ur_filter" placeholder="Seleccione una UR"
                                            data-live-search="true">
                                            <option value="" disabled selected>Seleccione una UR</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                                    <div class="table table-responsive-lg d-flex justify-content-center">
                                        <table id="proye">
                                            <thead >
                                                <tr class="colorMorado">
                                                    <th>Programas</th>
                                                    <th >SubProgramas</th>
                                                    <th class="subName">Proyecto</th>
                                                    <th>Seleccion</th>
                                                </tr>
                                            </thead>
                                        </table>
                            </div>

                        </div>

                    </div>
            </div>
            @include('calendarización.metas.tableMetas')
    </div>
    </article>
    </div>
    </section>
    </div>
    <script src="/js/calendarización/metas/init.js"></script>
    <script src="/js/utilerias.js"></script>
    <script>
        //En las vistas solo se llaman las funciones del archivo init
        init.validateCreate($('#frm_create'));
    </script>
@endsection
