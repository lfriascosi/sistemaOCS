<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        if($request->session()->get('user_id')=="" || $request->session()->get('user_name')==""){
            return view('auth_ocs/login_ocs');
        }else{
            $user_name = $request->session()->get('user_name');
            $capsule = array('user_name'=>$user_name);
            return view('/contenido/contenido')->with($capsule);
        }
        
    }
    public function login(Request $request)
    {
        $email=$request->usuario;
        $clave=$request->clave;
        $session_temp = Persona::where('EMail',$email)->get();
        if(count($session_temp)>0){
            $session = Persona::where('EMail',$email)->where('numeroIdentificacion',$clave)->get();
            if(count($session)>0){
                // $request->session()->put('user_id',$session[0]->numeroIdentificacion);
                // $request->session()->put('user_name',$session[0]->apellidos);
                $request->session()->put('user_array',$session);
                return redirect('/main');
            }else{
                return redirect('/')
                ->with(['msgPassword'=>'Ingrese número de indetificación valido'])
                ->withInput(request(['usuario']));
            }
        }else{
            return redirect('/')
            ->with(['msgEmail'=>'Ingrese un correo valido','msgPassword'=>'Ingrese una contraseña valida'])
            ->withInput(request(['usuario']));
        }
    }
    public function invitado(Request $request)
    {
        // return view('/invitado');
        return view('/welcome');
    }
    public function protect(Request $request)
    {
        // if($request->session()->get('user_id')=="" || $request->session()->get('user_name')==""){
        if($request->session()->get('user_array')==""){
            return redirect('/');
        }else{
            $user_name = $request->session()->get('user_array.0.apellidos');
            $capsule = array('user_name'=>$user_name);
            return view('/contenido/contenido')->with($capsule);
        }
    }
    public function logout(Request $request)
    {
        // $request->session()->forget('user_id');
        //  $request->session()->forget('user_name');
        $request->session()->forget('user_array');
        return redirect('/');
    }
    public function datos(Request $request){

        $cedula=$request->session()->get('user_array.0.numeroIdentificacion');
        $persona = Persona::where('numeroIdentificacion',$cedula)->get();
        return ['personas' => $persona];
    }
}