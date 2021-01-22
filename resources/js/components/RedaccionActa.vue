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
                                <input type="text" placeholder="Ingrese el código de la acta" class="form-control" v-model="codigo">
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
                                            <th>Opciones</th>
                                            <th>Nro</th>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arraySelectOrdendia.length">
                                        <tr v-for="detalle in arraySelectOrdendia" :key="detalle.id">
                                            <td width="90" align="center" style="vertical-align:middle;">
                                                <button v-if="detalle.descripcion=='Ninguna'" @click="abrirModalDescripcion(detalle.id)" type="button"
                                                class="btn btn-success btn-sm" title="Agregar descripción" data-toggle="modal" data-target="#modalDescripcionN">
                                                    <i class="icon-plus"></i>
                                                </button>
                                                <button v-if="detalle.descripcion!='Ninguna'" @click="editarDescripcion(detalle.id)" type="button"
                                                class="btn btn-warning btn-sm" title="Editar descripción" data-toggle="modal" data-target="#modalDescripcionN">
                                                    <i class="icon-pencil"></i>
                                                </button>
                                                <button v-if="detalle.descripcion!='Ninguna'" @click="eliminarDescripcion(detalle.id)" type="button"
                                                class="btn btn-danger btn-sm" title="Eliminar descripción">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td align="center" style="vertical-align:middle;" v-text="detalle.numerador"></td>
                                            <td v-if="buscarComa(detalle.id)" style="vertical-align:middle;" v-text="detalle.nombre"></td>
                                            <td v-else>
                                                <input v-model="detalle.nombreAlternativo" type="text" value="" class="form-control" placeholder="Ingrese un nombre">
                                            </td>
                                            <td v-text="detalle.descripcion"></td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="4">
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
                                                            <input type="checkbox" v-model="arraySelectCheckbox" :value="check"/>
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
        <!--Fin del modal-->
        <!--Inicio del modal Persona agregar/actualizar-->
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
                idordendia:0,
                
                //Inicio
                arrayActa :[],
                arrayDetalleActa :[],
                arrayFirmaActa :[],
                arrayListConvocatoria :[],
                arraySelectConvocatoria :[],
                arrayTempConvocatoria :[],
                arraySelectOrdendia :[],
                arrayCheckbox:[],
                arraySelectCheckbox:[],
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
                idDescripcion:'',
                aidConvocatoriaActa:'',
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
            encuentra(nombre){
                var sw=0;
                for (var i = 0; i < this.arrayOrdenDia.length; i++) {
                    if (this.arrayOrdenDia[i].nombre==nombre) {
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
            encuentraPersonaInv(cedula){
                var sw=0;
                for (var i = 0; i < this.arrayPersonaInv.length; i++) {
                    if (this.arrayPersonaInv[i].cedula==cedula && this.arrayPersonaInv[i].idordendia==this.idordendia) {
                        sw=true;
                    }
                }
                return sw;
            },
            eliminarOrdendia(index){
                let me=this;
                me.eliminarInvitado(me.arrayOrdenDia[index].nro);
                me.arrayOrdenDia.splice(index,1);
                for (var i = 0; i < me.arrayOrdenDia.length; i++) {
                    me.arrayOrdenDia[i].nro=i+1;
                }
            },
            eliminarRI(num,id){
                let me=this;
                if(num==1){
                    for (var i = 0; i < me.arrayPersonaInv.length; i++) {
                        if(me.arrayPersonaInv[i].idordendia==id){
                            me.arrayPersonaInv.splice(i,1);
                            break;
                        }
                    }
                }else{
                    num--;
                    for (var i = 0; i < me.arrayPersonaInv.length; i++) {
                        if(me.arrayPersonaInv[i].idordendia==id){
                            me.arrayPersonaInv.splice(i,1);
                            me.eliminarRI(num,id);
                        }
                    }
                }
            },
            eliminarInvitado(id){
                let me=this;
                var num=0;
                for (var i = 0; i < me.arrayOrdenDia.length; i++) {
                    if(me.arrayOrdenDia[i].nro==id){
                        me.arrayOrdenDia[i].invitado='Ninguno';
                    }
                }
                for (var i = 0; i < me.arrayPersonaInv.length; i++) {
                    if(me.arrayPersonaInv[i].idordendia==id){
                        num++;
                    }
                }
                me.eliminarRI(num,id);
            },
            eliminarPersona(index){
                let me=this;
                me.arrayPersona.splice(index,1);
            },
            agregarOrdendia(){
                let me=this;
                if(me.nombre==''){

                }else{
                    if(me.encuentra(me.nombre)){
                        Swal.fire({
                            title: 'Error...',
                            text: 'Ese nombre ya se encuentra agregado!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }else{
                        me.arrayOrdenDia.push({
                        nro: me.arrayOrdenDia.length+1,
                        nombre: me.nombre,
                        invitado: 'Ninguno'
                        });
                        me.nombre="";
                    }                    
                }
            },
            agregarInvitado(data = []){
                let me=this;
                if(me.encuentraPersonaInv(data['numeroIdentificacion'])){
                    Swal.fire({
                        title: 'Error...',
                        text: 'La perona ya se encuentra invitada a este punto de la orden del dia!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }else{
                    me.arrayPersonaInv.push({
                    idordendia: me.idordendia,
                    cedula: data['numeroIdentificacion'],
                    nombre: data['apellidos']+' '+data['nombres'],
                    EMail: data['EMail'],
                    perfil: data['perfil']
                    });
                    var invitados='';
                    for (var i = 0; i < me.arrayOrdenDia.length; i++) {
                        if (me.arrayOrdenDia[i].nro==me.idordendia) {
                            if(me.arrayOrdenDia[i].invitado!='Ninguno'){
                                invitados+=', '+data['apellidos']+' '+data['nombres'];
                                me.arrayOrdenDia[i].invitado+=invitados;
                            }else{
                                invitados=data['apellidos']+' '+data['nombres'];
                                me.arrayOrdenDia[i].invitado=invitados;
                            }
                            break;
                        }
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
            inicializarOrdenDiaSelect(){
                let me=this;
                var arrayTemp=[];
                for (var i = 0; i < me.arraySelectOrdendia.length; i++) {
                    arrayTemp.push({
                        id: me.arraySelectOrdendia[i]['id']+'',
                        numerador: me.arraySelectOrdendia[i]['numerador']+'',
                        nombre: me.arraySelectOrdendia[i]['nombre'],
                        nombreAlternativo:'',
                        descripcion: 'Ninguna'
                    });
                }
                me.arraySelectOrdendia=arrayTemp;
            },
            agregarConvocatoriaModal(data =[]){
                let me=this;
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
                }                
            },
            eliminarconvocatoria(){
                let me=this;
                me.arraySelectConvocatoria=[];
                me.arraySelectOrdendia=[];
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
                    if(me.arraySelectCheckbox.length>0){
                        for (var i = 0; i < me.arraySelectCheckbox.length; i++) {
                            for (var j = 0; j < me.arraySelectOrdendia.length; j++) {
                                if(me.arraySelectCheckbox[i]['id']==me.arraySelectOrdendia[j]['id'] || me.idDescripcion==me.arraySelectOrdendia[j]['id']){
                                    me.arraySelectOrdendia[j]['descripcion']=me.descripcionActa;
                                }
                            }
                        }
                        me.arraySelectCheckbox=[];
                    }
                    me.combinarDescripcion();
                    me.descripcionActa='';
                    me.arrayCheckbox=[];
                }
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
                if(arrayId.length>1){
                    for (var i = 0; i < me.arraySelectOrdendia.length; i++) {
                        if(me.arraySelectOrdendia[i]['id']==id){
                            arrayNro=me.arraySelectOrdendia[i]['numerador'].split(",");
                            arrayNombre=me.arraySelectOrdendia[i]['nombre'].split(",");
                        }
                    }
                    for (var i = 0; i < me.arraySelectOrdendia.length; i++) {
                        if(me.arraySelectOrdendia[i]['id']==id){
                            for (var j = 0; j < arrayId.length; j++) {
                                arrayTemp.push({
                                    id: arrayId[j],
                                    numerador: arrayNro[j],
                                    nombre: arrayNombre[j],
                                    nombreAlternativo:'',
                                    descripcion: 'Ninguna'
                                });
                            }
                        }else{
                            arrayTemp.push({
                                id: me.arraySelectOrdendia[i]['id'],
                                numerador: me.arraySelectOrdendia[i]['numerador'],
                                nombre: me.arraySelectOrdendia[i]['nombre'],
                                nombreAlternativo:'',
                                descripcion: me.arraySelectOrdendia[i]['descripcion']
                            });
                        }
                    }
                    me.arraySelectOrdendia=arrayTemp;
                }else{
                    for (var i = 0; i < me.arraySelectOrdendia.length; i++) {
                        if(me.arraySelectOrdendia[i]['id']==id){
                            me.arraySelectOrdendia[i]['descripcion']='Ninguna';
                        }
                    }
                }
            },
            editarDescripcion(id){
                let me=this;
                me.idDescripcion=id;
                me.tituloModal = 'Editar descripción';
                me.valBoolean=false;
                me.arrayCheckbox=[];
                me.arraySelectCheckbox=[];
                for (var i = 0; i < me.arraySelectOrdendia.length; i++) {
                    if(me.arraySelectOrdendia[i]['id']==id){
                        me.descripcionActa=me.arraySelectOrdendia[i]['descripcion'];
                        me.llenarCheckbox(id);
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
                me.arraySelectCheckbox=[];
                var arrayId=[];
                var cadenaId='';
                var arrayNro=[];
                var cadenaNro='';
                for (var i = 0; i < me.arraySelectOrdendia.length; i++) {
                    if(me.arraySelectOrdendia[i]['id']==id){
                        cadenaId=id+'';
                        arrayId=cadenaId.split(",");
                        if(arrayId.length>1){
                            cadenaNro=me.arraySelectOrdendia[i]['numerador']+'';
                            arrayNro=cadenaNro.split(",");
                            for (var j = 0; j < arrayId.length; j++) {
                                me.arraySelectCheckbox.push({
                                    id: arrayId[j],
                                    numerador: arrayNro[j]
                                })
                            }
                        }else{
                            me.arraySelectCheckbox.push({
                                id: me.arraySelectOrdendia[i]['id'],
                                numerador: me.arraySelectOrdendia[i]['numerador']
                            })
                        }
                    }else{
                        if(me.arraySelectOrdendia[i]['descripcion']=='Ninguna'){
                            cadenaId=me.arraySelectOrdendia[i]['id']+'';
                            arrayId=cadenaId.split(",");
                            if(arrayId.length>1){
                                cadenaNro=me.arraySelectOrdendia[i]['numerador']+'';
                                arrayNro=cadenaNro.split(",");
                                for (var j = 0; j < arrayId.length; j++) {
                                    me.arrayCheckbox.push({
                                        id: arrayId[j],
                                        numerador: arrayNro[j]
                                    })
                                }
                            }else{
                                me.arrayCheckbox.push({
                                    id: me.arraySelectOrdendia[i]['id'],
                                    numerador: me.arraySelectOrdendia[i]['numerador']
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
                    if (data[i].id==id) {
                        sw=true;
                    }
                }
                return sw;
            },
            combinarDescripcion(){
                let me=this;
                var arrayTemp=[];
                var idT='';
                var numeradorT='';
                var nombreT='';
                var descripcionT='';
                var cont=0;
                for (var i = 0; i < me.arraySelectOrdendia.length; i++) {
                    cont=0;
                    idT='';
                    numeradorT='';
                    nombreT='';
                    descripcionT='';
                    for (var j = 0; j < me.arraySelectOrdendia.length; j++) {
                        if(me.arraySelectOrdendia[i]['descripcion']!='Ninguna' && me.arraySelectOrdendia[i]['descripcion']==me.arraySelectOrdendia[j]['descripcion']){
                            cont++;
                            if(cont>1){
                                idT+= ',';
                                numeradorT+= ',';
                                nombreT+= ',';
                            }
                            idT+= me.arraySelectOrdendia[j]['id'];
                            numeradorT+= me.arraySelectOrdendia[j]['numerador'];
                            nombreT+= me.arraySelectOrdendia[j]['nombre'];
                            descripcionT=me.arraySelectOrdendia[j]['descripcion'];
                        }
                    }
                    if(cont>1){
                        if(!me.buscarD(idT,arrayTemp)){
                            arrayTemp.push({
                                id: idT,
                                numerador: numeradorT,
                                nombre: nombreT,
                                nombreAlternativo: '',
                                descripcion: descripcionT
                            });
                        }
                    }else{
                        arrayTemp.push({
                            id: me.arraySelectOrdendia[i]['id'],
                            numerador: me.arraySelectOrdendia[i]['numerador'],
                            nombre: me.arraySelectOrdendia[i]['nombre'],
                            nombreAlternativo:'',
                            descripcion: me.arraySelectOrdendia[i]['descripcion']
                        });
                    }
                }
                me.arraySelectOrdendia=[];
                me.arraySelectOrdendia=arrayTemp;
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
                this.arraySelectCheckbox=[];
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
                this.tituloModal = 'Ingrese descripción';
                this.descripcionActa='';
                this.llenarCheckbox(index);
                this.valBoolean=false;
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
    }
</style>