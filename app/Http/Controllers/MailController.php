<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\VueMail;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send(Request $request){
        $correo = $request->get('correo');
        $ordendias = $request->get('ordendias');
        $data = $request->get('data');
        Mail::to($correo)->send(new VueMail($data,$ordendias));
    }
}
