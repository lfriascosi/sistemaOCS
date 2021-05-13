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
    <h2 align="center">{{$data['titulo']}}</h2>
    <h2 align="center">CONVOCATORIA Nro {{$data['codigo']}}</h2>
    <p align="justify">{{$data['descripcion']}}</p>
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