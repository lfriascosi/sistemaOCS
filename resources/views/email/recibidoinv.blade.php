<!DOCTYPE html>
<html>

<head>
    <style>
    #t01 {
        width: 80%;
        border-collapse: collapse;
    }

    #t01 th {
        background-color: #dddddd;
        border: 1px solid black;
    }

    #t01 td {
        border: 1px solid black;
        text-align: center;
    }
    </style>
</head>

<body>
	<h3 align="left">Estimado(a):</h3>
    <p align="justify">Ha sido(a) invitado(a) a la siguiente convocatoria en el punto del orden del día número <b>{{$data['numerador']}}</b>:<br/>
    <b>Título:</b> {{$data['titulo']}}<br/>
    <b>Código:</b> {{$data['codigo']}}<br/>
    <b>Descripción:</b> {{$data['descripcion']}}<br/>
    <b>Estado:</b> {{$data['estado']}}<br/>
    </p>
    
    <h3 align="center">Orden del día</h3>
    <table id="t01" ALIGN="center">
        <thead>
            <tr>
                <th><b>Nro</b></th>
                <th><b>Nombre</b></th>
            </tr>
        </thead>
        <tbody>

            @foreach($lstordendias as $key=>$o)
            <tr>
                <td width="50">{{ $o->numerador }}</td>
                <td>{{ $o->nombre }}</td>
            </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>