<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Convocatoria;
use App\Models\Detalleconvocatoria;
use App\Models\Ordendia;
//
use App\Models\Persona;

class ConvocatoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index( Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $convocatorias = Convocatoria::select('convocatorias.id','convocatorias.iduser',
            'convocatorias.titulo','convocatorias.codigo','convocatorias.descripcion','convocatorias.estado')
            ->orderBy('convocatorias.id','desc')->paginate(10);
        }else{
            $convocatorias = Convocatoria::select('convocatorias.id','convocatorias.iduser',
            'convocatorias.titulo','convocatorias.codigo','convocatorias.descripcion','convocatorias.estado')
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
        $convocatoria = Convocatoria::select('convocatorias.id','convocatorias.iduser',
        'convocatorias.titulo','convocatorias.codigo','convocatorias.descripcion','convocatorias.estado')
        ->where('convocatorias.id','=',$id)
        ->orderBy('convocatorias.id','desc')->take(1)->get();

        return ['convocatoria' => $convocatoria];
    }

    public function obtenerDetalles( Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        $detalles = Detalleconvocatoria::join('convocatorias','detalle_convocatorias.idconvocatoria','=','convocatorias.id')
        ->select('detalle_convocatorias.idconvocatoria','detalle_convocatorias.iduser')
        ->where('detalle_convocatorias.idconvocatoria','=',$id)
        ->orderBy('detalle_convocatorias.iduser','asc')->get();

        return ['detalles' => $detalles];
    }

    public function obtenerOrdenDias( Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $id = $request->id;
        $orden_dias = Ordendia::join('convocatorias','orden_dias.idconvocatoria','=','convocatorias.id')
        ->select('orden_dias.nombre','orden_dias.condicion')
        ->where('orden_dias.idconvocatoria','=',$id)
        ->orderBy('orden_dias.id','asc')->get();

        return ['orden_dias' => $orden_dias];
    }

    //Consulta personas segunda Base
    public function indexP(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $personas = Persona::whereNotNull('email')->orderBy('id','desc')->get();
        }else{
            $personas = Persona::where('vista_personas.'.$criterio,'like','%'.$buscar.'%')->orderBy('id','desc')->get();
        }
        return ['personas'=>$personas];
    }
    public function buscarPersona( Request $request){
        if (!$request->ajax()) return redirect('/');
        $filtro = $request->filtro;
        $personas = Persona::where('num_documento','=',$filtro)
        ->select('num_documento','nombre','email')
        ->orderBy('nombre','asc')
        ->take(1)->get();

        return ['personas' => $personas];
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

            $convocatoria = new Convocatoria();
            $convocatoria->iduser = 'secretaria';
            //$convocatoria->iduser = \Auth::user()->num_documento;
            $convocatoria->titulo = $request->titulo;
            $convocatoria->codigo = $request->codigo;
            $convocatoria->descripcion = $request->descripcion;
            if(sizeof($personas)<=0){
                $convocatoria->estado = 'Guardada';
            }else{
                $convocatoria->estado = 'Enviada';
            }
            $convocatoria->save();

            //Array detalles Orden Dia
            foreach ($ordendias as $ord) {
                $ordendia = new Ordendia();
                $ordendia->idconvocatoria = $convocatoria->id;
                $ordendia->nombre = $ord['nombre'];
                $ordendia->condicion = 0;
                $ordendia->save();
            }

            DB::commit();

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
}
