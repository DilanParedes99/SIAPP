<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JasperPHP\JasperPHP as PHPJasper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isEmpty;

class ReporteController extends Controller
{
    public function indexPlaneacion(){
        $dataSet = array();
        $names = DB::select('SELECT ROUTINE_NAME AS name FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_TYPE="PROCEDURE" AND ROUTINE_SCHEMA="fondoss_db" AND ROUTINE_NAME LIKE "%art_20%" AND ROUTINE_NAME NOT LIKE "%a_num_1_%"');
        // $names = DB::select('SELECT ROUTINE_NAME AS name FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_TYPE="PROCEDURE" AND ROUTINE_SCHEMA="fondos_db" AND ROUTINE_NAME LIKE "%art_20%" AND ROUTINE_NAME NOT LIKE "%a_num_1_%"');
        $anios = DB::select('SELECT ejercicio FROM programacion_presupuesto pp GROUP BY ejercicio ORDER BY ejercicio DESC');
        return view("reportes.leyHacendaria", [
            'dataSet' => json_encode($dataSet),
            'names' => $names,
            'anios' => $anios,
        ]);
        // return view('reportes.leyHacendaria',compact('names','anios'));
    }

    public function indexAdministrativo(){
        $dataSet = array();
        $anios = DB::select('SELECT ejercicio FROM programacion_presupuesto pp GROUP BY ejercicio ORDER BY ejercicio DESC');
        // $upps = DB::select('SELECT clave,descripcion FROM catalogo WHERE grupo_id = 6 ORDER BY clave ASC');
        return view("reportes.administrativos.index2", [
            'dataSet' => json_encode($dataSet),
            'anios' => $anios,
            // 'upps' => $upps,
        ]);
    }

    // public function reportePlaneacion(Request $request){
    //     $data = DB::select('SELECT ROUTINE_NAME AS name FROM INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_TYPE="PROCEDURE" AND ROUTINE_SCHEMA="fondos_db" AND ROUTINE_NAME LIKE "%art_20%" AND ROUTINE_NAME NOT LIKE "%a_num_1_%"');
    //     $dataSet = array();

    //     foreach($data as $d){
    //         $ds = array($d->name,$d->name);
    //         $dataSet[] = $ds;
    //     }
        
    //     log::info($dataSet);
    //     return response()->json([
    //         "dataSet" => $dataSet,
    //     ]);
    // }

    // prueba
    public function calendarioFondoMensual(Request $request){
        $anio = $request->anio;
        $fecha = $request->input('fechaCorte_filter') != null ? $request->input('fechaCorte_filter') : "NULL";
        $data = DB::select("CALL calendario_fondo_mensual(".$anio.", ".$fecha.")");
        foreach ($data as $d) {

            $suma = $d->enero + $d->febrero + $d->marzo + $d->abril + $d->mayo + $d->junio + $d->julio + $d->agosto + $d->septiembre + $d->octubre + $d->noviembre + $d->diciembre;

            $ds = array($d->ramo, $d->fondo, number_format($d->enero), number_format($d->febrero), number_format($d->marzo), number_format($d->abril), number_format($d->mayo), number_format($d->junio), number_format($d->julio), number_format($d->agosto), number_format($d->septiembre), number_format($d->octubre), number_format($d->noviembre), number_format($d->diciembre), number_format($suma));
            $dataSet[] = $ds;
        }
        return response()->json([
            "dataSet" => $dataSet,
        ]);
    }

    public function resumenCapituloPartida(Request $request){
        log::info($request);
        $anio = $request->anio;
        $fecha = $request->input('fechaCorte_filter') != null ? $request->input('fechaCorte_filter') : "NULL";
        $data = DB::select("CALL resumen_capitulo_partida(".$anio.", ".$fecha.")");
            foreach ($data as $d) {
                $ds = array($d->capitulo, $d->partida_llave." ".$d->partida, number_format($d->importe));
                $dataSet[] = $ds;
            }
        return response()->json([
            "dataSet" => $dataSet,
        ]);
    }

    public function proyectoAvanceGeneral(Request $request){
        $anio = $request->input('anio_filter');
        $fecha = $request->input('fechaCorte_filter') != null ? $request->input('fechaCorte_filter') : "NULL";
        $data = DB::select("CALL resumen_capitulo_partida(23, ".$fecha.")");
            foreach ($data as $d) {
                $ds = array($d->capitulo, $d->partida_llave." ".$d->partida, number_format($d->importe));
                $dataSet[] = $ds;
            }
        return response()->json([
            "dataSet" => $dataSet,
        ]);
    }
    // prueba

