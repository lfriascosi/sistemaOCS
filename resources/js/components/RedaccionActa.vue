<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            <li class="breadcrumb-item">Acta</li>
            <li class="breadcrumb-item active">Redacción</li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>Actas
                    <button type="button" @click="mostrarDetalleActa()" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nueva
                    </button>
                    <button type="button" @click="mostrarModal()" class="btn btn-secondary"  data-toggle="modal" data-target="#modalResolucion">
                        <i class="icon-plus"></i>&nbsp;Detalle
                    </button>
                </div>
                <!--Listado Acta-->
                <template v-if="listado==1">
                    <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="codigo">Código</option>
                                    <option value="titulo">Título</option>
                                    <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarActa(1,buscar,criterio)" class="form-control"
                                    placeholder="Texto a buscar">
                                <button type="submit" @click="listarActa(1,buscar,criterio)" class="btn btn-primary" title="Buscar acta">
                                    <i class="fa fa-search"></i>Buscar
                                </button>
                                <button type="submit" @click="listarActa(1,'','')" class="btn btn-info" title="Ver todas las actas">
                                    <i class="fa fa-list"></i> Lista
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Redactor</th>
                                <th>Título</th>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody v-if="arrayActa.length">
                            <tr v-for="acta in arrayActa" :key="acta.id">
                                <td width="1" align="center" style="vertical-align:middle;">
                                    <button type="button" @click="verActa(acta.id)" class="btn btn-success btn-sm" title="Ver acta">
                                        <i class="icon-eye"></i>
                                    </button>
                                </td>
                                <td width="100" align="center"  style="vertical-align:middle;" v-text="acta.apellidos+' '+acta.nombres"></td>
                                <td width="220" align="justify" style="vertical-align:middle;" v-text="acta.titulo"></td>
                                <td width="200" align="center" style="vertical-align:middle;" v-text="acta.codigo"></td>
                                <td align="justify" v-text="acta.descripcion"></td>
                                <td width="1" align="center" style="vertical-align:middle;">
                                    <div>
                                        <span class="badge badge-success" v-text="acta.estado"></span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="6">
                                    No hay actas agregadas
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                    </div>
                </template>
                <!--Fin Listado Acta-->
                <!--Listado Agregar Acta-->
                <template v-else-if="listado==0">
                    <div class="card-body">
                        <b>Acta</b>
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <label for="">Título<span style="color:red;" v-show="titulo==''">(*Ingrese)</span></label>
                                <input type="text" placeholder="Ingrese el título de la acta" class="form-control" v-model="titulo">
                            </div>
                            <div class="col-md-3">
                                <label for="">Código<span style="color:red;" v-show="codigo==''">(*Ingrese)</span></label>
                                <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"
                                placeholder="Ingrese el código de la acta" class="form-control" v-model="codigo">
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Descripción<span style="color:red;" v-show="descripcion==''">(*Ingrese)</span></label>
                                    <textarea class="form-control" placeholder="Ingrese la descripción de la acta" v-model="descripcion" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <b>Seleccione convocatoria</b>
                        <div class="form-group row border">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Convocatoria <span style="color:red;" v-show="!arraySelectConvocatoria.length">(*seleccione)</span></label>
                                    <div class="form-inline">
                                        <input style="width : 40%;" type="text" class="form-control" v-model="codigoConvocatoria" @keyup.enter="buscarConvocatoriaI(codigoConvocatoria)" placeholder="Ingrese código convocatoria">
                                        <button @click="abrirModal()" class="btn- btn-primary" title="Buscar convocatoria" data-toggle="modal" data-target="#modalConvocatoria">⋮</button>
                                        <input type="text" readonly class="form-control" v-model="codigoVerifi">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarConvocatoria()" class="btn btn-success form-control btnagregar" title="Agregar Convocatoria">
                                        <i class="icon-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Redactor</th>
                                            <th>Título</th>
                                            <th>Código</th>
                                            <th>Descripción</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arraySelectConvocatoria.length">
                                        <tr v-for="convocatoria in arraySelectConvocatoria" :key="convocatoria.id">
                                            <td width="1" align="center" style="vertical-align:middle;">
                                                <button @click="eliminarconvocatoria()" type="button" class="btn btn-danger btn-sm" title="Eliminar convocatoria">
                                                    <i class="icon-close"></i>
                                                </button>
                                            
                                            </td>
                                            <td v-text="convocatoria.apellidos+' '+convocatoria.nombres"></td>
                                            <td v-text="convocatoria.titulo"></td>
                                            <td v-text="convocatoria.codigo"></td>
                                            <td v-text="convocatoria.descripcion"></td>
                                            <td v-text="convocatoria.estado"></td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">
                                                No hay convocatoria seleccionada
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <b>Orden del día</b>
                        <div class="form-group row border">&nbsp;
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Opciones</th>
                                            <th rowspan="2" style="vertical-align:middle;">Nro</th>
                                            <th rowspan="2" style="vertical-align:middle;">Nombre</th>
                                            <th rowspan="2" style="vertical-align:middle;">Descripción</th>
                                            <th rowspan="2" style="vertical-align:middle;">Resolución</th>
                                        </tr>
                                        <tr>
                                            <th>Descripción</th>
                                            <th>Resolución</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayVistaOrdendia.length">
                                        <tr v-for="detalle in arrayVistaOrdendia" :key="detalle.idordendia">
                                            <td width="90" align="center" style="vertical-align:middle;">
                                                <button v-if="detalle.descripcion=='Ninguna'" @click="abrirModalDescripcion(detalle.idordendia)" type="button"
                                                class="btn btn-success btn-sm" title="Agregar descripción" data-toggle="modal" data-target="#modalDescripcionN">
                                                    <i class="icon-plus"></i>
                                                </button>
                                                <button v-if="detalle.descripcion!='Ninguna'" @click="editarDescripcion(detalle.idordendia)" type="button"
                                                class="btn btn-warning btn-sm" title="Editar descripción" data-toggle="modal" data-target="#modalDescripcionN">
                                                    <i class="icon-pencil"></i>
                                                </button>
                                                <button v-if="detalle.descripcion!='Ninguna'" @click="eliminarDescripcion(detalle.idordendia)" type="button"
                                                class="btn btn-danger btn-sm" title="Eliminar descripción">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td width="90" align="center" style="vertical-align:middle;">
                                                <button v-if="detalle.resolucion=='Ninguna'" @click="abrirModalResolucion(detalle.idordendia)" type="button"
                                                class="btn btn-primary btn-sm" title="Agregar resolución" data-toggle="modal" data-target="#modalResolucion">
                                                    <i class="icon-plus"></i>
                                                </button>
                                                <button v-if="detalle.resolucion!='Ninguna'" @click="editarResolucion(detalle.idordendia)" type="button"
                                                class="btn btn-warning btn-sm" title="Editar resolución" data-toggle="modal" data-target="#modalResolucion">
                                                    <i class="icon-pencil"></i>
                                                </button>
                                                <button v-if="detalle.resolucion!='Ninguna'" @click="eliminarResolucion(detalle.idordendia)" type="button"
                                                class="btn btn-danger btn-sm" title="Eliminar resoluciones">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td align="center" style="vertical-align:middle;" v-text="detalle.numerador"></td>
                                            <td v-if="buscarComa(detalle.idordendia)" style="vertical-align:middle;" v-text="detalle.nombre"></td>
                                            <td v-else>
                                                <input v-model="detalle.nombrealternativo" type="text" value="" class="form-control" placeholder="Ingrese un nombre">
                                            </td>
                                            <td v-text="detalle.descripcion"></td>
                                            <td v-text="detalle.resolucion"></td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">
                                                No hay orden del día agregada
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <b>Seleccione usuarios dirigidos</b>
                        <div class="form-group row border">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Usuario <span style="color:red;" v-show="personaUser==''">(*seleccione)</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="docUser" @keyup.enter="buscarPersona()" placeholder="Ingrese cédula">
                                        <button @click="abrirModalPersona()" class="btn- btn-primary" title="Buscar Persona" data-toggle="modal" data-target="#modalPersona">⋮</button>
                                        <input type="text" readonly class="form-control" v-model="personaUser">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarPersona()" class="btn btn-success form-control btnagregar" title="Agregar Persona">
                                        <i class="icon-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Cédula</th>
                                            <th>Nombre</th>
                                            <th>Correo</th>
                                            <th>Perfil</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayPersona.length">
                                        <tr v-for="(persona,index) in arrayPersona" :key="persona.id">
                                            <td width="1" align="center" style="vertical-align:middle;">
                                                <button @click="eliminarPersona(index)" type="button" class="btn btn-danger btn-sm" title="Eliminar Persona">
                                                    <i class="icon-close"></i>
                                                </button>
                                            
                                            </td>
                                            <td v-text="persona.cedula"></td>
                                            <td v-text="persona.nombre"></td>
                                            <td v-text="persona.EMail"></td>
                                            <td v-text="persona.perfil"></td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                No hay personas agregadas
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">                      
                            <div class="col-md-12">
                                <div v-show="errorActa" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjActa" :key="error" v-text="error"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalleActa()" class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarActa()">Registrar Acta</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!--Fin Listado Agregar Acta-->
                <!--Ver Detalle Convocatoria-->
                <template v-else-if="listado==2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for=""><b>Título</b></label>
                                    <p v-text="titulo"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for=""><b>Código</b></label>
                                <p v-text="codigo"></p>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><b>Descripción</b></label>
                                    <p v-text="descripcion"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">&nbsp;
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th colspan="2"><b>Orden día</b></th>
                                        </tr>
                                        <tr>
                                            <th><b>Nro</b></th>
                                            <th><b>Nombre</b></th>                                       
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr align="center" v-for="(detalle, index) in arrayDetalle" :key="detalle.id" v-bind:index="index">
                                            <td >{{index+1}}</td>
                                            <td v-text="detalle.nombre"/>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="4">
                                                No hay orden del dia agregados
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row border">&nbsp;
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th colspan="1"><b>Orden Día</b></th>
                                            <th colspan="5"><b>Usuarios Invitados</b></th>
                                        </tr>
                                        <tr>
                                            <th><b>Nro</b></th>
                                            <th><b>Cédula</b></th>
                                            <th><b>Apellidos</b></th>
                                            <th><b>Nombres</b></th>
                                            <th><b>Correo</b></th>
                                            <th><b>Perfil</b></th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalleInvitado.length">
                                        <tr align="center" v-for="detalleInv in arrayDetalleInvitado" :key="detalleInv.numeroIdentificacion">
                                            <td v-text="detalleInv.numerador"/>
                                            <td v-text="detalleInv.numeroIdentificacion"/>
                                            <td v-text="detalleInv.apellidos"/>
                                            <td v-text="detalleInv.nombres"/>
                                            <td v-text="detalleInv.EMail"/>
                                            <td v-text="detalleInv.perfil"/>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">
                                                No hay usuarios enviados
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row border">&nbsp;
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th colspan="4"><b>Usuarios Dirigidos</b></th>
                                        </tr>
                                        <tr>
                                            <th><b>Cédula</b></th>
                                            <th><b>Nombre</b></th>
                                            <th><b>Correo</b></th>
                                            <th><b>Perfil</b></th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalleEnvio.length">
                                        <tr align="center" v-for="detalleEnv in arrayDetalleEnvio" :key="detalleEnv.iduser">
                                            <td v-text="detalleEnv.iduser"/>
                                            <td v-text="detalleEnv.apellidos+' '+detalleEnv.nombres"/>
                                            <td v-text="detalleEnv.EMail"/>
                                            <td v-text="detalleEnv.perfil"/>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="4">
                                                No hay usuarios enviados
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row border">&nbsp;
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th colspan="5"><b>Redactado por:</b></th>
                                        </tr>
                                        <tr>
                                            <th><b>Cédula</b></th>
                                            <th><b>Apellido</b></th>
                                            <th><b>Nombre</b></th>
                                            <th><b>Correo</b></th>
                                            <th><b>Perfil</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr align="center">
                                            <td v-text="cedulaRedactor"/>
                                            <td v-text="apellidoRedactor"/>
                                            <td v-text="nombreRedactor"/>
                                            <td v-text="emailRedactor"/>
                                            <td v-text="perfilRedactor"/>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalleActa()" class="btn btn-secondary">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!--Fin Ver Detalle Convocatoria-->
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal Convocatoria agregar/actualizar-->
        <div class="modal fade" id="modalConvocatoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioA">
                                        <option value="codigo">Código</option>
                                        <option value="titulo">Título</option>
                                        <option value="descripcion">Descripción</option>
                                    </select>
                                    <input type="text" v-model="buscarA" @keyup.enter="listarConvocatoriaS(buscarA,criterioA)" class="form-control"
                                        placeholder="Texto a buscar">
                                    <button type="submit" @click="listarConvocatoriaS(buscarA,criterioA)" class="btn btn-primary" :disabled="!buscarA"><i class="fa fa-search"></i>
                                        Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-response">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Redactor</th>
                                        <th>Título</th>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayListConvocatoria.length">
                                    <tr v-for="convocatoria in arrayListConvocatoria" :key="convocatoria.id">
                                        <td width="1" align="center" style="vertical-align:middle;">
                                            <button type="button"  v-if="tipoAccionInv==0" @click="agregarConvocatoriaModal(convocatoria)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                        </td>
                                        <td width="100" align="center"  style="vertical-align:middle;" v-text="convocatoria.apellidos+' '+convocatoria.nombres"></td>
                                        <td v-text="convocatoria.titulo"></td>
                                        <td v-text="convocatoria.codigo"></td>
                                        <td v-text="convocatoria.descripcion"></td>
                                        <td v-text="convocatoria.estado"></td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                        <tr>
                                            <td colspan="6">
                                                No existe convocatoria habilitada
                                            </td>
                                        </tr>
                                    </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
        <!--Inicio del modal Descripcion -->
        <div v-if="valModal">
            <div class="modal fade" id="modalDescripcionN" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close" data-dismiss="modal">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <textarea type="textarea" v-model="descripcionActa" class="form-control"
                                    placeholder="Ingrese la descripción deacuerdo al punto del orden día seleccionado." rows="6"></textarea>
                                </div>&nbsp;
                                <div class="col-md-12" style="border-top: 1px solid #c2cfd6;">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <b>Seleccione un punto adisional</b>
                                            <button v-if="!valBoolean" type="button" class="close" @click="cambiarBoolean()" aria-label="Close">
                                                <span aria-hidden="true">+</span>
                                            </button>
                                            <button v-if="valBoolean" type="button" class="close" @click="cambiarBoolean()" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="panel-body" v-if="valBoolean">
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <div v-for="check in arrayCheckbox" :key="check.id">
                                                            <label>{{check.numerador}}</label>
                                                            <input type="checkbox" v-model="arrayCheckboxSelect" :value="check"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="guardarDescripcion()" data-dismiss="modal">Guardar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </div>
        <!--Fin del modal-->
        <!--Inicio del modal Resolucion -->
        <div v-if="valModal">
            <div class="modal fade" id="modalResolucion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close" data-dismiss="modal">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <textarea v-model="descripcionResolucion" type="textarea" class="form-control"
                                        placeholder="Ingrese la descripción deacuerdo al punto del orden día seleccionado." rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button type="button" @click="agregarResolucion()" class="btn btn-success form-control btnagregarR" title="Agregar resolución">
                                            <i class="icon-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Opción</th>
                                                <th>Código</th>
                                                <th>Descripción</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayResolucionVista.length">
                                            <tr v-for="(resolucion,index) in arrayResolucionVista" :key="resolucion.id">
                                                <td width="10" align="center" style="vertical-align:middle;">
                                                    <button @click="eliminarResolucionVista(index)" type="button" class="btn btn-danger btn-sm" title="Eliminar resolución">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td width="250" align="center" style="vertical-align:middle;" v-text="resolucion.codigo"></td>
                                                <td align="justify" style="vertical-align:middle;" v-text="resolucion.descripcion"></td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="4">
                                                    No hay resoluciones agregadas
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModalResolucion()" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="guardarResolucion()" data-dismiss="modal">Guardar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </div>
        <!--Fin del modal-->
        <!--Inicio del modal Persona agregar-->
        <div class="modal fade" id="modalPersona" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioP">
                                        <option value="apellidos">Apellido</option>
                                        <option value="nombres">Nombre</option>
                                        <option value="EMail">Correo</option>
                                        <option value="perfil">Perfil</option>
                                    </select>
                                    <input type="text" v-model="buscarP" @keyup.enter="listarPersona(buscarP,criterioP)" class="form-control"
                                        placeholder="Texto a buscar">
                                    <button type="submit" @click="listarPersona(buscarP,criterioP)" class="btn btn-primary"><i class="fa fa-search"></i>
                                        Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-response">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Cédula</th>
                                        <th>Apellidos</th>
                                        <th>Nombres</th>
                                        <th>Correo</th>
                                        <th>Perfil</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayPersonaB.length">
                                    <tr v-for="persona in arrayPersonaB" :key="persona.id">
                                        <td width="1" align="center" style="vertical-align:middle;">
                                            <button type="button"  v-if="tipoAccionInv==0" @click="agregarPersonaModal(persona)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                            <button type="button"  v-if="tipoAccionInv==1" @click="agregarInvitado(persona)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                        </td>
                                        <td v-text="persona.numeroIdentificacion"></td>
                                        <td v-text="persona.apellidos"></td>
                                        <td v-text="persona.nombres"></td>
                                        <td v-text="persona.EMail"></td>
                                        <td v-text="persona.perfil"></td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                        <tr>
                                            <td colspan="6">
                                                No existe usuario
                                            </td>
                                        </tr>
                                    </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>
    export default {
        data (){
            return {
                arrayEjemplo:[],
                arrayConvocatoria :[],
                arrayDetalleEnvio :[],
                arrayDetalleInvitado :[],
                arrayOrdenDia : [],
                arrayUser:[],
                arrayPersona:[],
                arrayPersonaB:[],
                arrayPersonaInv:[],
                nombre :'',
                docUser:'',
                personaUser:'',
                emailPersona:'',
                perfilPersona:'',
                errorActa : 0,
                errorMostrarMsjActa : [],
                tipoAccionInv : 0,
                idordendia:'',
                
                //Inicio
                arrayActa :[],
                arrayDetalleActa :[],
                arrayFirmaActa :[],
                arrayListConvocatoria :[],
                arraySelectConvocatoria :[],
                arrayTempConvocatoria :[],
                arrayCheckbox:[],
                arrayCheckboxSelect:[],
                arraySelectOrdendia :[],
                arrayDescripcion:[],
                arrayResolucion:[],
                arrayResolucionVista:[],
                arrayVistaOrdendia :[],
                codigoConvocatoria:'',
                codigoVerifi:'',
                codigo :'',
                titulo :'',
                descripcion :'',
                descripcionActa:'',
                estado :'',
                cedulaRedactor:'',
                apellidoRedactor:'',
                nombreRedactor:'',
                emailRedactor:'',
                perfilRedactor:'',
                valBoolean:false,
                aidConvocatoriaActa:'',
                valModal:false,
                descripcionResolucion:'',
                descripcionResolucion:'',
                //Fin
                
                listado: 1,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                pagination : {
                    'total' :0,
                    'current_page' :0,
                    'per_page' :0,
                    'last_page' :0,
                    'from' :0,
                    'to' :0,                    
                },
                offset :3,
                criterio : 'codigo',
                buscar : '',
                criterioA:'codigo',
                buscarA:'',
                criterioP:'apellidos',
                buscarP:'',
            }
        },
        computed : {
            isActived : function(){
                return this.pagination.current_page;
            },
            pagesNumber : function(){
                if(!this.pagination.to){
                    return [];
                }
                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }
                var pagesArray = [];
                while (from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
            mayus : function(){
                let me=this;
                if(me.codigo!=''){
                    me.codigo=me.codigo.toUpperCase();
                }
            }
        },
        methods : {
            listarActa (page,buscar,criterio){
                let me=this;
                var url='/acta?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta=response.data;
                    me.arrayActa = respuesta.actas.data;
                    me.pagination=respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            listarPersona (buscar,criterio){
                let me=this;
                var url='/convocatoria/personas?buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta=response.data;
                    me.arrayPersonaB = respuesta.personas;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            buscarPersona(){
                let me=this;
                var url = 'convocatoria/buscarPersona?filtro='+me.docUser;
                axios.get(url).then(function (response) {
                    var respuesta=response.data;
                    me.arrayUser=respuesta.personas;

                    if (me.arrayUser.length>0) {
                        me.docUser=me.arrayUser[0]['numeroIdentificacion'];
                        me.personaUser=me.arrayUser[0]['apellidos']+' '+me.arrayUser[0]['nombres'];
                        me.perfilPersona=me.arrayUser[0]['perfil'];
                        me.emailPersona=me.arrayUser[0]['EMail'];
                    }else{
                        me.personaUser='No existe persona';
                    }
                })
                .catch(function (error){
                    console.log(error);
                })
            },
            cambiarPagina(page,buscar,criterio){
                let me= this;
                me.pagination.current_page=page;
                me.listarConvocatoria(page,buscar,criterio);
            },
            encuentra(descripcion){
                var sw=0;
                for (var i = 0; i < this.arrayResolucion.length; i++) {
                    if (this.arrayResolucion[i].descripcion==descripcion) {
                        sw=true;
                    }
                }
                return sw;
            },
            encuentraPersona(cedula){
                var sw=0;
                for (var i = 0; i < this.arrayPersona.length; i++) {
                    if (this.arrayPersona[i].cedula==cedula) {
                        sw=true;
                    }
                }
                return sw;
            },
            eliminarResolucionVista(index){
                let me=this;
                //me.eliminarInvitado(me.arrayOrdenDia[index].nro);
                me.arrayResolucionVista.splice(index,1);
                /*for (var i = 0; i < me.arrayOrdenDia.length; i++) {
                    me.arrayOrdenDia[i].nro=i+1;
                }*/
            },
            eliminarPersona(index){
                let me=this;
                me.arrayPersona.splice(index,1);
            },
            metodoSortResolu(){
                let me = this;
                var arrayTemp=[];
                me.arrayResolucion.sort(function (o1,o2) {
                    if (o1.idordendia > o2.idordendia) { //comparación lexicogŕafica
                        return 1;
                    } else if (o1.idordendia < o2.idordendia) {
                        return -1;
                    } 
                    return 0;
                });
                for (var i = 0; i < me.arrayResolucion.length; i++) {
                    me.arrayResolucion[i].codigo='';
                }
            },
            ordenarCodigoResol(){
                let me=this;
                //me.metodoSortResolu();
                me.codigo=me.codigo.toUpperCase();
                var pos = me.codigo.length-2;
                var cadCe='';
                var codigoVal='';
                var contV=0;
                var contC=0;
                var contV2=false;
                var contC2=0;
                for (var i = 0; i < me.arrayResolucion.length; i++) {
                    contV=0;
                    for (var j = 0; j < me.arrayResolucion.length; j++) {
                        if(me.arrayResolucion[i].descripcion==me.arrayResolucion[j].descripcion){
                            contV++;
                        }
                    }
                    if(contV==1){
                        contC++;
                    }
                }
                for (var i = 0; i < me.arrayResolucionVista.length; i++) {
                    contV2=false;
                    for (var j = 0; j < me.arrayResolucion.length; j++) {
                        if(me.arrayResolucionVista[i].codigo==me.arrayResolucion[j].codigo){
                            contV2=true;
                        }
                    }
                    if(contV2){
                        contC2++;
                    }
                }
                var contador=(contC-contC2+me.arrayResolucionVista.length+1);

                if(contador<9){
                    cadCe='/00';
                }else{
                    cadCe='/0';
                }
                if(me.codigo.includes('-A',pos)){
                    codigoVal=me.codigo.slice(0,pos)+cadCe+(contador);
                }else{
                    codigoVal=me.codigo+cadCe+(contador);
                }
                return codigoVal;
            },
            encuentraResolucion(descripcion){
                var sw=0;
                for (var i = 0; i < this.arrayResolucionVista.length; i++) {
                    if (this.arrayResolucionVista[i].descripcion==descripcion) {
                        sw=true;
                    }
                }
                return sw;
            },
            agregarResolucion(){
                let me=this;
                if(me.descripcionResolucion==''){
                    Swal.fire({
                        title: 'Error...',
                        text: 'Agrege una descripción!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }else{
                    if(!me.encuentraResolucion(me.descripcionResolucion)){
                        //for (var i = 0; i < me.arrayCheckboxSelect.length; i++) {
                            me.arrayResolucionVista.push({
                                //idordendia: me.arrayCheckboxSelect[i].id,
                                codigo: me.ordenarCodigoResol(),
                                descripcion: me.descripcionResolucion
                            });
                        //}
                        me.descripcionResolucion="";
                        //me.llenarVistaDescripcionResolucion();
                    }else{
                        Swal.fire({
                            title: 'Error...',
                            text: 'La descripción ya existe!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            },
            agregarPersona(){
                let me=this;
                if(me.personaUser!=''){
                    if(me.encuentraPersona(me.docUser)){
                        Swal.fire({
                            title: 'Error...',
                            text: 'La persona ya se encuentra agregado!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        me.docUser="";
                        me.personaUser="";
                    }else{
                        me.arrayPersona.push({
                        nombre: me.personaUser,
                        EMail: me.emailPersona,
                        cedula: me.docUser,
                        perfil: me.perfilPersona
                        });
                        me.docUser="";
                        me.personaUser="";
                    }                    
                }
            },
            agregarPersonaModal( data = []){
                let me=this;
                if(me.encuentraPersona(data['numeroIdentificacion'])){
                    Swal.fire({
                        title: 'Error...',
                        text: 'La perona ya se encuentra agregado!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    me.buscarA="";
                }else{
                    me.arrayPersona.push({
                    cedula: data['numeroIdentificacion'],
                    nombre: data['apellidos']+' '+data['nombres'],
                    EMail: data['EMail'],
                    perfil: data['perfil']
                    });
                }
            },
            agregarConvocatoriaModal(data =[]){
                let me=this;
                if(me.codigo!=''){
                    if(me.arraySelectConvocatoria.length>0){
                        Swal.fire({
                            title: 'Error...',
                            text: 'Solo se puede seleccionar una convocatoria!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        me.buscarA="";
                    }else{
                        me.arraySelectConvocatoria.push({
                        id: data['id'],
                        titulo: data['titulo'],
                        codigo: data['codigo'],
                        descripcion: data['descripcion'],
                        estado: data['estado'],
                        iduser: data['iduser'],
                        apellidos: data['apellidos'],
                        nombres: data['nombres'],
                        EMail: data['EMail'],
                        perfil: data['perfil']
                        });
                        var url='/convocatoria/obtenerOrdenDias?id='+me.arraySelectConvocatoria[0]['id'];
                        axios.get(url).then(function (response) {
                            var respuesta=response.data;
                            me.arraySelectOrdendia = respuesta.orden_dias;
                            me.inicializarOrdenDiaSelect();
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                    }
                }else{
                    Swal.fire({
                        title: 'Error...',
                        text: 'Debe ingresar un código de acta!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            agregarConvocatoria(){
                let me=this;
                if(me.codigoVerifi=='' || me.codigoVerifi=='Código inválido'){
                    Swal.fire({
                        title: 'Error...',
                        text: 'Ingrese un código válido de convocatoria!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }else{
                    //if(me.codigo!=''){
                        if(me.arraySelectConvocatoria.length==0){
                            me.arraySelectConvocatoria=me.arrayTempConvocatoria;
                            me.arrayTempConvocatoria=[];
                            me.codigoConvocatoria='';
                            me.codigoVerifi='';

                            var url='/convocatoria/obtenerOrdenDias?id='+me.arraySelectConvocatoria[0]['id'];
                            axios.get(url).then(function (response) {
                                var respuesta=response.data;
                                me.arraySelectOrdendia = respuesta.orden_dias;
                                me.inicializarOrdenDiaSelect();
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                        }else{
                            Swal.fire({
                                title: 'Error...',
                                text: 'Solo se puede seleccionar una convocatoria!',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                        /*
                    }else{
                        Swal.fire({
                            title: 'Error...',
                            text: 'Debe ingresar un código de acta!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                    */
                }                
            },
            eliminarconvocatoria(){
                let me=this;
                me.arraySelectConvocatoria=[];
                me.arraySelectOrdendia=[];
                me.arrayVistaOrdendia=[];
                me.codigoVerifi='';
            },
            combinarDescripcion(){
                let me=this;
                var arrayTemp=[];
                var idT='';
                var numeradorT='';
                var nombreT='';
                var descripcionT='';
                var cont=0;
                var cont2=0;
                var cade='';
                for (var i = 0; i < me.arrayVistaOrdendia.length; i++) {
                    cont=0;
                    idT='';
                    numeradorT='';
                    nombreT='';
                    descripcionT='';
                    for (var j = 0; j < me.arrayVistaOrdendia.length; j++) {
                        if(me.arrayVistaOrdendia[i].descripcion!='Ninguna' && me.arrayVistaOrdendia[i].descripcion==me.arrayVistaOrdendia[j].descripcion){
                            cont++;
                            if(cont>1){
                                idT+= ',';
                                numeradorT+= ',';
                                nombreT+= ',';
                            }
                            idT+= me.arrayVistaOrdendia[j].idordendia;
                            numeradorT+= me.arrayVistaOrdendia[j].numerador;
                            nombreT+= me.arrayVistaOrdendia[j].nombre;
                            descripcionT=me.arrayVistaOrdendia[j].descripcion;
                        }
                    }
                    if(cont>1){
                        if(!me.buscarD(idT,arrayTemp)){
                            var arrayId=idT.split(",");
                            cont2=0;
                            cade='';
                            for (var q = 0; q < me.arrayResolucion.length; q++) {
                                if(arrayId[0]==me.arrayResolucion[q].idordendia){
                                    if(cont2>0){
                                        cade+=',';
                                    }else{
                                        me.codigo=me.codigo.toUpperCase();
                                        var pos = me.codigo.length-2;
                                        if(me.codigo.includes('-A',pos)){
                                            cade+=me.codigo.slice(0,pos)+'/(';
                                        }else{
                                            cade+=me.codigo+'/(';
                                        }
                                    }
                                    cade+=me.arrayResolucion[q].codigo.slice(-3);
                                    cont2++;
                                }
                            }
                            if(cade==''){
                                cade='Ninguna';
                            }else{
                                cade+=')';
                            }
                            arrayTemp.push({
                                idordendia: idT,
                                numerador: numeradorT,
                                nombre: nombreT,
                                nombrealternativo: '',
                                descripcion: descripcionT,
                                resolucion: cade
                            });
                        }
                    }else{
                        cont2=0;
                        cade='';
                        for (var q = 0; q < me.arrayResolucion.length; q++) {
                            if(me.arrayVistaOrdendia[i].idordendia==me.arrayResolucion[q].idordendia){
                                if(cont2>0){
                                    cade+=',';
                                }else{
                                    me.codigo=me.codigo.toUpperCase();
                                    var pos = me.codigo.length-2;
                                    if(me.codigo.includes('-A',pos)){
                                        cade+=me.codigo.slice(0,pos)+'/(';
                                    }else{
                                        cade+=me.codigo+'/(';
                                    }
                                }
                                cade+=me.arrayResolucion[q].codigo.slice(-3);
                                cont2++;
                            }
                        }
                        if(cade==''){
                            cade='Ninguna';
                        }else{
                            cade+=')';
                        }
                        arrayTemp.push({
                            idordendia: me.arrayVistaOrdendia[i].idordendia,
                            numerador: me.arrayVistaOrdendia[i].numerador,
                            nombre: me.arrayVistaOrdendia[i].nombre,
                            nombrealternativo:'',
                            descripcion: me.arrayVistaOrdendia[i].descripcion,
                            resolucion: cade
                        });
                    }
                }
                me.arrayVistaOrdendia=[];
                me.arrayVistaOrdendia=arrayTemp;
                //me.ordenarCodigoResol();
            },
            inicializarOrdenDiaSelect(){
                let me=this;
                me.arrayVistaOrdendia=[];
                for (var i = 0; i < me.arraySelectOrdendia.length; i++) {
                    me.arrayVistaOrdendia.push({
                        idordendia: me.arraySelectOrdendia[i]['id']+'',
                        numerador: me.arraySelectOrdendia[i]['numerador']+'',
                        nombre: me.arraySelectOrdendia[i]['nombre'],
                        nombrealternativo:'',
                        descripcion: 'Ninguna',
                        resolucion: 'Ninguna'
                    });
                }
            },
            llenarVistaDescripcionResolucion(){
                let me=this;
                me.inicializarOrdenDiaSelect();
                var cont=0;
                var cade='';
                for (var i = 0; i < me.arrayVistaOrdendia.length; i++) {
                    for (var j = 0; j < me.arrayDescripcion.length; j++) {
                        if(me.arrayVistaOrdendia[i].idordendia==me.arrayDescripcion[j].idordendia){
                            me.arrayVistaOrdendia[i].descripcion=me.arrayDescripcion[j].descripcion;
                        }
                    }
                    cont=0;
                    cade='';
                    for (var q = 0; q < me.arrayResolucion.length; q++) {
                        if(me.arrayVistaOrdendia[i].idordendia==me.arrayResolucion[q].idordendia){
                            if(cont>0){
                                cade+=',';
                            }else{
                                me.codigo=me.codigo.toUpperCase();
                                var pos = me.codigo.length-2;
                                if(me.codigo.includes('-A',pos)){
                                    cade+=me.codigo.slice(0,pos)+'/(';
                                }else{
                                    cade+=me.codigo+'/(';
                                }
                            }
                            cade+=me.arrayResolucion[q].codigo.slice(-3);
                            cont++;
                        }
                    }
                    if(cade!=''){
                        me.arrayVistaOrdendia[i].resolucion=cade+')';
                    }
                }
            },
            guardarDescripcion(){
                let me=this;
                if(me.descripcionActa==''){
                    Swal.fire({
                        title: 'Error...',
                        text: 'Debe de ingresar una descripción!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }else{
                    if(me.arrayCheckboxSelect.length>0){
                        //me.llenarVistaDescripcionResolucion();
                        var condicion=false;
                        for (var i = 0; i < me.arrayCheckboxSelect.length; i++) {
                            condicion=false;
                            for (var j = 0; j < me.arrayVistaOrdendia.length; j++) {
                                if(me.arrayCheckboxSelect[i].id==me.arrayVistaOrdendia[j].idordendia){
                                    for (var q = 0; q < me.arrayDescripcion.length; q++) {
                                        if(me.arrayCheckboxSelect[i].id==me.arrayDescripcion[q].idordendia){
                                            condicion=true;
                                            me.arrayDescripcion[q].descripcion=me.descripcionActa;
                                        }
                                    }
                                    if(condicion==false){
                                        me.arrayDescripcion.push({
                                            idordendia: me.arrayVistaOrdendia[j].idordendia,
                                            descripcion: me.descripcionActa,
                                            nombrealternativo:''
                                        });
                                    }
                                }
                            }
                        }
                    }
                    me.llenarVistaDescripcionResolucion();
                    me.combinarDescripcion();
                    me.descripcionActa='';
                    me.arrayCheckbox=[];
                    me.arrayCheckboxSelect=[];
                }
            },
            guardarResolucion(){
                let me=this;
                for (var i = 0; i < me.arrayCheckboxSelect.length; i++) {
                    for (var j = me.arrayResolucion.length; j > 0 ; j--) {
                        if(me.arrayCheckboxSelect[i].id==me.arrayResolucion[j-1].idordendia){
                            me.arrayResolucion.splice(j-1,1);
                        }
                    }
                }
                for (var i = 0; i < me.arrayResolucionVista.length; i++) {
                    for (var j = 0; j < me.arrayCheckboxSelect.length; j++) {
                        me.arrayResolucion.push({
                            idordendia: me.arrayCheckboxSelect[j].id,
                            codigo:me.arrayResolucionVista[i].codigo,
                            descripcion: me.arrayResolucionVista[i].descripcion
                        });
                    }
                }
                me.arrayResolucionVista=[];
                me.llenarVistaDescripcionResolucion();
                //me.ordenarCodigoResol();
                me.combinarDescripcion();
                me.arrayCheckbox=[];
                me.arrayCheckboxSelect=[];
            },
            metodoSortOrdenDia(){
                let me = this;
                me.arraySelectOrdendia.sort(function (o1,o2) {
                    if (o1.numerador > o2.numerador) { //comparación lexicogŕafica
                        return 1;
                    } else if (o1.numerador < o2.numerador) {
                        return -1;
                    } 
                    return 0;
                });
            },
            eliminarDescripcion(id){
                let me=this;
                var arrayId=[];
                var cadenaId=id;
                var arrayNro=[];
                var arrayNombre=[];
                var arrayTemp=[];
                var pos=0;
                arrayId=cadenaId.split(",");
                var arrayPos=[];
                for (var i = 0; i < arrayId.length; i++) {
                    for (var j = 0; j < me.arrayDescripcion.length; j++) {
                        if(arrayId[i]==me.arrayDescripcion[j].idordendia){
                            me.arrayDescripcion.splice(j,1);
                        }
                    }
                }
                me.llenarVistaDescripcionResolucion();
                me.combinarDescripcion();
            },
            eliminarResolucion(index){
                let me=this;
                this.llenarCheckbox(index);
                for (var i = 0; i < me.arrayCheckboxSelect.length; i++) {
                    for (var j = me.arrayResolucion.length; j > 0 ; j--) {
                        if(me.arrayCheckboxSelect[i].id==me.arrayResolucion[j-1].idordendia){
                            me.arrayResolucion.splice(j-1,1);
                        }
                    }
                }
                me.llenarVistaDescripcionResolucion();
                me.combinarDescripcion();
                me.arrayCheckbox=[];
                me.arrayCheckboxSelect=[];
            },
            editarDescripcion(id){
                let me=this;
                me.tituloModal = 'Editar descripción';
                me.valBoolean=false;
                me.arrayCheckbox=[];
                me.arrayCheckboxSelect=[];
                for (var i = 0; i < me.arrayVistaOrdendia.length; i++) {
                    if(me.arrayVistaOrdendia[i].idordendia==id){
                        me.descripcionActa=me.arrayVistaOrdendia[i].descripcion;
                        me.llenarCheckbox(id);
                    }
                }
            },
            editarResolucion(id){
                let me=this;
                me.tituloModal = 'Editar resolción';
                me.arrayResolucionVista=[];
                me.arrayCheckbox=[];
                me.arrayCheckboxSelect=[];
                me.llenarCheckbox(id);
                for (var i = 0; i < me.arrayCheckboxSelect.length; i++) {
                    for (var j = 0; j < me.arrayResolucion.length; j++) {
                        if(me.arrayCheckboxSelect[i].id==me.arrayResolucion[j].idordendia){
                            if(!me.buscarR(me.arrayResolucion[j].descripcion,me.arrayResolucionVista)){
                                me.arrayResolucionVista.push({
                                    //idordendia: me.arrayResolucion[j].idordendia,
                                    codigo:me.arrayResolucion[j].codigo,
                                    descripcion: me.arrayResolucion[j].descripcion
                                })
                            }
                        }
                    }
                }
            },
            cambiarBoolean(){
                let me=this;
                if(me.valBoolean==true){
                    me.valBoolean=false;
                }else{
                    me.valBoolean=true;
                }
            },
            llenarCheckbox(id){
                let me=this;
                me.arrayCheckbox=[];
                me.arrayCheckboxSelect=[];
                var arrayId=[];
                var cadenaId='';
                var arrayNro=[];
                var cadenaNro='';
                for (var i = 0; i < me.arrayVistaOrdendia.length; i++) {
                    if(me.arrayVistaOrdendia[i].idordendia==id){
                        cadenaId=id+'';
                        arrayId=cadenaId.split(",");
                        if(arrayId.length>1){
                            cadenaNro=me.arrayVistaOrdendia[i]['numerador']+'';
                            arrayNro=cadenaNro.split(",");
                            for (var j = 0; j < arrayId.length; j++) {
                                me.arrayCheckboxSelect.push({
                                    id: arrayId[j],
                                    numerador: arrayNro[j]
                                })
                            }
                        }else{
                            me.arrayCheckboxSelect.push({
                                id: me.arrayVistaOrdendia[i].idordendia,
                                numerador: me.arrayVistaOrdendia[i]['numerador']
                            })
                        }
                    }else{
                        if(me.arrayVistaOrdendia[i]['descripcion']=='Ninguna'){
                            cadenaId=me.arrayVistaOrdendia[i].idordendia+'';
                            arrayId=cadenaId.split(",");
                            if(arrayId.length>1){
                                cadenaNro=me.arrayVistaOrdendia[i]['numerador']+'';
                                arrayNro=cadenaNro.split(",");
                                for (var j = 0; j < arrayId.length; j++) {
                                    me.arrayCheckbox.push({
                                        id: arrayId[j],
                                        numerador: arrayNro[j]
                                    })
                                }
                            }else{
                                me.arrayCheckbox.push({
                                    id: me.arrayVistaOrdendia[i].idordendia,
                                    numerador: me.arrayVistaOrdendia[i]['numerador']
                                });
                            }
                        }
                    }
                }
            },
            buscarComa(val){
                var sw=0;
                var cadena=val+'';
                var r = cadena.indexOf(",");
                if(r<0){
                    sw=true;
                }   
                return sw;
            },
            buscarD(id,data=[]){
                var sw=0;
                for (var i = 0; i < data.length; i++) {
                    if (data[i].idordendia==id) {
                        sw=true;
                    }
                }
                return sw;
            },
            buscarR(resolucion,data=[]){
                var sw=0;
                for (var i = 0; i < data.length; i++) {
                    if (data[i].descripcion==resolucion) {
                        sw=true;
                    }
                }
                return sw;
            },
            verificarConvocatorias(convocatoriasT=[],buscar){
                let me=this;
                var convocatorias=[];
                var convocatoriasV=[];
                var url='/acta/buscarConvocatoriaActa';
                axios.get(url).then(function (response) {
                    var respuesta=response.data;
                    convocatoriasV = respuesta.convocatorias;
                    var cont=0;
                    for (var i = 0; i < convocatoriasT.length; i++) {
                        cont=0;
                        for (var j = 0; j < convocatoriasV.length; j++) {
                            if(convocatoriasT[i].id==convocatoriasV[j].id){
                                cont++;
                            }
                            if(buscar.toUpperCase()==convocatoriasV[j].codigo.toUpperCase()){
                                Swal.fire({
                                    title: 'Error...',
                                    text: 'Esta convocatoria ya tiene su acta definida!',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                            if(buscar.toUpperCase()==convocatoriasV[j].titulo.toUpperCase()){
                                Swal.fire({
                                    title: 'Error...',
                                    text: 'Esta convocatoria ya tiene su acta definida!',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                            if(buscar.toUpperCase()==convocatoriasV[j].descripcion.toUpperCase()){
                                Swal.fire({
                                    title: 'Error...',
                                    text: 'Esta convocatoria ya tiene su acta definida!',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                        if(cont==0){
                            
                            convocatorias.push({
                                id:convocatoriasT[i].id,
                                iduser:convocatoriasT[i].iduser,
                                titulo:convocatoriasT[i].titulo,
                                codigo:convocatoriasT[i].codigo,
                                descripcion:convocatoriasT[i].descripcion,
                                estado:convocatoriasT[i].estado,
                                condicion:convocatoriasT[i].condicion,
                                apellidos:convocatoriasT[i].apellidos,
                                nombres:convocatoriasT[i].nombres,
                                EMail:convocatoriasT[i].EMail,
                                perfil:convocatoriasT[i].perfil,
                            });
                        }
                    }
                })
                .catch(function (error) {
                    console.log(error);
                })
                return convocatorias;
            },
            listarConvocatoriaS (buscar,criterio){
                let me=this;
                if(buscar!=''){
                    var url='/acta/buscarConvocatorias?buscar=' + buscar + '&criterio=' + criterio;
                    axios.get(url).then(function (response) {
                        var respuesta=response.data;
                        me.arrayListConvocatoria = me.verificarConvocatorias(respuesta.convocatorias,buscar);
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                }else{
                    me.arrayListConvocatoria=[];
                    Swal.fire({
                        title: 'Error...',
                        text: 'Ingrese un texto de busqueda!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            buscarConvocatoriaI (buscar){
                let me=this;
                //if(me.codigo!=''){
                    var url='/acta/buscarConvocatoria?buscar=' + buscar+ '&criterio=codigo';
                    axios.get(url).then(function (response) {
                        var respuesta=response.data;
                        me.arrayTempConvocatoria = respuesta.convocatoria;
                        if(me.arrayTempConvocatoria.length>0){
                            var url='/acta/buscarConvocatoriaActa';
                            axios.get(url).then(function (response) {
                                var respuesta=response.data;
                                var convocatoriasV = respuesta.convocatorias;
                                var cont=0;
                                for (var j = 0; j < convocatoriasV.length; j++) {
                                    if(me.arrayTempConvocatoria[0].id==convocatoriasV[j].id){
                                        me.arrayTempConvocatoria=[];
                                        me.codigoVerifi='Código inválido';
                                        Swal.fire({
                                            title: 'Error...',
                                            text: 'Esta convocatoria ya tiene su acta definida!',
                                            icon: 'error',
                                            confirmButtonText: 'OK'
                                        });
                                    }else{
                                        me.codigoVerifi='Código valido';
                                    }
                                }
                            })
                            .catch(function (error) {
                                console.log(error);
                            })
                        }else{
                            me.codigoVerifi='Código inválido';
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                /*
                }else{
                    Swal.fire({
                        title: 'Error...',
                        text: 'Debe ingresar un código de acta!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
                */
            },
            registrarConvocatoria(){
                if(this.validarConvocatoria()){
                    return;
                }
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                    title: '¿Está seguro(a) de guardar la convocatoria?',
                    text: "No se podra revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Guardar',
                    cancelButtonText: 'No, Cancelar',
                    reverseButtons: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        swalWithBootstrapButtons.fire(
                            'Guardada!',
                            'La convocatoria ha sido guardada.',
                            'success'
                        )
                        //Guardar Convocatoria
                        let me=this;
                        axios.post('/convocatoria/registrar',{
                            'titulo' : this.titulo,
                            'codigo' : this.codigo,
                            'descripcion' : this.descripcion,
                            'data_ordendia' : this.arrayOrdenDia,
                            'data_persona' : this.arrayPersona,
                            'data_persona_invitada' : this.arrayPersonaInv,
                        }).then(function (response) {
                            me.listado=1;
                            me.listarConvocatoria(1,'','codigo');
                            me.titulo = '';
                            me.codigo = '';
                            me.descripcion = '';
                            me.arrayConvocatoria=[];
                            me.arrayOrdenDia=[];
                            me.arrayPersona=[];
                            me.arrayPersonaInv=[];
                        }).catch(function (error) {
                            console.log(error);
                        })
                        //
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                            'Cancelada',
                            'La convocatoria todavía no fue guardada.',
                            'error'
                        )
                    }
                })
            },
            validarConvocatoria(){
                this.errorConvocatoria=0;
                this.errorMostrarMsjConvocatoria=[];

                if(!this.titulo) this.errorMostrarMsjConvocatoria.push("Ingrese el título de la convocatoria");
                if(!this.codigo) this.errorMostrarMsjConvocatoria.push("Ingrese el codigo de la convocatoria");
                if(!this.descripcion) this.errorMostrarMsjConvocatoria.push("Ingrese la descripcion de la convocatoria");
                if(this.arrayPersona.length<=0) this.errorMostrarMsjConvocatoria.push("Ingrese usuarios dirigidos");
                if(this.arrayOrdenDia.length<=0) this.errorMostrarMsjConvocatoria.push("Ingrese el Orden del dia");
                if (this.errorMostrarMsjConvocatoria.length) this.errorConvocatoria=1;
                return this.errorConvocatoria;
            },
            mostrarDetalleActa(){
                let me=this;
                this.listado=0;
                me.titulo = '';
                me.codigo = '';
                me.descripcion = '';
                me.arrayDetalleActa=[];
                me.arraySelectConvocatoria=[];
                me.arraySelectOrdendia=[];
            },
            mostrarModal(){
                let me=this;
                me.valModal=true;
            },
            ocultarDetalleActa(){
                this.listado=1;
                this.arrayDetalleActa=[];
                this.errorMostrarMsjActa=[];
                this.arrayListConvocatoria=[];
                this.arraySelectConvocatoria=[];
                this.arraySelectOrdendia=[];
                this.arrayPersona=[];
                this.arrayPersonaB=[];
                this.titulo='';
                this.codigo='';
                this.descripcion='';
                this.estado='';
                this.cedulaRedactor='';
                this.apellidoRedactor='';
                this.nombreRedactor='';
                this.emailRedactor='';
                this.perfilRedactor='';
                this.errorActa=0;
            },
            ordenarPersonaInv(){
                let me=this;
                var arrayTemp=[];
                var cont=0;
                var text='';
                for (var i = 0; i < me.arrayDetalleInvitado.length; i++) {
                    cont=0;
                    text='';
                    for (var j = 0; j < me.arrayDetalleInvitado.length; j++) {
                        if(me.arrayDetalleInvitado[i].numeroIdentificacion==me.arrayDetalleInvitado[j].numeroIdentificacion){
                            if(cont>0){
                                text+=', ';
                            }
                            cont++;
                            text+=me.arrayDetalleInvitado[j].numerador;
                        }
                    }
                    if(!me.encuentraPuntoInv(me.arrayDetalleInvitado[i].numeroIdentificacion,arrayTemp)){
                        arrayTemp.push({
                            numerador: text,
                            numeroIdentificacion: me.arrayDetalleInvitado[i].numeroIdentificacion,
                            apellidos: me.arrayDetalleInvitado[i].apellidos,
                            nombres: me.arrayDetalleInvitado[i].nombres,
                            EMail: me.arrayDetalleInvitado[i].EMail,
                            perfil: me.arrayDetalleInvitado[i].perfil
                        });
                    }
                }
                me.arrayDetalleInvitado=arrayTemp;
            },
            encuentraPuntoInv(numeroIdentificacion,array=[]){
                var sw=0;
                for (var i = 0; i < array.length; i++) {
                    if (array[i].numeroIdentificacion==numeroIdentificacion) {
                        sw=true;
                    }
                }
                return sw;
            },
            verActa(id){
                this.arrayDetalle=[];
                this.arrayDetalleEnvio=[];
                this.arrayDetalleInvitado=[];
                let me=this;
                this.listado=2;
                
                //Obtener datos de la convocatoria
                var arrayActaT=[];
                var url='/acta/obtenerCabecera?id='+id;
                axios.get(url).then(function (response) {
                    var respuesta=response.data;
                    arrayActaT = respuesta.acta;
                    me.titulo=arrayActaT[0]['titulo'];
                    me.codigo=arrayActaT[0]['codigo'];
                    me.descripcion=arrayActaT[0]['descripcion'];
                    me.estado=arrayActaT[0]['estado'];
                    //Ver redactor
                    me.cedulaRedactor=arrayActaT[0]['iduser'];
                    me.apellidoRedactor=arrayActaT[0]['apellidos'];
                    me.nombreRedactor=arrayActaT[0]['nombres'];
                    me.emailRedactor=arrayActaT[0]['EMail'];
                    me.perfilRedactor=arrayActaT[0]['perfil'];
                }).catch(function (error) {
                    console.log(error);
                });
                //Otener Id Convocatoria
                var url='/acta/obtenerIdConvocatoria?id='+id;
                var arrayIdTem=[];
                axios.get(url).then(function (response) {
                    var respuesta=response.data;
                    arrayIdTem = respuesta.idConvocatoria;
                    me.aidConvocatoriaActa=arrayIdTem[0]['idconvocatoria'];
                })
                .catch(function (error) {
                    console.log(error);
                });
                //Obtener datos de la Onden dia
                var url='/convocatoria/obtenerOrdenDias?id='+id;
                axios.get(url).then(function (response) {
                    var respuesta=response.data;
                    me.arrayDetalle = respuesta.orden_dias;
                })
                .catch(function (error) {
                    console.log(error);
                });
                //Obtener datos de las personas enviadas la convocatoria
                var url='/convocatoria/obtenerDetalles?id='+id;
                axios.get(url).then(function (response) {
                    var respuesta=response.data;
                    me.arrayDetalleEnvio = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });
                //Obtener datos de las personas invitadas al punto Orden dia
                var url='/convocatoria/obtenerDetalleOrdenDias?id='+id;
                axios.get(url).then(function (response) {
                    var respuesta=response.data;
                    me.arrayDetalleInvitado = respuesta.detalleorden_dias;
                    me.ordenarPersonaInv();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal = '';
                this.descripcionActa='';
                this.arrayPersonaB=[];
                this.arrayCheckbox=[];
                this.arrayCheckboxSelect=[];
                this.arrayResolucionVista=[];
            },
            cerrarModalResolucion(){
                //this.modal=0;
                this.tituloModal = '';
                this.descripcionActa='';
                this.arrayCheckbox=[];
                this.arrayCheckboxSelect=[];
                this.arrayResolucionVista=[];
            },
            abrirModal(){
                this.tituloModal='';
                this.buscarA='';
                this.arrayListConvocatoria=[];
                this.tituloModal = 'Seleccione una convocatoria';
            },
            abrirModalPersona(){
                this.arrayPersonaB=[];
                this.tituloModal='';
                this.buscarP='';
                this.tituloModal = 'Seleccione uno o varios usuarios';
            },
            abrirModalDescripcion(index){
                if(this.codigo!=''){
                    this.valModal=true;
                    this.tituloModal = 'Ingrese descripción';
                    this.descripcionActa='';
                    this.llenarCheckbox(index);
                    this.valBoolean=false;
                    this.idordendia=index;
                }else{
                    this.valModal=false;
                    Swal.fire({
                        title: 'Error...',
                        text: 'Debe ingresar un código de acta!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            abrirModalResolucion(index){
                if(this.codigo!=''){
                    this.valModal=true;
                    this.tituloModal = 'Ingrese una o varias resoluciones';
                    this.descripcionResolucion='';
                    this.llenarCheckbox(index);
                    this.arrayResolucionVista=[];
                }else{
                    this.valModal=false;
                    Swal.fire({
                        title: 'Error...',
                        text: 'Debe ingresar un código de acta!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        },
        mounted() {
            this.listarActa(1,this.buscar,this.criterio);
        }
    }
</script>

<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 900px){
        .btnagregar{
            margin-top: 1.7rem;
        }
        .btnagregarR{
            font-size: 20px;
            margin-top: 0.1rem;
            height: 88px;
        }
    }
</style>