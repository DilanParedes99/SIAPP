@section('page_scripts')
<script type="text/javascript">


    function getDataFechaCorte(anio) { //función para actualizar el select fechas de corte
        $.ajax({
            url: "/Reportes/data-fecha-corte/"+ anio,
            type:'POST',
            dataType: 'json',
            success: function(data) {
                var par = $('#fechaCorte_filter');
                par.html('');
                par.append(new Option("Todo", ""));
                $.each(data, function(i, val){
                    par.append(new Option(data[i].deleted_at, data[i].deleted_at));
                });
            }
        });
    }

    function getData(tabla, rt){
       

       var dt = $(tabla);
       var orderDt = "";
       var column = "";
       var ruta;
       var formatRight = [];
        var formatLeft = [];
        var formatCenter = [];
        var bold = [];
         
       switch(rt){
           case "A":
               ruta = "#buscarFormA";
               break;
           case "B":
               ruta = "#buscarFormB";
               break;
           case "C":
               ruta = "#buscarFormC";
               break;
           case "D":
               ruta = "#buscarFormD";
               break;
           case "E":
               ruta = "#buscarFormE";
               break;
           case "F":
               ruta = "#buscarFormF";
               break;
           default:
               break;
       }

        if(dt.attr('data-bold')!=undefined){
            bold = dt.attr('data-bold').split(",");
            for(var i in bold){
                if(bold[i] != ""){
                    bold[i] = parseInt(bold[i]);
                }
            }
        }

        if(dt.attr('data-right')!=undefined){
            formatRight = dt.attr('data-right').split(",");
            for(var i in formatRight){
                if(formatRight[i] != ""){
                    formatRight[i] = parseInt(formatRight[i]);
                }
            }
        }

        if(dt.attr('data-left')!=undefined){
            formatLeft = dt.attr('data-left').split(",");
            for(var i in formatLeft){
                if(formatLeft[i] != ""){
                    formatLeft[i] = parseInt(formatLeft[i]);
                }
            }
        }

        if(dt.attr('data-center')!=undefined){
            formatCenter = dt.attr('data-center').split(",");
            for(var i in formatCenter){
                if(formatCenter[i] != ""){
                    formatCenter[i] = parseInt(formatCenter[i]);
                }
            }
        }
       
       var formData = new FormData();
       var csrf_tpken = $("input[name='_token']").val();
       var anio = $("#anio_filter").val();
       var fecha = !$("#fechaCorte_filter").val() ? "null" : $("#fechaCorte_filter").val();
       
       formData.append("_token",csrf_tpken);
       formData.append("anio",anio);
       formData.append("fecha",fecha);
       if(!$('.div_upp').hasClass('d-none')){
           var upp = !$("#upp_filter").val() ? "null" : $("#upp_filter").val();
           formData.append("upp",upp);
        }

        $.ajax({
           url: $(ruta).attr("action"),
           data: formData,
            type:'POST',
            dataType: 'json',
            contentType: false,
            processData: false,
            beforeSend: function() {
                let timerInterval
                Swal.fire({
                    title: 'Cargando datos, por favor espere...',
                    html: ' <b></b>',
                    allowOutsideClick: false,
                    timer: 2000000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
            },
            complete: function(){
                Swal.close();
            },
           success: function(response) {
                if(response.dataSet.length == 0){
                    dt.attr('data-empty','true');
                }
                else{
                    dt.attr('data-empty','false');
                }
                // Se habilita el rowgroup dependiendo la tabla en la que esta el usuario
                var estatus = false;
                if(ruta == "#buscarFormD") estatus = true;

               dt.DataTable({
                    data: response.dataSet,
                    searching: true,
                    autoWidth: true,
                    order:[],
                    group: [],
                    rowGroup: estatus,
                    ordering: true,
                    pageLength: 10,
                    dom: 'frltip',
                    scrollX: true,
                    "lengthMenu": [10, 25, 50, 75, 100, 150, 200],
                   language: {
                       processing: "Procesando...",
                       lengthMenu: "Mostrar _MENU_ registros",
                       zeroRecords: "No se encontraron resultados",
                       emptyTable: "Ningún dato disponible en esta tabla",
                       info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                       infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                       infoFiltered: "(filtrado de un total de _MAX_ registros)",
                       search: "Búsqueda:",
                       infoThousands: ",",
                       loadingRecords: "Cargando...",
                       buttonText: "Imprimir",
                       paginate: {
                           first: "Primero",
                           last: "Último",
                           next: "Siguiente",
                           previous: "Anterior",
                       },
                       buttons: {
                           copyTitle: 'Copiado al portapapeles',
                           copySuccess: {
                               _: '%d registros copiados',
                               1: 'Se copio un registro'
                           }
                       },
                   },
                    columnDefs: [
                        {
                            defaultContent: "-",
                            targets: "_all"
                        },
                        {
                            targets: formatRight,
                            className: 'text-right txtR'
                        },
                        {
                            targets: formatLeft,
                            className: 'text-left txtL'
                        },
                        {
                            targets: formatCenter,
                            className: 'text-center'
                        }
                    ],
                    // Poner el scroll debajo del footer 
                    fnInitComplete: function(){
                        //Comprobar si hay footer
                        if($(tabla+' tfoot.colorMorado').length != 0){
                            // Deshabilitar la barra de scroll del body
                            $('.dataTables_scrollBody').css({
                                'overflow': 'hidden',
                                'border': '0'
                            });
    
                            // Habilitar la barra de scroll en el tfoot
                            $('.dataTables_scrollFoot').css('overflow', 'auto');
    
                            // Sincronizar la barra de scroll con la body
                            $('.dataTables_scrollFoot').on('scroll', function () {
                                $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                            });     
                        }
                    },
                    // obtener la suma total
                    footerCallback: function (row, data, start, end, display) {
                        var api = this.api();
            
                        // Cambiar el formato string a entero
                        var intVal = function (i) {
                            return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                        };
            
                            if(ruta == "#buscarFormD"){
                                totalMonto = api
                                .column(2)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                totalEnero = api
                                .column(3)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                totalFebrero = api
                                .column(4)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                totalMarzo = api
                                .column(5)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                totalAbril = api
                                .column(6)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                totalMayo = api
                                .column(7)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                totalJunio = api
                                .column(8)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                totalJulio = api
                                .column(9)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                totalAgosto = api
                                .column(10)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                totalSeptiembre = api
                                .column(11)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                totalOctubre = api
                                .column(12)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                totalNoviembre = api
                                .column(13)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                totalDiciembre = api
                                .column(14)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                                $(api.column(2).footer()).html( (totalMonto/2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") );
                                $(api.column(3).footer()).html( (totalEnero/2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") );
                                $(api.column(4).footer()).html( (totalFebrero/2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") );
                                $(api.column(5).footer()).html( (totalMarzo/2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") );
                                $(api.column(6).footer()).html( (totalAbril/2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") );
                                $(api.column(7).footer()).html( (totalMayo/2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") );
                                $(api.column(8).footer()).html( (totalJunio/2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") );
                                $(api.column(9).footer()).html( (totalJulio/2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") );
                                $(api.column(10).footer()).html( (totalAgosto/2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") );
                                $(api.column(11).footer()).html( (totalSeptiembre/2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") );
                                $(api.column(12).footer()).html( (totalOctubre/2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") );
                                $(api.column(13).footer()).html( (totalNoviembre/2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") );
                                $(api.column(14).footer()).html( (totalDiciembre/2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") );
                            }else if(ruta == "#buscarFormC"){
                                totalMonto = api
                                .column(3)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                totalCalendarizado = api
                                .column(4)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                                totalDisponible = api
                                .column(5)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                                $(api.column(3).footer()).html( (totalMonto/2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") );
                                $(api.column(4).footer()).html( (totalCalendarizado/2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") );
                                $(api.column(5).footer()).html( (totalDisponible/2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") );
                                
                            }else{
                                // Suma total de todas las páginas
                                total = api
                                    .column(".sum")
                                    .data()
                                    .reduce(function (a, b) {
                                        return intVal(a) + intVal(b);
                                }, 0);
                                    
                                // Actualizar footer
                                $(api.column(".sum").footer()).html( total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") );
                            }
                    },
               });
               redrawTable(tabla);   
               if(ruta == "#buscarFormD"){ // Eliminar primera columna que contiene las UPP
                    dt.DataTable().column(0).visible(false);
                }
            },
           error: function(response) {
               console.log('{{__("messages.error")}}: ' + response);
           }
       });
   }
   function redrawTable(tabla){
        dt = $(tabla);
        dt.DataTable().columns.adjust().draw();
        dt.children("thead").css("visibility","hidden");
    }
</script>
<style>
    .custom-select{
        min-width: 4em;
    }
</style>
@endsection