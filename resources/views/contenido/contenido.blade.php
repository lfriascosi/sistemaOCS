@extends('principal')
@section('contenido')
    <template v-if="menu==0">
        <baner></baner>
    </template>
    <template v-if="menu==10">
        <perfil></perfil>
    </template>
    <template v-if="menu==1">
        <redaccion-convocatoria></redaccion-convocatoria>
    </template>
    <template v-if="menu==2">
        <h1>Contenido del menu 2</h1>
    </template>
    <template v-if="menu==3">
        <h1>Contenido del menu 3</h1>
    </template>
    <template v-if="menu==4">
        <redaccion-acta></redaccion-acta>
    </template>
    <template v-if="menu==5">
        <h1>Contenido del menu 5</h1>
    </template>
    <template v-if="menu==6">
        <h1>Contenido del menu 6</h1>
    </template>
    <template v-if="menu==7">
        <h1>Contenido del menu 7</h1>
    </template>
    <template v-if="menu==8">
        <h1>Contenido del menu 8</h1>
    </template>
    <template v-if="menu==9">
        <h1>Contenido del menu 9</h1>
    </template>
@endsection