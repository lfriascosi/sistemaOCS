<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//
use Illuminate\Support\Facades\DB;
use App\Models\Acta;
use App\Models\Resolucion;
use App\Models\Detalleacta;
use App\Models\Ordendia;
use App\Models\Firmaacta;
use App\Models\Convocatoria;

class ActaController extends Controller
{
    public function index( Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $actas = Acta::join('dbpruebaocs.v_usuarios_sys_ocs','dbsistemaocs.actas.iduser','=','dbpruebaocs.v_usuarios_sys_ocs.numeroIdentificacion')
            ->select('actas.id','actas.iduser','actas.titulo','actas.codigo','actas.descripcion','actas.estado',
            'v_usuarios_sys_ocs.apellidos','v_usuarios_sys_ocs.nombres','v_usuarios_sys_ocs.EMail','v_usuarios_sys_ocs.perfil')
            ->orderBy('actas.id','desc')->paginate(10);
        }else{
            $actas = Acta::join('dbpruebaocs.v_usuarios_sys_ocs','dbsistemaocs.actas.iduser','=','dbpruebaocs.v_usuarios_sys_ocs.numeroIdentificacion')
            ->select('actas.id','actas.iduser','actas.titulo','actas.codigo','actas.descripcion','actas.estado',
            'v_usuarios_sys_ocs.apellidos','v_usuarios_sys_ocs.nombres','v_usuarios_sys_ocs.EMail','v_usuarios_sys_ocs.perfil')
            ->where('actas.'.$criterio,'like','%'.$buscar.'%')
            ->orderBy('actas.id','desc')->paginate(10);
        }
        
