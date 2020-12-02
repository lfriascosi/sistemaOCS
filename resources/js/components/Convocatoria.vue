<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i>Convocatorias
                    <button type="button" @click="mostrarDetalleConvocatoria()" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <!--Listado-->
                <template v-if="listado==1">
                    <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="codigo">Codigo</option>
                                    <option value="titulo">Título</option>
                                    <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarConvocatoria(1,buscar,criterio)" class="form-control"
                                    placeholder="Texto a buscar">
                                <button type="submit" @click="listarConvocatoria(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i>
                                    Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Usuario</th>
                                <th>Titulo</th>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="convocatoria in arrayConvocatoria" :key="convocatoria.id">
                                <td>
                                    <button type="button" @click="verConvocatoria(convocatoria.id)" class="btn btn-success btn-sm">
                                        <i class="icon-eye"></i>
                                    </button>
                                </td>
                                <td v-text="convocatoria.iduser"></td>
                                <td v-text="convocatoria.titulo"></td>
                                <td v-text="convocatoria.codigo"></td>
                                <td v-text="convocatoria.descripcion"></td>
                                <td>
                                    <div v-if="convocatoria.estado=='Enviada'">
                                        <span class="badge badge-success" v-text="convocatoria.estado"></span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-warning" v-text="convocatoria.estado"></span>
                                    </div>                                     
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
                <!--Fin Listado-->
                <!--Detalle Convocatoria-->
                <template v-else-if="listado==0">
                    <div class="card-body">
                        <b>Convocatoria</b>
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <label for="">Título<span style="color:red;" v-show="titulo==''">(*Ingrese)</span></label>
                                <input type="text" placeholder="Ingrese titulo de la convocatoria" class="form-control" v-model="titulo">
                            </div>
                            <div class="col-md-3">
                                <label for="">Código<span style="color:red;" v-show="codigo==''">(*Ingrese)</span></label>
                                <input type="text" placeholder="Ingrese el código de la convocatoria" class="form-control" v-model="codigo">
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Descripción<span style="color:red;" v-show="descripcion==''">(*Ingrese)</span></label>
                                    <textarea class="form-control" placeholder="Ingrese la descripción de la convocatoria" v-model="descripcion" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <b>Opcional envio de correo del dia</b>
                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Usuario <span style="color:red;" v-show="personaUser==''">(*seleccione)</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="docUser" @keyup.enter="buscarPersona()" placeholder="Ingrese cédula">
                                        <button @click="abrirModal()" class="btn- btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="personaUser">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarPersona()" class="btn btn-success form-control btnagregar">
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
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayPersona.length">
                                        <tr v-for="(persona,index) in arrayPersona" :key="persona.id">
                                            <td>
                                                <button @click="eliminarPersona(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            
                                            </td>
                                            <td v-text="persona.cedula"></td>
                                            <td v-text="persona.nombre"></td>
                                            <td v-text="persona.email"></td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="4">
                                                No hay personas agregadas
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <b>Orden del dia</b>
                        <div class="form-group row border">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label>Nombre <span style="color:red;" v-show="nombre==''">(*Ingrese)</span></label>
                                    <input type="text" placeholder="Ingrese nombre de la orden dia" class="form-control" v-model="nombre">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarOrdendia()" class="btn btn-success form-control btnagregar">
                                        <i class="icon-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Nombre</th>                                       
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayOrdenDia.length">
                                        <tr v-for="(detalle,index) in arrayOrdenDia" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarOrdendia(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <input v-model="detalle.nombre" type="text" value="" class="form-control">
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="2">
                                                No hay orden del dia agregada
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">                      
                            <div class="col-md-12">
                                <div v-show="errorConvocatoria" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjConvocatoria" :key="error" v-text="error"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalleConvocatoria()" class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarConvocatoria()">Registrar Convocatoria</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!--Fin Detalle Convocatoria-->
                <!--Ver Convocatoria-->
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
                                <label for=""><b>Codigo</b></label>
                                <p v-text="codigo"></p>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><b>Descripcíon</b></label>
                                    <p v-text="descripcion"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th><b>Nro</b></th>
                                            <th><b>Nombre</b></th>                                       
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle, index) in arrayDetalle" :key="detalle.id" v-bind:index="index">
                                            <td>{{index+1}}</td>
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
                        <div class="form-group row border">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for=""><b>Redactado por :</b></label>
                                    <p v-text="userRedacto"></p>
                                </div>
                            </div>
                            <div class="table-responsive col-md-4">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th><b>Usuarios Dirigidos</b></th>                                  
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalleEnvio.length">
                                        <tr v-for="detalleEnv in arrayDetalleEnvio" :key="detalleEnv.iduser">
                                            <td v-text="detalleEnv.iduser"/>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="4">
                                                No hay Usuarios enviados
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalleConvocatoria()" class="btn btn-secondary">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!--Fin ver Convocatoria-->
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioA">
                                        <option value="nombre">Nombre</option>
                                        <option value="email">Correo</option>
                                    </select>
                                    <input type="text" v-model="buscarA" @keyup.enter="listarPersona(buscarA,criterioA)" class="form-control"
                                        placeholder="Texto a buscar">
                                    <button type="submit" @click="listarPersona(buscarA,criterioA)" class="btn btn-primary"><i class="fa fa-search"></i>
                                        Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-response">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Cedula</th>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="persona in arrayPersonaB" :key="persona.id">
                                        <td>
                                            <button type="button" @click="agregarPersonaModal(persona)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                        </td>
                                        <td v-text="persona.num_documento"></td>
                                        <td v-text="persona.nombre"></td>
                                        <td v-text="persona.email"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
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
    import vSelect from 'vue-select';
    export default {
        data (){
            return {
                //Inicio
                arrayConvocatoria :[],
                arrayDetalleEnvio :[],
                arrayOrdenDia : [],
                arrayUser:[],
                arrayPersona:[],
                arrayPersonaB:[],
                codigo :'',
                titulo :'',
                descripcion :'',
                estado :'',
                userRedacto:'',
                nombre :'',
                docUser:'',
                personaUser:'',
                emailPersona:'',
                errorConvocatoria : 0,
                errorMostrarMsjConvocatoria : [],
                //Fin
                ingreso_id : 0,
                idproveedor : 0,
                proveedor: '',
                nombre : '',
                tipo_comprobante : 'BOLETA',
                serie_comprobante : '',
                num_comprobante : '',
                impuesto : 0.18,
                total : 0.0,
                totalImpuesto : 0.0,
                totalParcial : 0.0,
                arrayIngreso : [],
                arrayDetalle : [],
                arrayProveedor: [],
                listado: 1,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorIngreso : 0,
                errorMostrarMsjIngreso : [],
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
                criterioA:'nombre',
                buscarA:'',
                arrayArticulo :[],
                idarticulo : 0,
                codigo : '',
                articulo : '',
                precio : 0,
                cantidad : 0
            }
        },
        components: {
            vSelect
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
            calcularTotal: function(){
                var resultado =0.0;
                for (var i = 0; i < this.arrayDetalle.length; i++) {
                    resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad);
                }
                return resultado;
            }
        },
        methods : {
            listarConvocatoria (page,buscar,criterio){
                let me=this;
                var url='/convocatoria?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta=response.data;
                    me.arrayConvocatoria = respuesta.convocatorias.data;
                    me.pagination=respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            selectProveedor(search,loading){
                let me=this;
                loading(true)
                var url='/proveedor/selectProveedor?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta=response.data;
                    q: search
                    me.arrayProveedor = respuesta.proveedores;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            getDatosProveedor(val1){
                let me = this;
                me.loading =true;
                me.idproveedor = val1.id;
            },
            buscarPersona(){
                let me=this;
                var url = 'convocatoria/buscarPersona?filtro='+me.docUser;
                axios.get(url).then(function (response) {
                    var respuesta=response.data;
                    me.arrayUser=respuesta.personas;

                    if (me.arrayUser.length>0) {
                        me.personaUser=me.arrayUser[0]['nombre'];
                        me.docUser=me.arrayUser[0]['num_documento'];
                        me.emailPersona=me.arrayUser[0]['email'];
                    }else{
                        me.personaUser='No existe persona';
                        me.docUser='';
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
            encuentraPersona(nombre){
                var sw=0;
                for (var i = 0; i < this.arrayPersona.length; i++) {
                    if (this.arrayPersona[i].nombre==nombre) {
                        sw=true;
                    }
                }
                return sw;
            },
            eliminarOrdendia(index){
                let me=this;
                me.arrayOrdenDia.splice(index,1);
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
                        me.nombre="";
                    }else{
                        me.arrayOrdenDia.push({
                        nombre: me.nombre
                        });
                        me.nombre="";
                    }                    
                }
            },
            agregarPersona(){
                let me=this;
                if(me.personaUser==''){

                }else{
                    if(me.encuentraPersona(me.personaUser)){
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
                        email: me.emailPersona,
                        cedula: me.docUser
                        });
                        me.docUser="";
                        me.personaUser="";
                    }                    
                }
            },
            agregarPersonaModal( data = []){
                let me=this;
                if(me.encuentraPersona(data['nombre'])){
                        Swal.fire({
                            title: 'Error...',
                            text: 'La perona ya se encuentra agregado!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        me.buscarA="";
                    }else{
                        me.arrayPersona.push({
                        cedula: data['num_documento'],
                        nombre: data['nombre'],
                        email: data['email']
                        });
                    }      
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
            registrarConvocatoria(){
                if(this.validarConvocatoria()){
                    return;
                }
                let me=this;
                axios.post('/convocatoria/registrar',{
                    'titulo' : this.titulo,
                    'codigo' : this.codigo,
                    'descripcion' : this.descripcion,
                    'data_ordendia' : this.arrayOrdenDia,
                    'data_persona' : this.arrayPersona
                }).then(function (response) {
                    me.listado=1;
                    me.listarConvocatoria(1,'','codigo');
                    me.titulo = '';
                    me.codigo = '';
                    me.descripcion = '';
                    me.arrayOrdenDia=[];
                    me.arrayPersona=[];
                }).catch(function (error) {
                    console.log(error);
                })
            },
            validarConvocatoria(){
                this.errorConvocatoria=0;
                this.errorMostrarMsjConvocatoria=[];

                if(!this.titulo) this.errorMostrarMsjConvocatoria.push("Ingrese el título de la convocatoria");
                if(!this.codigo) this.errorMostrarMsjConvocatoria.push("Ingrese el codigo de la convocatoria");
                if(!this.descripcion) this.errorMostrarMsjConvocatoria.push("Ingrese la descripcion de la convocatoria");
                if(this.arrayOrdenDia.length<=0) this.errorMostrarMsjConvocatoria.push("Ingrese el Orden del dia");
                if (this.errorMostrarMsjConvocatoria.length) this.errorConvocatoria=1;
                return this.errorConvocatoria;
            },
            mostrarDetalleConvocatoria(){
                let me=this;
                this.listado=0;
                me.titulo = '';
                me.codigo = '';
                me.descripcion = '';
                me.arrayOrdenDia=[];
                me.arrayPersona=[];
            },
            ocultarDetalleConvocatoria(){
                this.listado=1;
            },
            verConvocatoria(id){
                let me=this;
                this.listado=2;
                
                //Obtener datos de la convocatoria
                var arrayConvocatoriaT=[];
                var url='/convocatoria/obtenerCabecera?id='+id;
                axios.get(url).then(function (response) {
                    var respuesta=response.data;
                    arrayConvocatoriaT = respuesta.convocatoria;
                    me.titulo=arrayConvocatoriaT[0]['titulo'];
                    me.codigo=arrayConvocatoriaT[0]['codigo'];
                    me.descripcion=arrayConvocatoriaT[0]['descripcion'];
                    me.estado=arrayConvocatoriaT[0]['estado'];
                    me.userRedacto=arrayConvocatoriaT[0]['iduser'];
                })
                .catch(function (error) {
                    console.log(error);
                })
                //Obtener datos de la Onden dia
                var url='/convocatoria/obtenerOrdenDias?id='+id;
                axios.get(url).then(function (response) {
                    var respuesta=response.data;
                    me.arrayDetalle = respuesta.orden_dias;
                })
                .catch(function (error) {
                    console.log(error);
                })
                //Obtener datos de las personas enviadas el acta
                var url='/convocatoria/obtenerDetalles?id='+id;
                axios.get(url).then(function (response) {
                    var respuesta=response.data;
                    me.arrayDetalleEnvio = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.buscarA='';
                this.arrayUser=[];
            },
            abrirModal(){
                this.arrayUser=[];
                this.modal = 1;
                this.tituloModal = 'Seleccione uno o varios usuarios';
            },
            desactivarIngreso(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Esta seguro de anular este ingreso?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar!',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me=this;
                    axios.put('/ingreso/desactivar',{
                        'id' : id
                    }).then(function (response) {
                        me.listarConvocatoria(1,'','num_comprobante');
                        swalWithBootstrapButtons.fire(
                        'Anulado!',
                        'El ingreso ha sido anulado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    })
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    
                }
                })
            },
        },
        mounted() {
            this.listarConvocatoria(1,this.buscar,this.criterio);
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