    public function reporteAdministrativo($nombre,Request $request){

        $anio = $request->input('anio_filter');
        $fecha = $request->input('fechaCorte_filter') != null ? $request->input('fechaCorte_filter') : "NULL";
    
        $dataSet = array();
        
        // CALENDARIO FONDO MENSUAL
        if($nombre == "calendario_fondo_mensual"){
            $data = DB::select("CALL calendario_fondo_mensual(".$anio.", ".$fecha.")");
            foreach ($data as $d) {
    
                $suma = $d->enero + $d->febrero + $d->marzo + $d->abril + $d->mayo + $d->junio + $d->julio + $d->agosto + $d->septiembre + $d->octubre + $d->noviembre + $d->diciembre;
    
                $ds = array($d->ramo, $d->fondo, number_format($d->enero), number_format($d->febrero), number_format($d->marzo), number_format($d->abril), number_format($d->mayo), number_format($d->junio), number_format($d->julio), number_format($d->agosto), number_format($d->septiembre), number_format($d->octubre), number_format($d->noviembre), number_format($d->diciembre), number_format($suma));
                $dataSet[] = $ds;
            }
        }
        // RESUMEN CAPITULO Y PARTIDA
        elseif($nombre == "resumen_capitulo_partida"){
            $data = DB::select("CALL resumen_capitulo_partida(".$anio.", ".$fecha.")");
            foreach ($data as $d) {
                $ds = array($d->capitulo, $d->partida_llave." ".$d->partida, number_format($d->importe));
                $dataSet[] = $ds;
            }
        }
        // PROYECTO AVANCE GENERAL
        elseif($nombre == 'proyecto_avance_general'){
            // $data = DB::select("CALL resumen_capitulo_partida(".$anio.", ".$fecha.")");
            // foreach ($data as $d) {
            //     $ds = array($d->capitulo, $d->partida_llave, $d->partida, $d->importe);
            //     $dataSet[] = $ds;
            // }
        }
        // CALENDARIO GENERAL
        elseif($nombre == 'calendario_general'){

        }
        // CALENDARIO POR UPP
        elseif($nombre == 'calendario_upp'){

        }

        return response()->json([
            "dataSet" => $dataSet,
        ]);
    }

    // public function getFechaCorte($anio){
    //     $fechaCorte = DB::select('select distinct DATE_FORMAT(deleted_at, "%Y-%m-%d") as deleted_at from programacion_presupuesto pp where anio = ? and deleted_at is not null',[$anio]);

    //     return $fechaCorte;
    // }
    // Datos BD antigua
    public function getFechaCorte($anio){
        $fechaCorte = DB::select('select distinct DATE_FORMAT(deleted_at, "%Y-%m-%d") as deleted_at from programacion_presupuesto pp where ejercicio = ? and deleted_at is not null',[$anio]);

        return $fechaCorte;
    }

    public function downloadReport($nombre, Request $request){ 
        date_default_timezone_set('America/Mexico_City');
        
        setlocale(LC_TIME, 'es_VE.UTF-8','esp');
        // $report =  'reporte_art_20_frac_X_b_num_11_2';
        $report =  $nombre;
        $anio = (int)$request->input('anio');
        $fechaCorte = $request->input('fechaCorte');
        // dd($report);

        $ruta = public_path()."/reportes";
        //Eliminación si ya existe reporte
        if(File::exists($ruta."/".$report.".pdf")) {
            File::delete($ruta."/".$report.".pdf");
        }
        $logo = public_path()."/img/logo.png";
        $report_path = app_path() ."/Reportes/".$report.".jasper";
        // dd($request->action);
        $format = array($request->action);
        $output_file =  public_path()."/reportes";
        $viewFile = public_path()."/reportes/".$report;
        $parameters = array(
            "anio" => $anio,
            "logoLeft" => $logo,
            "logoRight" => $logo,
        );
        if($fechaCorte != null) $parameters["fecha"] = $fechaCorte . " 00:00:00";
        // dd($parameters);

        $database_connection = \Config::get('database.connections.mysql');

        $jasper = new PHPJasper;
        $jasper->process(
          $report_path,
          $output_file,
          $format,
          $parameters,
          $database_connection
        )->execute();

        return $request->action == 'pdf' ? response()->file($viewFile.".pdf")->deleteFileAfterSend() : response()->file($viewFile.".xls")->deleteFileAfterSend(); 
    }
}
