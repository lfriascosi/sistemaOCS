    
    <h1 align="center">{{$data['titulo']}}</h1>
    <img src="img/logo.png" width="50" height="50">
    <h2 align="center">CONVOCATORIA Nro {{$data['codigo']}}</h2>
    <p align="justify">{{$data['descripcion']}}</p>
    <h3 align="center">Orden del dia</h3>
    <table>
        <thead>
            <tr>
                <th><b>Nro</b></th>
                <th><b>Nombre</b></th>
            </tr>
        </thead>
    <tbody>
        
        @foreach($lstordendias as $key=>$o)
            <tr>
                <td>{{ $o->numerador }}</td>
                <td>{{ $o->nombre }}</td>
            </tr>
        @endforeach
        
    </tbody>
    </table>
