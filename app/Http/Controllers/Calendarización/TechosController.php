<?php

namespace App\Http\Controllers\Calendarización;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TechosController extends Controller
{
    //Consulta Vista Techos
    public function getIndex()
    {
        return view('calendarización.techos.index');
    }

    public function getTechos(){
        $dataSet = [];
        $data = DB::table('techos_financieros as tf')
            ->select('tf.clv_upp','vee.upp as descPre','tf.tipo','tf.clv_fondo','f.fondo_ramo','tf.presupuesto','tf.ejercicio')
            ->leftJoin('v_entidad_ejecutora as vee','vee.clv_upp','=','tf.clv_upp')
            ->leftJoin('fondo as f', 'f.clv_fondo_ramo','=','tf.clv_fondo')
            ->where('tf.ejercicio','=',2023)
            ->get()
        ;

        foreach ($data as $d){
            $button2 = '<a class="btn btn-secondary" onclick="" data-toggle="modal"
            data-target="#createGroup" data-backdrop="static" data-keyboard="false"><i class="fa fa-pencil" style="font-size: large; color: white"></i></a>';
            $button3 = '<button onclick="" title="Eliminar grupo" class="btn btn-danger"><i class="fa fa-trash" style="font-size: large"></i></button>';

            array_push($dataSet,[$d->clv_upp, $d->descPre, $d->tipo,$d->clv_fondo,$d->fondo_ramo,'$'.number_format($d->presupuesto),$d->ejercicio,$button2.' '.$button3]);
        }

        return [
            'dataSet'=>$dataSet,
            'data' => json_encode($data)
        ];
    }
}
