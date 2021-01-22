<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//
use Illuminate\Support\Facades\DB;
use App\Models\Acta;
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

    public function obtenerIdConvocatoria( Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        $idconvocatoria = Ordendia::join('detalle_actas','orden_dias.id','=','detalle_actas.idordendia')
        ->select('orden_dias.idconvocatoria')
        ->where('detalle_actas.idacta','=',$id)->orderBy('orden_dias.id','asc')->take(1)->get();

        return ['idConvocatoria' => $idconvocatoria];
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
}
