
<div class="modal fade bd-example-modal-lg" id="ModalCargaMasiva" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div  class="modal-header colorMorado">
          <h5  class="modal-title" id="staticBackdropLabel">{{ __('messages.carga_masiva_title') }}</h5>
          <button type="button" class="btn-close" onclick="limpiarCampos()" data-bs-dismiss="modal"
                  aria-label="Close"></button>
        </div>

          @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 d-flex justify-content-center" >
              <div class="form-group">
                <label for="Instrucciones">
                  <span style="vertical-align: inherit;"><span style="vertical-align: inherit;"><b>Lea las instrucciones para asegurar el funcionamiento correcto del proceso:</b> </span></span>
                </label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-9">
              <div class="form-group">
                <label for="descripcion">
                  <span style="vertical-align: inherit;"><span style="vertical-align: inherit;"> <b>Asegurese de usar la plantilla </b> para el correcto funcionamiento de la carga masiva</span></span>
                </label>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <a href="/calendarizacion/get-plantilla" type="button" class="btn colorMorado" id="downloadbtn" name="downloadbtn">               
                 <i class="fa fa-download" style="color: #dfdfdf"></i> 
                  {{ __('messages.descargar_plantilla') }}</a>

              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-10">
              <div class="form-group">
                <label for="instruccion 2">
                  <span style="vertical-align: inherit;"><span style="vertical-align: inherit;">Modifique única y exclusivamente las columnas de <b>año y las propias cantidades de los meses</b></span></span>
                  </label>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-8">
              <div class="form-group">
                <label for="instruccion 3">
                  <span style="vertical-align: inherit;"><span style="vertical-align: inherit;">Los valores no especificados o con celdas vacias <b> quedáran en 0</b></span></span>
                  </label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label for="instruccion 4">
                  <span style="vertical-align: inherit;"><span style="vertical-align: inherit;">El archivo cargado debe ser un documento <b>.csv</b></span></span>
                  </label>
              </div>
            </div>
          </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="d-flex justify-content-center">
                  <button style="width: 20%; border: 1px solid #555;" type="button" class="btn colorMorado" onclick="document.getElementById('file').click()">Seleccionar archivo</button>
                  <input type="file"  id="file" name="file" style="display:none"
                   accept=" .xlsx ">
                   <input id="file_label" style="width: 70%" type="text" readonly value="Sin archivos seleccionados">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer-carga">
         <button type="button" {{-- onclick="limpiarCampos()" --}} class="btn btn-secondary " data-bs-dismiss="modal">{{__("messages.cancelar")}}</button>
          <button type="button" name="aceptar" id="aceptar" class="btn colorMorado">
            <i class="fa fa-upload" style="color: #dfdfdf"></i> 
             {{__("messages.cargar_archivo")}}</button>
          </div>
        </div>

      </div>
  </div>



<script type="text/javascript">

   //mostrar campos una vez selecionado el municipio
   $('#file').change(function(e) {
      e.preventDefault();
      $("#ModalCargaMasiva").find("#file_label").val($('#file')[0].files[0].name)
    });
    
    $('#aceptar').click(function(e){
      e.preventDefault();
      var fd = new FormData();
      var files = $('#file')[0].files;
      fd.append('_token', "{{ csrf_token() }}")
      fd.append('file',files[0]);

      $.ajax({
        url: "{{route('load_data_plantilla')}} ",
        type: "POST",
        data:  fd,
        processData: false,
        contentType: false,
        dataType: 'json',
        beforeSend: function () {
          let timerInterval
          Swal.fire({
            title: '{{__("messages.msg_cargando_datos")}}',
            html: ' <b></b>',
            allowOutsideClick: false,
            timer: 2000000,
            timerProgressBar: true,
            didOpen: () => {
                    Swal.showLoading();
                },
                
          });
        },
        success: function (response) {
          Swal.close();
          Swal.fire({
              title: 'Exito',
             timer: 2000,
             icon: 'success',
            text:  'Datos importados con exito',
            didOpen: () => {
                    Swal.hideLoading();
                },
           }).then(
             function () {
              $("#ModalCargaMasiva").find("#file_label").val('Sin archivos seleccionados')
              $('#ModalCargaMasiva').modal('hide');

             },
           
          )
        },
        error: function (response) {
          Swal.fire({
            icon: 'error',
            title: '{{__("messages.error")}}',
            timer: 2000,
            didOpen: () => {
                    Swal.hideLoading();
                },
            text: response.responseJSON.message ? response.responseJSON.message : 'Ocurrio un error al momento de obtener la información.',
            confirmButtonText: "{{__('messages.aceptar')}}",
          }).then(
            function(){
              $("#ModalCargaMasiva").find("#file_label").val('Sin archivos seleccionados')
              $('#ModalCargaMasiva').modal('hide');

            }
          );
        }
      });
      
    })



</script>
