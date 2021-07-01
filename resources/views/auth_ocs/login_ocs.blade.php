@extends('auth_ocs.contenido')

@section('login')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card-group mb-0">
            <div class="card p-4">
                <form role="form" data-toggle="validator" action="logear" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <h1>Acceder</h1>
                        <p class="text-muted">Control de acceso al sistema</p>
                        @if(session()->get('msgEmail'))
                        <div class="text-danger">
                            <small>{{session()->get('msgEmail')}}</small>
                        </div>
                        @endif
                        <div class="input-group mb-4">
                            <span class="input-group-addon"><i class="icon-user"></i></span>
                            <input type="text" value="{{old('usuario')}}" name="usuario" id="usuario"
                                class="form-control" placeholder="Ingrese correo" required>
                        </div>
                        @if(session()->get('msgPassword'))
                        <div class="text-danger">
                            <small>{{session()->get('msgPassword')}}</small>
                        </div>
                        @endif
                        <div class="input-group mb-4">
                            <span class="input-group-addon"><i class="icon-lock"></i></span>
                            <input type="password" name="clave" id="clave" class="form-control"
                                placeholder="Ingrese número de indetificación" required>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary px-5"><i class="fa fa-sign-in"></i>&nbsp;&nbsp;&nbsp;Iniciar sesión</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
                <div class="card-body text-center">
                    <div>
                        <h2>Sistema OCS</h2>
                        <img src="img/logo.png" class="img-logo">
                        <p>Sistema de creación de convocatorias y actas del Instituto Superior Tecnológico 17 de
                            Julio, comprometidos con la acreditación.</p>
                        <a href="{{ url('/invitado') }}" class="btn btn-primary active mt-3">Ingresar como
                            invitado</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection