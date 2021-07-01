<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//
use Illuminate\Support\Facades\DB;
use App\Models\Convocatoria;
use App\Models\Detalleconvocatoria;
use App\Models\Detalleordendia;
use App\Models\Ordendia;
use App\Models\Persona;
use Illuminate\Support\Facades\Storage;

use App\Mail\VueMail;
use Illuminate\Support\Facades\Mail;

class ConvocatoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index( Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $convocatorias = Convocatoria::join('dbpruebaocs.v_usuarios_sys_ocs','dbsistemaocs.convocatorias.iduser','=','dbpruebaocs.v_usuarios_sys_ocs.numeroIdentificacion')
            ->select('convocatorias.id','convocatorias.iduser',
            'convocatorias.titulo','convocatorias.codigo','convocatorias.descripcion','convocatorias.estado','convocatorias.condicion',
            'v_usuarios_sys_ocs.apellidos','v_usuarios_sys_ocs.nombres','v_usuarios_sys_ocs.EMail','v_usuarios_sys_ocs.perfil')
            ->orderBy('convocatorias.id','desc')->paginate(10);
        }else{
            $convocatorias = Convocatoria::join('dbpruebaocs.v_usuarios_sys_ocs','dbsistemaocs.convocatorias.iduser','=','dbpruebaocs.v_usuarios_sys_ocs.numeroIdentificacion')
            ->select('convocatorias.id','convocatorias.iduser',
            'convocatorias.titulo','convocatorias.codigo','convocatorias.descripcion','convocatorias.estado','convocatorias.condicion',
            'v_usuarios_sys_ocs.apellidos','v_usuarios_sys_ocs.nombres','v_usuarios_sys_ocs.EMail','v_usuarios_sys_ocs.perfil')
            ->where('convocatorias.'.$criterio,'like','%'.$buscar.'%')
            ->orderBy('convocatorias.id','desc')->paginate(10);
        }
        return [
            'pagination' =>[
                'total' =>$convocatorias->total(),
                'current_page' =>$convocatorias->currentPage(),
                'per_page' =>$convocatorias->perPage(),
                'last_page' =>$convocatorias->lastPage(),
                'from' =>$convocatorias->firstItem(),
                'to' =>$convocatorias->lastItem(),
            ],
            'convocatorias' => $convocatorias
        ];
    }
    public function obtenerCabecera( Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        $convocatoria = Convocatoria::join('dbpruebaocs.v_usuarios_sys_ocs','dbsistemaocs.convocatorias.iduser','=','dbpruebaocs.v_usuarios_sys_ocs.numeroIdentificacion')
        ->select('convocatorias.id','convocatorias.iduser','convocatorias.titulo','convocatorias.codigo','convocatorias.descripcion','convocatorias.estado',
        'v_usuarios_sys_ocs.apellidos','v_usuarios_sys_ocs.nombres','v_usuarios_sys_ocs.EMail','v_usuarios_sys_ocs.perfil')
        ->where('convocatorias.id','=',$id)
        ->take(1)->get();

        return ['convocatoria' => $convocatoria];
    }

    public function obtenerDetalles( Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        $detalles = Detalleconvocatoria::join('convocatorias','detalle_convocatorias.idconvocatoria','=','convocatorias.id')
        ->join('dbpruebaocs.v_usuarios_sys_ocs','dbsistemaocs.detalle_convocatorias.iduser','=','dbpruebaocs.v_usuarios_sys_ocs.numeroIdentificacion')
        ->select('detalle_convocatorias.idconvocatoria','detalle_convocatorias.iduser','v_usuarios_sys_ocs.apellidos','v_usuarios_sys_ocs.nombres','v_usuarios_sys_ocs.EMail','v_usuarios_sys_ocs.perfil')
        ->where('detalle_convocatorias.idconvocatoria','=',$id)
        ->orderBy('detalle_convocatorias.iduser','asc')->get();

        return ['detalles' => $detalles];
    }

    public function obtenerOrdenDias( Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        $orden_dias = Ordendia::join('convocatorias','orden_dias.idconvocatoria','=','convocatorias.id')
        ->select('orden_dias.id','orden_dias.idconvocatoria','orden_dias.numerador','orden_dias.nombre','orden_dias.nomdoc','orden_dias.estado','orden_dias.condicion')
        ->where('orden_dias.idconvocatoria','=',$id)
        ->orderBy('orden_dias.id','asc')->get();

        return ['orden_dias' => $orden_dias];
    }

    public function obtenerDetalleOrdenDias( Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        $detalleorden_dias = Detalleordendia::join('orden_dias','detalle_orden_dias.idordendia','=','orden_dias.id')
        ->join('convocatorias','orden_dias.idconvocatoria','=','convocatorias.id')
        ->join('dbpruebaocs.v_usuarios_sys_ocs','dbsistemaocs.detalle_orden_dias.iduser','=','dbpruebaocs.v_usuarios_sys_ocs.numeroIdentificacion')
        ->select('orden_dias.nombre','orden_dias.numerador','v_usuarios_sys_ocs.numeroIdentificacion','v_usuarios_sys_ocs.apellidos','v_usuarios_sys_ocs.nombres','v_usuarios_sys_ocs.EMail','v_usuarios_sys_ocs.perfil')
        ->where('orden_dias.idconvocatoria','=',$id)
        ->orderBy('orden_dias.numerador','asc')->get();

        return ['detalleorden_dias' => $detalleorden_dias];
    }

    // //Consulta personas segunda Base local
    public function indexP(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $personas = Persona::orderBy('apellidos','asc')->get();
        }else{
            $personas = Persona::where('v_usuarios_sys_ocs.'.$criterio,'like','%'.$buscar.'%')->orderBy('apellidos','asc')->get();
        }
        return ['personas'=>$personas];
    }
    //Consulta personas segunda Base OCS
    // public function indexP(Request $request)
    // {
    //     //if (!$request->ajax()) return redirect('/');
    //     $buscar = $request->buscar;
    //     $criterio = $request->criterio;
    //     if($buscar==''){
    //         $personas = Persona::orderBy('apellidos','asc')
    //         ->where('perfil','=','MiembroOCS')
    //         ->orderBy('apellidos','asc')->get();
    //     }else{
    //         $personas = Persona::where('perfil','=','MiembroOCS')
    //         ->where($criterio,'like','%'.$buscar.'%')
    //         ->orderBy('apellidos','asc')->get();
    //     }
    //     return ['personas'=>$personas];
    // }
    public function buscarPersona( Request $request){
        if (!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $personas = Persona::where('numeroIdentificacion','=',$filtro)
        ->select('numeroIdentificacion','apellidos','nombres','EMail','perfil')
        ->orderBy('apellidos','asc')
        ->take(1)->get();

        return ['personas' => $personas];
    }

    public function storeArchivo(Request $request)
    {
        $array=$request->all();
        $array_num = count($array);
        for ($i = 0; $i < $array_num; ++$i){
            $file=$array[$i];
            $nombre = $file->getClientOriginalName();
            Storage::disk('public')->put($nombre,  \File::get($file));
        }
    }
    public function storeEmail(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $control="MiembroOCS";
        $codigoConvocatoria = $request->codigo;
        $personas = $request->data_persona;

        $idConvocatoria = Convocatoria::where('codigo','=',$codigoConvocatoria)
        ->select('id')
        ->take(1)->get();
        $lstordendias = Ordendia::where('idconvocatoria','=',$idConvocatoria[0]['id'])
        ->orderBy('idconvocatoria','asc')->get();
        
        $data = ([
                'titulo' => $request->titulo,
                'codigo' => $request->codigo,
                'descripcion' => $request->descripcion,
            ]);
        foreach ($personas as $kep => $per) {
            Mail::to($per['EMail'])->send(new VueMail($data, $lstordendias, $control));
        }
    }
    public function storeEmailInv(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $control="MiembroINV";
            $codigoConvocatoria = $request->codigo;
            $personas = $request->data_persona_invitada;
        

            $idConvocatoria = Convocatoria::where('codigo','=',$codigoConvocatoria)
            ->select('id')
            ->take(1)->get();
            $lstordendias = Ordendia::where('idconvocatoria','=',$idConvocatoria[0]['id'])
            ->orderBy('idconvocatoria','asc')->get();

            foreach ($personas as $kep => $per) {
                $data = ([
                    'titulo' => $request->titulo,
                    'codigo' => $request->codigo,
                    'descripcion' => $request->descripcion,
                    'numerador' => $per['idordendia'],
                    'estado' => 'Activo'
                ]);
                Mail::to($per['EMail'])->send(new VueMail($data, $lstordendias, $control));
            }
        
    }
    public function storeEmailConf(Request $request){
        $id = $request->id;
        $idConvocatoria = $request->idConvocatoria;
        $control="MiembroINV";

        $lstordendias = Ordendia::where('idconvocatoria','=',$idConvocatoria)
        ->orderBy('idconvocatoria','asc')->get();
        
        $ordenDia = Ordendia::findOrFail($request->id);
            
        $data = ([
            'titulo' => $request->titulo,
            'codigo' => $request->codigo,
            'descripcion' => $request->descripcion,
            'numerador' => $ordenDia->numerador,
            'estado' => $ordenDia->estado
        ]);
        $personas = $request->data_persona_invitada;
        foreach ($personas as $kep => $per) {
            if ($ordenDia->numerador == $per['numerador']) {
                Mail::to($per['EMail'])->send(new VueMail($data, $lstordendias, $control));
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        try {
            DB::beginTransaction();

            $personas = $request->data_persona;
            $ordendias = $request->data_ordendia;
            $detalleordendias = $request->data_persona_invitada;

            $convocatoria = new Convocatoria();
            // $convocatoria->iduser = '0401541727';
            $convocatoria->iduser = $request->session()->get('user_id');
            $convocatoria->titulo = $request->titulo;
            $convocatoria->codigo = $request->codigo;
            $convocatoria->descripcion = $request->descripcion;
            $convocatoria->estado = 'Enviada';
            $convocatoria->save();
            //Array Personas Detalle_convocatoria
            foreach ($personas as $kep => $per) {
                $detalleconvocatoria = new Detalleconvocatoria();
                $detalleconvocatoria->iduser=$per['cedula'];
                $detalleconvocatoria->idconvocatoria = $convocatoria->id;
                $detalleconvocatoria->save();
            }

            //Array Orden Dia
            foreach ($ordendias as $kep => $ord) {
                $ordendia = new Ordendia();
                $ordendia->idconvocatoria = $convocatoria->id;
                $ordendia->numerador = $ord['nro'];
                $ordendia->nombre = $ord['nombre'];
                $ordendia->nomdoc = $ord['nomdoc'];
                $ordendia->estado = 'Activo';
                $ordendia->save();
                //Array Detalle Orden Dia
                foreach ($detalleordendias as $kep => $dord) {
                    if($ord['nro']==$dord['idordendia']){
                        $detalleordendia = new Detalleordendia();
                        $detalleordendia->idordendia = $ordendia->id;
                        $detalleordendia->iduser = $dord['cedula'];
                        $detalleordendia->save();
                    }
                }
            }           
            
            @DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $ordenDia = Ordendia::findOrFail($request->id);
        $ordenDia->estado = 'Activo';
        $ordenDia->save();
    }

    public function inactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $ordenDia = Ordendia::findOrFail($request->id);
        $ordenDia->estado = 'Inactivo';
        $ordenDia->save();
    }

    public function probarEmail(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $control="MiembroOCS";
        //$codigoConvocatoria = 'IST-17J-OCS-SO-2020-008-C';
        $codigoConvocatoria = 'P';
        //$personas = $request->data_persona;

        $idConvocatoria = Convocatoria::where('codigo','=',$codigoConvocatoria)
        ->select('id')
        ->take(1)->get();
        $lstordendias = Ordendia::where('idconvocatoria','=',$idConvocatoria[0]['id'])
        ->orderBy('idconvocatoria','asc')->get();
        // dd($lstordendias);
        $data = ([
                'titulo' => '$request->titulo',
                'codigo' => '$request->codigo',
                'descripcion' => '$request->descripcion',
            ]);
        // foreach ($personas as $kep => $per) {
        //     Mail::to($per['EMail'])->send(new VueMail($data, $lstordendias, $control));
        // }
        
        Mail::to('drixio@outlook.es')->send(new VueMail($data, $lstordendias, $control));
    }
}