        return [
            'pagination' =>[
                'total' =>$actas->total(),
                'current_page' =>$actas->currentPage(),
                'per_page' =>$actas->perPage(),
                'last_page' =>$actas->lastPage(),
                'from' =>$actas->firstItem(),
                'to' =>$actas->lastItem(),
            ],
            'actas' => $actas
        ];
    }

    public function obtenerCabecera( Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        $acta = Acta::join('dbpruebaocs.v_usuarios_sys_ocs','dbsistemaocs.actas.iduser','=','dbpruebaocs.v_usuarios_sys_ocs.numeroIdentificacion')
        ->select('actas.id','actas.iduser','actas.titulo','actas.codigo','actas.descripcion','actas.estado',
        'v_usuarios_sys_ocs.apellidos','v_usuarios_sys_ocs.nombres','v_usuarios_sys_ocs.EMail','v_usuarios_sys_ocs.perfil')
        ->where('actas.id','=',$id)
        ->take(1)->get();

        return ['acta' => $acta];
    }

    public function obtenerOrdenDias( Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        $orden_dias = Ordendia::where('orden_dias.idconvocatoria','=',$id)
        ->orderBy('orden_dias.id','asc')->get();

        return ['orden_dias' => $orden_dias];
    }
    public function buscarConvocatoria( Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $convocatorias = Convocatoria::join('dbpruebaocs.v_usuarios_sys_ocs','dbsistemaocs.convocatorias.iduser','=','dbpruebaocs.v_usuarios_sys_ocs.numeroIdentificacion')
        ->select('convocatorias.id','convocatorias.iduser',
        'convocatorias.titulo','convocatorias.codigo','convocatorias.descripcion','convocatorias.estado','convocatorias.condicion',
        'v_usuarios_sys_ocs.apellidos','v_usuarios_sys_ocs.nombres','v_usuarios_sys_ocs.EMail','v_usuarios_sys_ocs.perfil')
        ->where('convocatorias.'.$criterio,'=',$buscar)
        ->take(1)->get();
        return ['convocatoria' => $convocatorias];
    }

    public function buscarConvocatorias( Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $convocatorias = Convocatoria::join('dbpruebaocs.v_usuarios_sys_ocs','dbsistemaocs.convocatorias.iduser','=','dbpruebaocs.v_usuarios_sys_ocs.numeroIdentificacion')
        ->select('convocatorias.id','convocatorias.iduser',
        'convocatorias.titulo','convocatorias.codigo','convocatorias.descripcion','convocatorias.estado','convocatorias.condicion',
        'v_usuarios_sys_ocs.apellidos','v_usuarios_sys_ocs.nombres','v_usuarios_sys_ocs.EMail','v_usuarios_sys_ocs.perfil')
        ->where('convocatorias.'.$criterio,'like','%'.$buscar.'%')
        ->orderBy('convocatorias.id','desc')->get();
        return ['convocatorias' => $convocatorias];
    }

    public function buscarConvocatoriaActa( Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $convocatorias = Convocatoria::join('orden_dias','convocatorias.id','=','orden_dias.idconvocatoria')
        ->join('detalle_actas','orden_dias.id','=','detalle_actas.idordendia')
        ->select('convocatorias.id','convocatorias.titulo','convocatorias.codigo','convocatorias.descripcion',)
        ->orderBy('convocatorias.id','desc')->get();
        return ['convocatorias' => $convocatorias];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        try {
            DB::beginTransaction();

            $ordendias = $request->data_ordendia;
            $descripciones = $request->data_descripcion;
            $resoluciones = $request->data_resolucion;
            $personas = $request->data_persona;

            $acta = new Acta();
            // $acta->iduser = '0401541727';
            //$acta->iduser = \Auth::user()->num_documento;
            $acta->iduser = $request->session()->get('user_id');
            $acta->titulo = $request->titulo;
            $acta->codigo = $request->codigo;
            $acta->descripcion = $request->descripcion;
            $acta->estado = 'Enviada';
            $acta->save();

            // Array Personas firma_acta
            foreach ($personas as $kep => $per) {
                $firmaacta = new Firmaacta();
                $firmaacta->iduser=$per['cedula'];
                $firmaacta->idacta = $acta->id;
                $firmaacta->save();
            }
            //Array Orden Dia
            foreach ($ordendias as $kep => $vis) {
                //Array Descripciones
                foreach ($descripciones as $kep => $des) {
                    if($vis['idordendia']==$des['idordendia']){
                        $detalleacta = new Detalleacta();
                        $detalleacta->idacta = $acta->id;
                        $detalleacta->idordendia = $des['idordendia'];
                        $detalleacta->nombrealternativo = $vis['nombrealternativo'];
                        $detalleacta->descripcion = $des['descripcion'];
                        $detalleacta->save();
                    }
                }
                //Array Resoluciones
                foreach ($resoluciones as $kep => $res) {
                    if($vis['idordendia']==$res['idordendia']){
                        $detalleresolucion = new Resolucion();
                        $detalleresolucion->idacta = $acta->id;
                        $detalleresolucion->idordendia = $res['idordendia'];
                        $detalleresolucion->codigo = $res['codigo'];
                        $detalleresolucion->nombrealternativo = $vis['nombrealternativo'];
                        $detalleresolucion->descripcion = $res['descripcion'];
                        $detalleresolucion->save();
                    }
                }
            }        
            
            @DB::commit();
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
        }
    }

    public function obtenerDescripcion( Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        $descripciones = Detalleacta::join('actas','detalle_actas.idacta','=','actas.id')
        ->select('detalle_actas.idacta','detalle_actas.idordendia','detalle_actas.nombrealternativo','detalle_actas.descripcion')
        ->where('detalle_actas.idacta','=',$id)
        ->orderBy('detalle_actas.id','asc')->get();

        return ['descripciones' => $descripciones];
    }

    public function obtenerResolucion( Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        $resoluciones = Resolucion::join('actas','resoluciones.idacta','=','actas.id')
        ->select('resoluciones.idacta','resoluciones.idordendia','resoluciones.codigo','resoluciones.nombrealternativo','resoluciones.descripcion')
        ->where('resoluciones.idacta','=',$id)
        ->orderBy('resoluciones.id','asc')->get();

        return ['resoluciones' => $resoluciones];
    }

    public function obtenerIdConvocatoria( Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        $idConvocatoriaD = Ordendia::join('detalle_actas','orden_dias.id','=','detalle_actas.idordendia')
        ->select('orden_dias.idconvocatoria')
        ->where('detalle_actas.idacta','=',$id)
        ->take(1)->get();

        $idConvocatoriaR = Ordendia::join('resoluciones','orden_dias.id','=','resoluciones.idordendia')
        ->select('orden_dias.idconvocatoria')
        ->where('resoluciones.idacta','=',$id)
        ->take(1)->get();

        if (count($idConvocatoriaD)==1) {
            return ['idConvocatoria' => $idConvocatoriaD];
        }elseif (count($idConvocatoriaR)==1) {
            return ['idConvocatoria' => $idConvocatoriaR];
        }
    }

    public function obtenerFirma( Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        $firmas = Firmaacta::join('actas','firma_actas.idacta','=','actas.id')
        ->join('dbpruebaocs.v_usuarios_sys_ocs','dbsistemaocs.firma_actas.iduser','=','dbpruebaocs.v_usuarios_sys_ocs.numeroIdentificacion')
        ->select('firma_actas.idacta','firma_actas.iduser','v_usuarios_sys_ocs.apellidos','v_usuarios_sys_ocs.nombres','v_usuarios_sys_ocs.EMail','v_usuarios_sys_ocs.perfil')
        ->where('firma_actas.idacta','=',$id)
        ->orderBy('firma_actas.iduser','asc')->get();

        return ['firmas' => $firmas];
    }
}
