<div class="modal fade export-excel" id="exportExcel" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#6A0F49 !important; color:whitesmoke">
                <h5 class="modal-title" id="exampleModalLabel">Exportar Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      
            </div>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <div class="modal-body">
                <form action="{{route('exportExcel')}}" method="get" id="formExport">
                    @csrf
                    <div class="row">
                        <div class="col-sm-1">
                             <label><b>Año</b></label>
                        </div>
                        <div class="col-sm-7">
                            <select class="form-control filters" id="anio_filter_export" name="anio_filter_export"
                                                autocomplete="anio_filter_export" placeholder="Seleccione un año">
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="btnExport">EXPORTAR</button>
            </div>
        </div>
    </div>
</div>
