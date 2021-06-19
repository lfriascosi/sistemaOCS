<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
      <li class="breadcrumb-item">Acta</li>
      <li class="breadcrumb-item active">Redacción</li>
    </ol>
    <div class="container-fluid">
      <!-- Tabla Listado -->
      <div class="card">
        <div v-if="listado == 1" class="card-header">
          <i class="fa fa-align-justify"></i>Actas
          <button
            type="button"
            @click="mostrarDetalleActa()"
            class="btn btn-secondary"
          >
            <i class="icon-plus"></i>&nbsp;Nueva
          </button>
        </div>
        <div v-else-if="listado == 0" class="card-header">
          <i class="fa fa-align-justify"></i>Acta
        </div>
        <!--Listado Acta-->
        <template v-if="listado == 1">
          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <select class="form-control col-md-3" v-model="criterio">
                    <option value="codigo">Código</option>
                    <option value="titulo">Título</option>
                    <option value="descripcion">Descripción</option>
                  </select>
                  <input
                    type="text"
                    v-model="buscar"
                    @keyup.enter="listarActa(1, buscar, criterio)"
                    class="form-control"
                    placeholder="Texto a buscar"
                  />
                  <button
                    type="submit"
                    @click="listarActa(1, buscar, criterio)"
                    class="btn btn-primary"
                    title="Buscar acta"
                  >
                    <i class="fa fa-search"></i>Buscar
                  </button>
                  <button
                    type="submit"
                    @click="listarActa(1, '', '')"
                    class="btn btn-info"
                    title="Ver todas las actas"
                  >
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
                    <td width="1" align="center" style="vertical-align: middle">
                      <button
                        type="button"
                        @click="verActa(acta.id)"
                        class="btn btn-success btn-sm"
                        title="Ver acta"
                      >
                        <i class="icon-eye"></i>
                      </button>
                    </td>
                    <td
                      width="100"
                      align="center"
                      style="vertical-align: middle"
                      v-text="acta.apellidos + ' ' + acta.nombres"
                    ></td>
                    <td
                      width="220"
                      align="justify"
                      style="vertical-align: middle"
                      v-text="acta.titulo"
                    ></td>
                    <td
                      width="200"
                      align="center"
                      style="vertical-align: middle"
                      v-text="acta.codigo"
                    ></td>
                    <td align="justify" v-text="acta.descripcion"></td>
                    <td width="1" align="center" style="vertical-align: middle">
                      <div>
                        <span
                          class="badge badge-success"
                          v-text="acta.estado"
                        ></span>
                      </div>
                    </td>
                  </tr>
                </tbody>
                <tbody v-else>
                  <tr>
                    <td colspan="6">No hay actas agregadas</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <nav>
              <ul class="pagination">
                <li class="page-item" v-if="pagination.current_page > 1">
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="
                      cambiarPagina(
                        pagination.current_page - 1,
                        buscar,
                        criterio
                      )
                    "
                    >Ant</a
                  >
                </li>
                <li
                  class="page-item"
                  v-for="page in pagesNumber"
                  :key="page"
                  :class="[page == isActived ? 'active' : '']"
                >
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="cambiarPagina(page, buscar, criterio)"
                    v-text="page"
                  ></a>
                </li>
                <li
                  class="page-item"
                  v-if="pagination.current_page < pagination.last_page"
                >
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="
                      cambiarPagina(
                        pagination.current_page + 1,
                        buscar,
                        criterio
                      )
                    "
                    >Sig</a
                  >
                </li>
              </ul>
            </nav>
          </div>
        </template>
        <!--Fin Listado Acta-->
        <!--Listado Agregar Acta-->
        <template v-else-if="listado == 0">
          <div class="card-body">
            <b>Acta</b>
            <div class="form-group row border">
              <div class="col-md-9">
                <label for=""
                  >Título<span style="color: red" v-show="titulo == ''"
                    >(*Ingrese)</span
                  ></label
                >
                <input
                  type="text"
                  placeholder="Ingrese el título de la acta"
                  class="form-control"
                  v-model="titulo"
                />
              </div>
              <div class="col-md-3">
                <label for=""
                  >Código<span style="color: red" v-show="codigo == ''"
                    >(*Ingrese)</span
                  ></label
                >
                <input
                  type="text"
                  style="text-transform: uppercase"
                  onkeyup="javascript:this.value=this.value.toUpperCase();"
                  placeholder="Ingrese el código de la acta"
                  class="form-control"
                  v-model="codigo"
                />
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for=""
                    >Descripción<span
                      style="color: red"
                      v-show="descripcion == ''"
                      >(*Ingrese)</span
                    ></label
                  >
                  <textarea
                    class="form-control"
                    placeholder="Ingrese la descripción de la acta"
                    v-model="descripcion"
                    rows="3"
                  ></textarea>
                </div>
              </div>
            </div>
            <b>Seleccione convocatoria</b>
            <div class="form-group row border">
              <div v-if="arraySelectConvocatoria.length == 0" class="col-md-8">
                <div class="form-group">
                  <label
                    >Convocatoria
                    <span
                      style="color: red"
                      v-show="!arraySelectConvocatoria.length"
                      >(*seleccione)</span
                    ></label
                  >
                  <div class="form-inline">
                    <input
                      style="width: 40%"
                      type="text"
                      class="form-control"
                      v-model="codigoConvocatoria"
                      @keyup.enter="buscarConvocatoriaI(codigoConvocatoria)"
                      placeholder="Ingrese código convocatoria"
                    />
                    <button
                      @click="abrirModal()"
                      class="btn- btn-primary"
                      title="Buscar convocatoria"
                      data-toggle="modal"
                      data-target="#modalConvocatoria"
                    >
                      ⋮
                    </button>
                    <input
                      type="text"
                      readonly
                      class="form-control"
                      v-model="codigoVerifi"
                    />
                  </div>
                </div>
              </div>
              <div v-if="arraySelectConvocatoria.length == 0" class="col-md-2">
                <div class="form-group">
                  <button
                    @click="agregarConvocatoria()"
                    class="btn btn-success form-control btnagregar"
                    title="Agregar Convocatoria"
                  >
                    <i class="icon-plus"></i>
                  </button>
                </div>
              </div>
              <div v-else-if="arraySelectConvocatoria.length > 0">&nbsp;</div>
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
                    <tr
                      v-for="convocatoria in arraySelectConvocatoria"
                      :key="convocatoria.id"
                    >
                      <td
                        width="1"
                        align="center"
                        style="vertical-align: middle"
                      >
                        <button
                          @click="eliminarconvocatoria()"
                          type="button"
                          class="btn btn-danger btn-sm"
                          title="Eliminar convocatoria"
                        >
                          <i class="icon-close"></i>
                        </button>
                      </td>
                      <td
                        v-text="
                          convocatoria.apellidos + ' ' + convocatoria.nombres
                        "
                      ></td>
                      <td v-text="convocatoria.titulo"></td>
                      <td v-text="convocatoria.codigo"></td>
                      <td v-text="convocatoria.descripcion"></td>
                      <td v-text="convocatoria.estado"></td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="6">No hay convocatoria seleccionada</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <b>Orden del día</b>
            <div class="form-group row border">
              &nbsp;
              <div class="table-responsive col-md-12">
                <table class="table table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th colspan="2">Opciones</th>
                      <th rowspan="2" style="vertical-align: middle">Nro</th>
                      <th rowspan="2" style="vertical-align: middle">Nombre</th>
                      <th rowspan="2" style="vertical-align: middle">
                        Descripción
                      </th>
                      <th rowspan="2" style="vertical-align: middle">
                        Resolución
                      </th>
                    </tr>
                    <tr>
                      <th>Descripción</th>
                      <th>Resolución</th>
                    </tr>
                  </thead>
                  <tbody v-if="arrayVistaOrdendia2.length">
                    <tr
                      v-for="detalle in arrayVistaOrdendia2"
                      :key="detalle.idordendia"
                    >
                      <td
                        width="90"
                        align="center"
                        style="vertical-align: middle"
                      >
                        <button
                          v-if="detalle.descripcion == 'Ninguna'"
                          @click="abrirModalDescripcion(detalle.idordendia)"
                          type="button"
                          class="btn btn-success btn-sm"
                          title="Agregar descripción"
                          data-toggle="modal"
                          data-target="#modalDescripcionN"
                        >
                          <i class="icon-plus"></i>
                        </button>
                        <button
                          v-if="detalle.descripcion != 'Ninguna'"
                          @click="editarDescripcion(detalle.idordendia)"
                          type="button"
                          class="btn btn-warning btn-sm"
                          title="Editar descripción"
                          data-toggle="modal"
                          data-target="#modalDescripcionN"
                        >
                          <i class="icon-pencil"></i>
                        </button>
                        <button
                          v-if="detalle.descripcion != 'Ninguna'"
                          @click="eliminarDescripcion(detalle.idordendia)"
                          type="button"
                          class="btn btn-danger btn-sm"
                          title="Eliminar descripción"
                        >
                          <i class="icon-close"></i>
                        </button>
                      </td>
                      <td
                        width="90"
                        align="center"
                        style="vertical-align: middle"
                      >
                        <button
                          v-if="detalle.resolucion == 'Ninguna'"
                          @click="abrirModalResolucion(detalle.idordendia)"
                          type="button"
                          class="btn btn-primary btn-sm"
                          title="Agregar resolución"
                          data-toggle="modal"
                          data-target="#modalResolucion"
                        >
                          <i class="icon-plus"></i>
                        </button>
                        <button
                          v-if="detalle.resolucion != 'Ninguna'"
                          @click="editarResolucion(detalle.idordendia)"
                          type="button"
                          class="btn btn-warning btn-sm"
                          title="Editar resolución"
                          data-toggle="modal"
                          data-target="#modalResolucion"
                        >
                          <i class="icon-pencil"></i>
                        </button>
                        <button
                          v-if="detalle.resolucion != 'Ninguna'"
                          @click="eliminarResolucion(detalle.idordendia)"
                          type="button"
                          class="btn btn-danger btn-sm"
                          title="Eliminar resoluciones"
                        >
                          <i class="icon-close"></i>
                        </button>
                      </td>
                      <td
                        align="center"
                        style="vertical-align: middle"
                        v-text="detalle.numerador"
                      ></td>
                      <td
                        v-if="buscarComa(detalle.idordendia)"
                        style="vertical-align: middle"
                        v-text="detalle.nombre"
                      ></td>
                      <td v-else>
                        <input
                          v-model="detalle.nombrealternativo"
                          type="text"
                          value=""
                          class="form-control"
                          placeholder="Ingrese un nombre"
                        />
                      </td>
                      <td v-text="detalle.descripcion"></td>
                      <td v-text="detalle.resolucion"></td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="6">No hay orden del día agregada</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <b>Seleccione usuarios dirigidos</b>
            <div class="form-group row border">
              <div class="col-md-8">
                <div class="form-group">
                  <label
                    >Usuario
                    <span style="color: red" v-show="arrayPersona.length == 0"
                      >(*seleccione)</span
                    ></label
                  >
                  <div class="form-inline">
                    <input
                      type="text"
                      class="form-control"
                      v-model="docUser"
                      @keyup.enter="buscarPersona()"
                      placeholder="Ingrese cédula"
                    />
                    <button
                      @click="abrirModalPersona()"
                      class="btn- btn-primary"
                      title="Buscar Persona"
                      data-toggle="modal"
                      data-target="#modalPersona"
                    >
                      ⋮
                    </button>
                    <input
                      type="text"
                      readonly
                      class="form-control"
                      v-model="personaUser"
                    />
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <button
                    @click="agregarPersona()"
                    class="btn btn-success form-control btnagregar"
                    title="Agregar Persona"
                  >
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
                    <tr
                      v-for="(persona, index) in arrayPersona"
                      :key="persona.id"
                    >
                      <td
                        width="1"
                        align="center"
                        style="vertical-align: middle"
                      >
                        <button
                          @click="eliminarPersona(index)"
                          type="button"
                          class="btn btn-danger btn-sm"
                          title="Eliminar Persona"
                        >
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
                      <td colspan="5">No hay personas agregadas</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12">
                <button
                  type="button"
                  @click="ocultarAgregarActa()"
                  class="btn btn-secondary"
                >
                  Cerrar
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="registrarActa()"
                >
                  Registrar Acta
                </button>
              </div>
            </div>
          </div>
        </template>
        <!--Fin Listado Agregar Acta-->
        <!--Ver Detalle Acta-->
        <template v-else-if="listado == 2">
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
            <div class="form-group row border">
              &nbsp;
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
                    <tr
                      align="center"
                      v-for="(detalle, index) in arrayDetalle"
                      :key="detalle.id"
                      v-bind:index="index"
                    >
                      <td>{{ index + 1 }}</td>
                      <td v-text="detalle.nombre" />
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="4">No hay orden del dia agregados</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="form-group row border">
              &nbsp;
              <div class="table-responsive col-md-12">
                <table class="table table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th><b>Desarrollo de la reunión</b></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      align="center"
                      v-for="combinardetalle in arrayCombinarDetalle"
                      :key="combinardetalle.idordendia"
                    >
                      <td
                        align="left"
                        v-if="
                          combinardetalle.nombrealternativo == null ||
                          combinardetalle.nombrealternativo == ''
                        "
                      >
                        <p>
                          <b
                            v-text="
                              combinardetalle.numerador +
                              '  ' +
                              combinardetalle.nombre
                            "
                          />
                          <br /><b>Descripción:</b> <br />{{
                            combinardetalle.descripcion
                          }}
                          <br /><b>Resolución:</b> <br /><span
                            v-html="combinardetalle.resolucion"
                          ></span>
                        </p>
                      </td>
                      <td align="left" v-else>
                        <p>
                          <b
                            v-text="
                              combinardetalle.numerador +
                              '  ' +
                              combinardetalle.nombrealternativo
                            "
                          />
                          <br /><b>Descripción:</b> <br />{{
                            combinardetalle.descripcion
                          }}
                          <br /><b>Resolución:</b> <br /><span
                            v-html="combinardetalle.resolucion"
                          ></span>
                        </p>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="form-group row border">
              &nbsp;
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
                    <tr
                      align="center"
                      v-for="detalleEnv in arrayDetalleEnvio"
                      :key="detalleEnv.iduser"
                    >
                      <td v-text="detalleEnv.iduser" />
                      <td
                        v-text="detalleEnv.apellidos + ' ' + detalleEnv.nombres"
                      />
                      <td v-text="detalleEnv.EMail" />
                      <td v-text="detalleEnv.perfil" />
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="4">No hay usuarios enviados</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="form-group row border">
              &nbsp;
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
                      <td v-text="cedulaRedactor" />
                      <td v-text="apellidoRedactor" />
                      <td v-text="nombreRedactor" />
                      <td v-text="emailRedactor" />
                      <td v-text="perfilRedactor" />
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12">
                <button
                  type="button"
                  @click="ocultarDetalleActa()"
                  class="btn btn-secondary"
                >
                  Cerrar
                </button>
              </div>
            </div>
          </div>
        </template>
        <!--Fin Ver Detalle Acta-->
      </div>
      <!-- Fin tabla Listado -->
    </div>
    <!--Inicio del modal Convocatoria agregar/actualizar-->
    <div v-if="valModal">
      <div
        class="modal fade"
        id="modalConvocatoria"
        tabindex="-1"
        role="dialog"
        aria-labelledby="myModalLabel"
        style="display: none"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-primary modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" v-text="tituloModal"></h4>
              <button
                type="button"
                class="close"
                @click="cerrarModalConvocatoria()"
                aria-label="Close"
                data-dismiss="modal"
              >
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
                    <input
                      type="text"
                      v-model="buscarA"
                      @keyup.enter="listarConvocatoriaS(buscarA, criterioA)"
                      class="form-control"
                      placeholder="Texto a buscar"
                    />
                    <button
                      type="submit"
                      @click="listarConvocatoriaS(buscarA, criterioA)"
                      class="btn btn-primary"
                      :disabled="!buscarA"
                    >
                      <i class="fa fa-search"></i> Buscar
                    </button>
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
                    <tr
                      v-for="convocatoria in arrayListConvocatoria"
                      :key="convocatoria.id"
                    >
                      <td
                        width="1"
                        align="center"
                        style="vertical-align: middle"
                      >
                        <button
                          type="button"
                          @click="agregarConvocatoriaModal(convocatoria)"
                          class="btn btn-success btn-sm"
                          aria-label="Close"
                          data-dismiss="modal"
                        >
                          <i class="icon-check"></i>
                        </button>
                      </td>
                      <td
                        width="100"
                        align="center"
                        style="vertical-align: middle"
                        v-text="
                          convocatoria.apellidos + ' ' + convocatoria.nombres
                        "
                      ></td>
                      <td v-text="convocatoria.titulo"></td>
                      <td v-text="convocatoria.codigo"></td>
                      <td v-text="convocatoria.descripcion"></td>
                      <td v-text="convocatoria.estado"></td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="6">No existe convocatoria habilitada</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button
                title="Cerrar"
                type="button"
                class="btn btn-secondary"
                @click="cerrarModalConvocatoria()"
                data-dismiss="modal"
              >
                Cerrar
              </button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    </div>
    <!--Fin del modal-->
    <!--Inicio del modal Descripcion -->
    <div v-if="valModal">
      <div
        class="modal fade"
        id="modalDescripcionN"
        tabindex="-1"
        role="dialog"
        aria-labelledby="myModalLabel"
        style="display: none"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-primary modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" v-text="tituloModal"></h4>
              <button
                type="button"
                class="close"
                @click="cerrarModalDescripcion()"
                aria-label="Close"
                data-dismiss="modal"
              >
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <form
                action=""
                method="post"
                enctype="multipart/form-data"
                class="form-horizontal"
              >
                <div class="form-group row">
                  <div class="col-md-12">
                    <textarea
                      type="textarea"
                      v-model="descripcionActa"
                      class="form-control"
                      placeholder="Ingrese la descripción deacuerdo al punto del orden día seleccionado."
                      rows="6"
                    ></textarea>
                  </div>
                  &nbsp;
                  <div class="col-md-12" style="border-top: 1px solid #c2cfd6">
                    <div class="panel panel-primary">
                      <div class="panel-heading">
                        <b>Seleccione un punto adisional</b>
                        <button
                          v-if="!valBoolean"
                          type="button"
                          class="close"
                          @click="cambiarBoolean()"
                          aria-label="Close"
                        >
                          <span aria-hidden="true">+</span>
                        </button>
                        <button
                          v-if="valBoolean"
                          type="button"
                          class="close"
                          @click="cambiarBoolean()"
                          aria-label="Close"
                        >
                          <span aria-hidden="true">×</span>
                        </button>
                      </div>
                      <div class="panel-body" v-if="valBoolean">
                        <div class="form-group row">
                          <div class="col-md-12">
                            <div class="input-group">
                              <div
                                v-for="check in arrayCheckbox"
                                :key="check.id"
                              >
                                <label>{{ check.numerador }}</label>
                                <input
                                  type="checkbox"
                                  v-model="arrayCheckboxSelect"
                                  :value="check"
                                />
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
              <button
                type="button"
                class="btn btn-secondary"
                @click="cerrarModalDescripcion()"
                data-dismiss="modal"
              >
                Cerrar
              </button>
              <button
                type="button"
                class="btn btn-primary"
                @click="guardarDescripcion()"
                data-dismiss="modal"
                title="Guardar descripción"
              >
                Guardar
              </button>
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
      <div
        class="modal fade"
        id="modalResolucion"
        tabindex="-1"
        role="dialog"
        aria-labelledby="myModalLabel"
        style="display: none"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-primary modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" v-text="tituloModal"></h4>
              <button
                type="button"
                class="close"
                @click="cerrarModalResolucion()"
                aria-label="Close"
                data-dismiss="modal"
              >
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group row">
                <div class="col-md-10">
                  <div class="form-group">
                    <textarea
                      v-model="descripcionResolucion"
                      type="textarea"
                      class="form-control"
                      placeholder="Ingrese la descripción deacuerdo al punto del orden día seleccionado."
                      rows="4"
                    ></textarea>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <button
                      type="button"
                      @click="agregarResolucion()"
                      class="btn btn-success form-control btnagregarR"
                      title="Agregar resolución"
                    >
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
                      <tr
                        v-for="(resolucion, index) in arrayResolucionVista"
                        :key="resolucion.id"
                      >
                        <td
                          width="10"
                          align="center"
                          style="vertical-align: middle"
                        >
                          <button
                            @click="eliminarResolucionVista(index)"
                            type="button"
                            class="btn btn-danger btn-sm"
                            title="Eliminar resolución"
                          >
                            <i class="icon-close"></i>
                          </button>
                        </td>
                        <td
                          width="250"
                          align="center"
                          style="vertical-align: middle"
                          v-text="resolucion.codigo"
                        ></td>
                        <td align="justify" style="vertical-align: middle">
                          <input
                            v-model="resolucion.descripcion"
                            type="text"
                            value=""
                            class="form-control"
                          />
                        </td>
                      </tr>
                    </tbody>
                    <tbody v-else>
                      <tr>
                        <td colspan="4">No hay resoluciones agregadas</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                &nbsp;
                <div class="col-md-12" style="border-top: 1px solid #c2cfd6">
                  <div class="panel panel-primary">
                    <div class="panel-heading">
                      <b>Seleccione un punto adisional</b>
                      <button
                        v-if="!valBoolean"
                        type="button"
                        class="close"
                        @click="cambiarBoolean()"
                        aria-label="Close"
                      >
                        <span aria-hidden="true">+</span>
                      </button>
                      <button
                        v-if="valBoolean"
                        type="button"
                        class="close"
                        @click="cambiarBoolean()"
                        aria-label="Close"
                      >
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                    <div class="panel-body" v-if="valBoolean">
                      <div class="form-group row">
                        <div class="col-md-12">
                          <div class="input-group">
                            <div v-for="check in arrayCheckbox" :key="check.id">
                              <label>{{ check.numerador }}</label>
                              <input
                                type="checkbox"
                                v-model="arrayCheckboxSelect"
                                :value="check"
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                @click="cerrarModalResolucion()"
                data-dismiss="modal"
              >
                Cerrar
              </button>
              <button
                type="button"
                class="btn btn-primary"
                @click="guardarResolucion()"
                data-dismiss="modal"
                title="Guardar resolución"
              >
                Guardar
              </button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    </div>
    <!--Fin del modal-->
    <!--Inicio del modal Persona agregar-->
    <div v-if="valModal">
      <div
        class="modal fade"
        id="modalPersona"
        tabindex="-1"
        role="dialog"
        aria-labelledby="myModalLabel"
        style="display: none"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-primary modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" v-text="tituloModal"></h4>
              <button
                type="button"
                class="close"
                @click="cerrarModalPersona()"
                aria-label="Close"
                data-dismiss="modal"
              >
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
                    <input
                      type="text"
                      v-model="buscarP"
                      @keyup.enter="listarPersona(buscarP, criterioP)"
                      class="form-control"
                      placeholder="Texto a buscar"
                    />
                    <button
                      type="submit"
                      @click="listarPersona(buscarP, criterioP)"
                      class="btn btn-primary"
                    >
                      <i class="fa fa-search"></i> Buscar
                    </button>
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
                      <td
                        width="1"
                        align="center"
                        style="vertical-align: middle"
                      >
                        <button
                          type="button"
                          @click="agregarPersonaModal(persona)"
                          class="btn btn-success btn-sm"
                        >
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
                      <td colspan="6">No existe usuario</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                @click="cerrarModalPersona()"
                data-dismiss="modal"
              >
                Cerrar
              </button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    </div>
    <!--Fin del modal-->
  </main>
</template>

<script>
export default {
  data() {
    return {
      arrayDetalle: [],
      arrayConvocatoria: [],
      arrayDetalleEnvio: [],
      arrayDetalleInvitado: [],
      arrayOrdenDia: [],
      arrayPersona: [],
      arrayPersonaB: [],
      nombre: "",
      docUser: "",
      personaUser: "",
      emailPersona: "",
      perfilPersona: "",
      errorActa: 0,
      errorMostrarMsjActa: [],
      idordendia: "",

      //Inicio
      arrayActa: [],
      arrayDetalleActa: [],
      arrayFirmaActa: [],
      arrayListConvocatoria: [],
      arraySelectConvocatoria: [],
      arrayTempConvocatoria: [],
      arrayCheckbox: [],
      arrayCheckboxSelect: [],
      arraySelectOrdendia: [],
      arrayDescripcion: [],
      arrayResolucion: [],
      arrayDescripcionDetalle: [],
      arrayResolucionDetalle: [],
      arrayResolucionVista: [],
      arrayVistaOrdendia: [],
      arrayVistaOrdendia2: [],
      arrayCombinarDetalle: [],
      codigoConvocatoria: "",
      codigoVerifi: "",
      codigo: "",
      titulo: "",
      descripcion: "",
      descripcionActa: "",
      estado: "",
      cedulaRedactor: "",
      apellidoRedactor: "",
      nombreRedactor: "",
      emailRedactor: "",
      perfilRedactor: "",
      valBoolean: false,
      aidConvocatoriaActa: 0,
      valModal: false,
      descripcionResolucion: "",
      descripcionResolucion: "",
      //Fin

      listado: 1,
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      offset: 3,
      criterio: "codigo",
      buscar: "",
      criterioA: "codigo",
      buscarA: "",
      criterioP: "apellidos",
      buscarP: "",
    };
  },
  computed: {
    isActived: function () {
      return this.pagination.current_page;
    },
    pagesNumber: function () {
      if (!this.pagination.to) {
        return [];
      }
      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }
      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }
      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
    mayus: function () {
      let me = this;
      if (me.codigo != "") {
        me.codigo = me.codigo.toUpperCase();
      }
    },
  },
  methods: {
    listarActa(page, buscar, criterio) {
      let me = this;
      var url =
        "/acta?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayActa = respuesta.actas.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function (error) {
          // console.log(error);
        });
    },
    listarPersona(buscar, criterio) {
      let me = this;
      var url =
        "/convocatoria/personas?buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayPersonaB = respuesta.personas;
        })
        .catch(function (error) {
          // console.log(error);
        });
    },
    buscarPersona() {
      let me = this;
      var arrayUser = [];
      var url = "convocatoria/buscarPersona?filtro=" + me.docUser;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          arrayUser = respuesta.personas;

          if (arrayUser.length > 0) {
            me.docUser = arrayUser[0]["numeroIdentificacion"];
            me.personaUser =
              arrayUser[0]["apellidos"] + " " + arrayUser[0]["nombres"];
            me.perfilPersona = arrayUser[0]["perfil"];
            me.emailPersona = arrayUser[0]["EMail"];
          } else {
            me.personaUser = "No existe persona";
          }
        })
        .catch(function (error) {
          // console.log(error);
        });
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      me.pagination.current_page = page;
      me.listarActa(page, buscar, criterio);
    },
    encuentra(descripcion) {
      var sw = 0;
      for (var i = 0; i < this.arrayResolucion.length; i++) {
        if (this.arrayResolucion[i].descripcion == descripcion) {
          sw = true;
        }
      }
      return sw;
    },
    encuentraPersona(cedula) {
      var sw = 0;
      for (var i = 0; i < this.arrayPersona.length; i++) {
        if (this.arrayPersona[i].cedula == cedula) {
          sw = true;
        }
      }
      return sw;
    },
    eliminarResolucionVista(index) {
      let me = this;
      me.arrayResolucionVista.splice(index, 1);
      me.ordenarCodigoResolASC();
      for (let i = 0; i < me.arrayCheckboxSelect.length; i++) {
        for (let j = 0; j < me.arrayResolucion.length; j++) {
          if (
            me.arrayCheckboxSelect[i].id == me.arrayResolucion[j].idordendia
          ) {
            for (let l = 0; l < me.arrayResolucionVista.length; l++) {
              if (
                me.arrayResolucion[j].descripcion ==
                me.arrayResolucionVista[l].descripcion
              ) {
                me.arrayResolucionVista[l].codigo =
                  me.arrayResolucion[j].codigo;
              }
            }
          }
        }
      }
    },
    ordenarCodigoResolASC() {
      let me = this;
      var arraytemp = [];
      arraytemp = me.arrayResolucion;
      var arraytemp2 = [];
      // var arraytemp2 = [];
      // var arraytemp2 = me.arrayResolucion;
      var arrayID = [];
      me.codigo = me.codigo.toUpperCase();
      var pos = me.codigo.length - 2;
      var codigoVal = "";
      var cadCe = "";
      var cont = 1;
      for (let x = 0; x < arraytemp.length; x++) {
        arraytemp2.push({
          idordendia: arraytemp[x].idordendia,
          codigo: "",
          descripcion: arraytemp[x].descripcion,
        });
      }
      for (let i = 0; i < me.arrayVistaOrdendia.length; i++) {
        for (let j = 0; j < arraytemp.length; j++) {
          if (me.arrayVistaOrdendia[i].idordendia == arraytemp[j].idordendia) {
            if (arraytemp2[j].codigo == "") {
              arrayID = [];

              for (let q = 0; q < arraytemp.length; q++) {
                if (arraytemp[j].codigo == arraytemp[q].codigo) {
                  arrayID.push({ id: arraytemp[q].idordendia });
                }
              }
              for (let m = 0; m < arrayID.length; m++) {
                for (let n = 0; n < arraytemp.length; n++) {
                  if (
                    arrayID[m].id == arraytemp[n].idordendia &&
                    arraytemp[n].descripcion == arraytemp[j].descripcion
                  ) {
                    if (arraytemp2[n].codigo == "") {
                      if (cont < 9) {
                        cadCe = "/00";
                      } else {
                        cadCe = "/0";
                      }
                      if (me.codigo.includes("-A", pos)) {
                        codigoVal = me.codigo.slice(0, pos) + cadCe + cont;
                      } else {
                        codigoVal = me.codigo + cadCe + cont;
                      }
                      arraytemp2[n].codigo = codigoVal;
                    }
                  }
                }
              }
              cont++;
            }
          }
        }
      }
      me.arrayResolucion = [];
      me.arrayResolucion = arraytemp2;
      me.llenarVistaDescripcionResolucion();
    },
    eliminarPersona(index) {
      let me = this;
      me.arrayPersona.splice(index, 1);
    },
    metodoSortResolu() {
      let me = this;
      var arrayTemp = [];
      me.arrayResolucion.sort(function (o1, o2) {
        if (o1.idordendia > o2.idordendia) {
          //comparación lexicogŕafica
          return 1;
        } else if (o1.idordendia < o2.idordendia) {
          return -1;
        }
        return 0;
      });
      // for (var i = 0; i < me.arrayResolucion.length; i++) {
      //   me.arrayResolucion[i].codigo = "";
      // }
    },
    ordenarCodigoResol() {
      let me = this;
      //me.metodoSortResolu();
      me.codigo = me.codigo.toUpperCase();
      var pos = me.codigo.length - 2;
      var cadCe = "";
      var codigoVal = "";
      var contC = 0;
      var contV2 = false;
      var contC2 = 0;

      var hash = {};
      var arrayCont = [];
      arrayCont = me.arrayResolucion.filter((o) =>
        hash[o.codigo] ? false : (hash[o.codigo] = true)
      );
      contC = arrayCont.length;

      for (var i = 0; i < me.arrayResolucionVista.length; i++) {
        contV2 = false;
        for (var j = 0; j < arrayCont.length; j++) {
          if (me.arrayResolucionVista[i].codigo == arrayCont[j].codigo) {
            contV2 = true;
          }
        }
        if (contV2) {
          contC2++;
        }
      }
      var contador = contC - contC2 + me.arrayResolucionVista.length + 1;

      if (contador < 9) {
        cadCe = "/00";
      } else {
        cadCe = "/0";
      }
      if (me.codigo.includes("-A", pos)) {
        codigoVal = me.codigo.slice(0, pos) + cadCe + contador;
      } else {
        codigoVal = me.codigo + cadCe + contador;
      }
      return codigoVal;
    },
    encuentraResolucion(descripcion) {
      var sw = 0;
      for (var i = 0; i < this.arrayResolucionVista.length; i++) {
        if (this.arrayResolucionVista[i].descripcion == descripcion) {
          sw = true;
        }
      }
      return sw;
    },
    agregarResolucion() {
      let me = this;
      if (me.descripcionResolucion == "") {
        Swal.fire({
          title: "Error...",
          text: "Agrege una descripción!",
          icon: "error",
          confirmButtonText: "OK",
        });
      } else {
        if (!me.encuentraResolucion(me.descripcionResolucion)) {
          //for (var i = 0; i < me.arrayCheckboxSelect.length; i++) {
          me.arrayResolucionVista.push({
            //idordendia: me.arrayCheckboxSelect[i].id,
            codigo: me.ordenarCodigoResol(),
            descripcion: me.descripcionResolucion,
          });
          //}
          me.descripcionResolucion = "";
        } else {
          Swal.fire({
            title: "Error...",
            text: "La descripción ya existe!",
            icon: "error",
            confirmButtonText: "OK",
          });
        }
      }
    },
    agregarPersona() {
      let me = this;
      if (me.personaUser != "") {
        if (me.encuentraPersona(me.docUser)) {
          Swal.fire({
            title: "Error...",
            text: "La persona ya se encuentra agregado!",
            icon: "error",
            confirmButtonText: "OK",
          });
          me.docUser = "";
          me.personaUser = "";
        } else {
          me.arrayPersona.push({
            nombre: me.personaUser,
            EMail: me.emailPersona,
            cedula: me.docUser,
            perfil: me.perfilPersona,
          });
          me.docUser = "";
          me.personaUser = "";
          me.emailPersona = "";
          me.perfilPersona = "";
        }
      }
    },
    agregarPersonaModal(data = []) {
      let me = this;
      if (me.encuentraPersona(data["numeroIdentificacion"])) {
        Swal.fire({
          title: "Error...",
          text: "La perona ya se encuentra agregado!",
          icon: "error",
          confirmButtonText: "OK",
        });
        me.buscarA = "";
      } else {
        me.arrayPersona.push({
          cedula: data["numeroIdentificacion"],
          nombre: data["apellidos"] + " " + data["nombres"],
          EMail: data["EMail"],
          perfil: data["perfil"],
        });
      }
    },
    agregarConvocatoriaModal(data = []) {
      let me = this;
      if (me.codigo != "") {
        if (me.arraySelectConvocatoria.length > 0) {
          Swal.fire({
            title: "Error...",
            text: "Solo se puede seleccionar una convocatoria!",
            icon: "error",
            confirmButtonText: "OK",
          });
          me.buscarA = "";
          me.arrayListConvocatoria = [];
          me.valModal = false;
        } else {
          me.arraySelectConvocatoria.push({
            id: data["id"],
            titulo: data["titulo"],
            codigo: data["codigo"],
            descripcion: data["descripcion"],
            estado: data["estado"],
            iduser: data["iduser"],
            apellidos: data["apellidos"],
            nombres: data["nombres"],
            EMail: data["EMail"],
            perfil: data["perfil"],
          });
          me.arrayListConvocatoria = [];
          me.valModal = false;
          var url =
            "/convocatoria/obtenerOrdenDias?id=" +
            me.arraySelectConvocatoria[0]["id"];
          axios
            .get(url)
            .then(function (response) {
              var respuesta = response.data;
              me.arraySelectOrdendia = respuesta.orden_dias;
              me.inicializarOrdenDiaSelect();
            })
            .catch(function (error) {
              // console.log(error);
            });
        }
      } else {
        Swal.fire({
          title: "Error...",
          text: "Debe ingresar un código de acta!",
          icon: "error",
          confirmButtonText: "OK",
        });
      }
    },
    agregarConvocatoria() {
      let me = this;
      if (me.codigoVerifi == "" || me.codigoVerifi == "Código inválido") {
        Swal.fire({
          title: "Error...",
          text: "Ingrese un código válido de convocatoria!",
          icon: "error",
          confirmButtonText: "OK",
        });
      } else {
        if (me.codigo != "") {
          if (me.arraySelectConvocatoria.length == 0) {
            me.arraySelectConvocatoria = me.arrayTempConvocatoria;
            me.arrayTempConvocatoria = [];
            me.codigoConvocatoria = "";
            me.codigoVerifi = "";

            var url =
              "/convocatoria/obtenerOrdenDias?id=" +
              me.arraySelectConvocatoria[0]["id"];
            axios
              .get(url)
              .then(function (response) {
                var respuesta = response.data;
                me.arraySelectOrdendia = respuesta.orden_dias;
                me.inicializarOrdenDiaSelect();
              })
              .catch(function (error) {
                // console.log(error);
              });
          } else {
            Swal.fire({
              title: "Error...",
              text: "Solo se puede seleccionar una convocatoria!",
              icon: "error",
              confirmButtonText: "OK",
            });
          }
        } else {
          Swal.fire({
            title: "Error...",
            text: "Debe ingresar un código de acta!",
            icon: "error",
            confirmButtonText: "OK",
          });
        }
      }
    },
    eliminarconvocatoria() {
      let me = this;
      me.arraySelectConvocatoria = [];
      me.arraySelectOrdendia = [];
      me.arrayVistaOrdendia = [];
      me.arrayVistaOrdendia2 = [];
      me.arrayDescripcion = [];
      me.arrayResolucion = [];
      me.codigoVerifi = "";
    },
    combinarDescripcionResolucion() {
      let me = this;
      var arrayTemp = [];
      var idT = "";
      var numeradorT = "";
      var nombreT = "";
      var descripcionT = "";
      var resolucionT = "";
      var cont = 0;
      var nombreAlT = "";
      //========================================================
      for (var i = 0; i < me.arrayVistaOrdendia.length; i++) {
        cont = 0;
        idT = "";
        numeradorT = "";
        nombreT = "";
        descripcionT = "";
        for (var j = 0; j < me.arrayVistaOrdendia.length; j++) {
          if (
            (me.arrayVistaOrdendia[i].descripcion != "Ninguna" &&
              me.arrayVistaOrdendia[i].descripcion ==
                me.arrayVistaOrdendia[j].descripcion) ||
            (me.arrayVistaOrdendia[i].resolucion != "Ninguna" &&
              me.arrayVistaOrdendia[i].resolucion ==
                me.arrayVistaOrdendia[j].resolucion)
          ) {
            cont++;
            if (cont > 1) {
              idT += ",";
              numeradorT += ",";
              nombreT += ",";
            }
            idT += me.arrayVistaOrdendia[j].idordendia;
            numeradorT += me.arrayVistaOrdendia[j].numerador;
            nombreT += me.arrayVistaOrdendia[j].nombre;
            nombreAlT = me.arrayVistaOrdendia[j].nombrealternativo;
            descripcionT = me.arrayVistaOrdendia[j].descripcion;
            resolucionT = me.arrayVistaOrdendia[j].resolucion;
          }
        }
        if (cont > 1) {
          if (!me.buscarD(idT, arrayTemp)) {
            arrayTemp.push({
              idordendia: idT,
              numerador: numeradorT,
              nombre: nombreT,
              nombrealternativo: nombreAlT,
              descripcion: descripcionT,
              resolucion: resolucionT,
            });
          }
        } else {
          arrayTemp.push({
            idordendia: me.arrayVistaOrdendia[i].idordendia,
            numerador: me.arrayVistaOrdendia[i].numerador,
            nombre: me.arrayVistaOrdendia[i].nombre,
            nombrealternativo: me.arrayVistaOrdendia[i].nombrealternativo,
            descripcion: me.arrayVistaOrdendia[i].descripcion,
            resolucion: me.arrayVistaOrdendia[i].resolucion,
          });
        }
      }

      me.arrayVistaOrdendia2 = [];
      me.arrayVistaOrdendia2 = arrayTemp;
      //========================================================
    },
    inicializarOrdenDiaSelect() {
      let me = this;
      me.arrayVistaOrdendia = [];
      for (var i = 0; i < me.arraySelectOrdendia.length; i++) {
        me.arrayVistaOrdendia.push({
          idordendia: me.arraySelectOrdendia[i]["id"] + "",
          numerador: me.arraySelectOrdendia[i]["numerador"] + "",
          nombre: me.arraySelectOrdendia[i]["nombre"],
          nombrealternativo: "",
          descripcion: "Ninguna",
          resolucion: "Ninguna",
        });
      }
      me.arrayVistaOrdendia2 = [];
      for (var i = 0; i < me.arraySelectOrdendia.length; i++) {
        me.arrayVistaOrdendia2.push({
          idordendia: me.arraySelectOrdendia[i]["id"] + "",
          numerador: me.arraySelectOrdendia[i]["numerador"] + "",
          nombre: me.arraySelectOrdendia[i]["nombre"],
          nombrealternativo: "",
          descripcion: "Ninguna",
          resolucion: "Ninguna",
        });
      }
    },
    agregarNombreAlt() {
      let me = this;
      var arrayId = [];
      var cadenaId = "";
      for (let i = 0; i < me.arrayVistaOrdendia2.length; i++) {
        cadenaId = me.arrayVistaOrdendia2[i].idordendia + "";
        arrayId = cadenaId.split(",");
        if (arrayId.length > 1) {
          for (let k = 0; k < arrayId.length; k++) {
            for (let j = 0; j < me.arrayVistaOrdendia.length; j++) {
              if (arrayId[k] == me.arrayVistaOrdendia[j].idordendia) {
                me.arrayVistaOrdendia[j].nombrealternativo =
                  me.arrayVistaOrdendia2[i].nombrealternativo;
              }
            }
          }
        } else {
          for (let j = 0; j < me.arrayVistaOrdendia.length; j++) {
            if (arrayId[0] == me.arrayVistaOrdendia[j].idordendia) {
              me.arrayVistaOrdendia[j].nombrealternativo =
                me.arrayVistaOrdendia2[i].nombrealternativo;
            }
          }
        }
      }
    },
    llenarVistaDescripcionResolucion() {
      let me = this;
      me.agregarNombreAlt();
      //me.inicializarOrdenDiaSelect();
      var cont = 0;
      var cade = "";
      for (var i = 0; i < me.arrayVistaOrdendia.length; i++) {
        //===Verificar actualizacion descripcion
        var verifivarDes = false;
        for (var j = 0; j < me.arrayDescripcion.length; j++) {
          if (
            me.arrayVistaOrdendia[i].idordendia ==
            me.arrayDescripcion[j].idordendia
          ) {
            me.arrayVistaOrdendia[i].descripcion =
              me.arrayDescripcion[j].descripcion;
            verifivarDes = true;
          }
        }
        if (verifivarDes == false) {
          me.arrayVistaOrdendia[i].descripcion = "Ninguna";
        }
        //===Fin
        //===Verificar actualizacion resolucion
        cont = 0;
        cade = "";
        for (var q = 0; q < me.arrayResolucion.length; q++) {
          if (
            me.arrayVistaOrdendia[i].idordendia ==
            me.arrayResolucion[q].idordendia
          ) {
            if (cont > 0) {
              cade += ",";
            } else {
              me.codigo = me.codigo.toUpperCase();
              var pos = me.codigo.length - 2;
              if (me.codigo.includes("-A", pos)) {
                cade += me.codigo.slice(0, pos) + "/(";
              } else {
                cade += me.codigo + "/(";
              }
            }
            cade += me.arrayResolucion[q].codigo.slice(-3);
            cont++;
          }
        }
        if (cade != "") {
          me.arrayVistaOrdendia[i].resolucion = cade + ")";
        } else {
          me.arrayVistaOrdendia[i].resolucion = "Ninguna";
        }
        //===Fin
      }
    },
    encuentraCodigo(codigo, array = []) {
      var sw = 0;
      if (array.length == 0) {
        sw = false;
      } else {
        for (var i = 0; i < array.length; i++) {
          if (array[i].codigo == codigo) {
            sw = true;
          }
        }
      }
      return sw;
    },
    guardarDescripcion() {
      let me = this;

      if (me.descripcionActa == "") {
        Swal.fire({
          title: "Error...",
          text: "Debe de ingresar una descripción!",
          icon: "error",
          confirmButtonText: "OK",
        });
      } else {
        if (me.arrayCheckboxSelect.length > 0) {
          var confirmar = false;
          var valresolucion = [];
          for (let i = 0; i < me.arrayCheckboxSelect.length; i++) {
            for (let j = 0; j < me.arrayResolucion.length; j++) {
              if (
                me.arrayCheckboxSelect[i].id == me.arrayResolucion[j].idordendia
              ) {
                if (
                  !me.encuentraCodigo(
                    me.arrayResolucion[j].codigo,
                    valresolucion
                  )
                ) {
                  valresolucion.push({
                    codigo: me.arrayResolucion[j].codigo,
                    descripcion: me.arrayResolucion[j].descripcion,
                  });
                }
              }
            }
          }
          if (valresolucion.length > 0) {
            for (let i = 0; i < me.arrayCheckboxSelect.length; i++) {
              confirmar = false;
              for (let j = 0; j < me.arrayResolucion.length; j++) {
                if (
                  me.arrayCheckboxSelect[i].id ==
                  me.arrayResolucion[j].idordendia
                ) {
                  confirmar = true;
                }
              }
              if (confirmar == false) {
                for (let j = 0; j < valresolucion.length; j++) {
                  me.arrayResolucion.push({
                    idordendia: me.arrayCheckboxSelect[i].id,
                    codigo: valresolucion[j].codigo,
                    descripcion: valresolucion[j].descripcion,
                  });
                }
                me.metodoSortResolu();
              }
            }
          }
          for (var i = 0; i < me.arrayCheckboxSelect.length; i++) {
            var verificar = false;
            for (var j = 0; j < me.arrayDescripcion.length; j++) {
              if (
                me.arrayCheckboxSelect[i].id ==
                me.arrayDescripcion[j].idordendia
              ) {
                verificar = true;
                me.arrayDescripcion[j].descripcion = me.descripcionActa;
              }
            }
            if (verificar == false) {
              me.arrayDescripcion.push({
                idordendia: me.arrayCheckboxSelect[i].id,
                descripcion: me.descripcionActa,
                //nombrealternativo: "",
              });
            }
          }
        }
        me.llenarVistaDescripcionResolucion();
        me.metodoSortResolu();
        me.ordenarCodigoResolASC();
        me.combinarDescripcionResolucion();
        //me.combinarDescripcion();
        me.descripcionActa = "";
        me.arrayCheckbox = [];
        me.arrayCheckboxSelect = [];
      }
    },
    guardarResolucion() {
      let me = this;
      var confirmar = false;
      var valdescripcion = "";

      for (var i = 0; i < me.arrayCheckboxSelect.length; i++) {
        for (let j = 0; j < me.arrayDescripcion.length; j++) {
          if (
            me.arrayCheckboxSelect[i].id == me.arrayDescripcion[j].idordendia
          ) {
            valdescripcion = me.arrayDescripcion[j].descripcion;
          }
        }
        for (var j = me.arrayResolucion.length; j > 0; j--) {
          if (
            me.arrayCheckboxSelect[i].id == me.arrayResolucion[j - 1].idordendia
          ) {
            me.arrayResolucion.splice(j - 1, 1);
          }
        }
      }
      if (valdescripcion != "") {
        for (let i = 0; i < me.arrayCheckboxSelect.length; i++) {
          confirmar = false;
          for (let j = 0; j < me.arrayDescripcion.length; j++) {
            if (
              me.arrayCheckboxSelect[i].id == me.arrayDescripcion[j].idordendia
            ) {
              confirmar = true;
            }
          }
          if (confirmar == false) {
            me.arrayDescripcion.push({
              idordendia: me.arrayCheckboxSelect[i].id,
              descripcion: valdescripcion,
            });
          }
        }
      }
      for (var i = 0; i < me.arrayResolucionVista.length; i++) {
        for (var j = 0; j < me.arrayCheckboxSelect.length; j++) {
          me.arrayResolucion.push({
            idordendia: me.arrayCheckboxSelect[j].id,
            codigo: me.arrayResolucionVista[i].codigo,
            descripcion: me.arrayResolucionVista[i].descripcion,
          });
        }
      }
      me.arrayResolucionVista = [];
      //me.llenarVistaDescripcionResolucion();
      me.llenarVistaDescripcionResolucion();
      me.metodoSortResolu();
      me.ordenarCodigoResolASC();
      me.combinarDescripcionResolucion();
      me.arrayCheckbox = [];
      me.arrayCheckboxSelect = [];
    },
    metodoSortOrdenDia() {
      let me = this;
      me.arraySelectOrdendia.sort(function (o1, o2) {
        if (o1.numerador > o2.numerador) {
          //comparación lexicogŕafica
          return 1;
        } else if (o1.numerador < o2.numerador) {
          return -1;
        }
        return 0;
      });
    },
    eliminarDescripcion(id) {
      let me = this;
      me.llenarCheckbox2(id);
      for (let k = 0; k < me.arrayVistaOrdendia2.length; k++) {
        if (
          id == me.arrayVistaOrdendia2[k].idordendia &&
          me.arrayVistaOrdendia2[k].resolucion == "Ninguna"
        ) {
          me.arrayVistaOrdendia2[k].nombrealternativo = "";
        }
      }
      for (let i = 0; i < me.arrayCheckboxSelect.length; i++) {
        for (let j = 0; j < me.arrayDescripcion.length; j++) {
          if (
            me.arrayCheckboxSelect[i].id == me.arrayDescripcion[j].idordendia
          ) {
            me.arrayDescripcion.splice(j, 1);
          }
        }
      }
      me.llenarVistaDescripcionResolucion();
      me.metodoSortResolu();
      me.ordenarCodigoResolASC();
      me.combinarDescripcionResolucion();

      me.arrayCheckbox = [];
      me.arrayCheckboxSelect = [];
    },
    eliminarResolucion(index) {
      let me = this;
      me.llenarCheckbox2(index);
      for (let k = 0; k < me.arrayVistaOrdendia2.length; k++) {
        if (
          index == me.arrayVistaOrdendia2[k].idordendia &&
          me.arrayVistaOrdendia2[k].descripcion == "Ninguna"
        ) {
          me.arrayVistaOrdendia2[k].nombrealternativo = "";
        }
      }
      for (var i = 0; i < me.arrayCheckboxSelect.length; i++) {
        for (var j = me.arrayResolucion.length; j > 0; j--) {
          if (
            me.arrayCheckboxSelect[i].id == me.arrayResolucion[j - 1].idordendia
          ) {
            me.arrayResolucion.splice(j - 1, 1);
          }
        }
      }
      //me.llenarVistaDescripcionResolucion();
      me.llenarVistaDescripcionResolucion();
      me.metodoSortResolu();
      me.ordenarCodigoResolASC();
      me.combinarDescripcionResolucion();
      //me.combinarDescripcion();
      me.arrayCheckbox = [];
      me.arrayCheckboxSelect = [];
    },
    editarDescripcion(id) {
      let me = this;
      me.tituloModal = "Editar descripción";
      me.valBoolean = false;
      me.llenarCheckbox2(id);
      me.agregarNombreAlt();
      for (var i = 0; i < me.arrayVistaOrdendia2.length; i++) {
        if (me.arrayVistaOrdendia2[i].idordendia == id) {
          me.descripcionActa = me.arrayVistaOrdendia2[i].descripcion;
        }
      }
    },
    editarResolucion(id) {
      let me = this;
      me.tituloModal = "Editar resolción";
      me.arrayResolucionVista = [];
      this.valBoolean = false;
      this.idordendia = id;
      me.llenarCheckbox2(id);
      me.agregarNombreAlt();
      for (var i = 0; i < me.arrayCheckboxSelect.length; i++) {
        for (var j = 0; j < me.arrayResolucion.length; j++) {
          if (
            me.arrayCheckboxSelect[i].id == me.arrayResolucion[j].idordendia
          ) {
            if (
              !me.buscarR(
                me.arrayResolucion[j].descripcion,
                me.arrayResolucionVista
              )
            ) {
              me.arrayResolucionVista.push({
                //idordendia: me.arrayResolucion[j].idordendia,
                codigo: me.arrayResolucion[j].codigo,
                descripcion: me.arrayResolucion[j].descripcion,
              });
            }
          }
        }
      }
    },
    cambiarBoolean() {
      let me = this;
      if (me.valBoolean == true) {
        me.valBoolean = false;
      } else {
        me.valBoolean = true;
      }
    },
    llenarCheckbox2(id) {
      let me = this;
      me.arrayCheckbox = [];
      me.arrayCheckboxSelect = [];
      var arrayId = [];
      var cadenaId = "";
      var arrayNro = [];
      var cadenaNro = "";
      for (var i = 0; i < me.arrayVistaOrdendia2.length; i++) {
        if (me.arrayVistaOrdendia2[i].idordendia == id) {
          cadenaId = id + "";
          arrayId = cadenaId.split(",");
          if (arrayId.length > 1) {
            cadenaNro = me.arrayVistaOrdendia2[i]["numerador"] + "";
            arrayNro = cadenaNro.split(",");
            for (var j = 0; j < arrayId.length; j++) {
              me.arrayCheckboxSelect.push({
                id: arrayId[j],
                numerador: arrayNro[j],
              });
            }
          } else {
            me.arrayCheckboxSelect.push({
              id: me.arrayVistaOrdendia2[i].idordendia,
              numerador: me.arrayVistaOrdendia2[i]["numerador"],
            });
          }
        } else {
          if (
            me.arrayVistaOrdendia2[i]["descripcion"] == "Ninguna" &&
            me.arrayVistaOrdendia2[i]["resolucion"] == "Ninguna"
          ) {
            //if (me.arrayVistaOrdendia2[i]["resolucion"] == "Ninguna") {
            cadenaId = me.arrayVistaOrdendia2[i].idordendia + "";
            arrayId = cadenaId.split(",");
            if (arrayId.length > 1) {
              cadenaNro = me.arrayVistaOrdendia2[i]["numerador"] + "";
              arrayNro = cadenaNro.split(",");
              for (var j = 0; j < arrayId.length; j++) {
                me.arrayCheckbox.push({
                  id: arrayId[j],
                  numerador: arrayNro[j],
                });
              }
            } else {
              me.arrayCheckbox.push({
                id: me.arrayVistaOrdendia2[i].idordendia,
                numerador: me.arrayVistaOrdendia2[i]["numerador"],
              });
            }
            //}
          }
        }
      }
    },
    buscarComa(val) {
      var sw = 0;
      var cadena = val + "";
      var r = cadena.indexOf(",");
      if (r < 0) {
        sw = true;
      }
      return sw;
    },
    buscarD(id, data = []) {
      var sw = 0;
      for (var i = 0; i < data.length; i++) {
        if (data[i].idordendia == id) {
          sw = true;
        }
      }
      return sw;
    },
    buscarR(resolucion, data = []) {
      var sw = 0;
      for (var i = 0; i < data.length; i++) {
        if (data[i].descripcion == resolucion) {
          sw = true;
        }
      }
      return sw;
    },
    verificarConvocatorias(convocatoriasT = [], buscar) {
      let me = this;
      var convocatorias = [];
      var convocatoriasV = [];
      var url = "/acta/buscarConvocatoriaActa";
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          convocatoriasV = respuesta.convocatorias;
          var cont = 0;
          for (var i = 0; i < convocatoriasT.length; i++) {
            cont = 0;
            for (var j = 0; j < convocatoriasV.length; j++) {
              if (convocatoriasT[i].id == convocatoriasV[j].id) {
                cont++;
              }
              if (
                buscar.toUpperCase() == convocatoriasV[j].codigo.toUpperCase()
              ) {
                Swal.fire({
                  title: "Error...",
                  text: "Esta convocatoria ya tiene su acta definida!",
                  icon: "error",
                  confirmButtonText: "OK",
                });
              }
              if (
                buscar.toUpperCase() == convocatoriasV[j].titulo.toUpperCase()
              ) {
                Swal.fire({
                  title: "Error...",
                  text: "Esta convocatoria ya tiene su acta definida!",
                  icon: "error",
                  confirmButtonText: "OK",
                });
              }
              if (
                buscar.toUpperCase() ==
                convocatoriasV[j].descripcion.toUpperCase()
              ) {
                Swal.fire({
                  title: "Error...",
                  text: "Esta convocatoria ya tiene su acta definida!",
                  icon: "error",
                  confirmButtonText: "OK",
                });
              }
            }
            if (cont == 0) {
              convocatorias.push({
                id: convocatoriasT[i].id,
                iduser: convocatoriasT[i].iduser,
                titulo: convocatoriasT[i].titulo,
                codigo: convocatoriasT[i].codigo,
                descripcion: convocatoriasT[i].descripcion,
                estado: convocatoriasT[i].estado,
                condicion: convocatoriasT[i].condicion,
                apellidos: convocatoriasT[i].apellidos,
                nombres: convocatoriasT[i].nombres,
                EMail: convocatoriasT[i].EMail,
                perfil: convocatoriasT[i].perfil,
              });
            }
          }
        })
        .catch(function (error) {
          // console.log(error);
        });
      return convocatorias;
    },
    listarConvocatoriaS(buscar, criterio) {
      let me = this;
      if (buscar != "") {
        var url =
          "/acta/buscarConvocatorias?buscar=" +
          buscar +
          "&criterio=" +
          criterio;
        axios
          .get(url)
          .then(function (response) {
            var respuesta = response.data;
            me.arrayListConvocatoria = me.verificarConvocatorias(
              respuesta.convocatorias,
              buscar
            );
          })
          .catch(function (error) {
            // console.log(error);
          });
      } else {
        me.arrayListConvocatoria = [];
        Swal.fire({
          title: "Error...",
          text: "Ingrese un texto de busqueda!",
          icon: "error",
          confirmButtonText: "OK",
        });
      }
    },
    buscarConvocatoriaI(buscar) {
      let me = this;
      if (me.codigo != "") {
        var url =
          "/acta/buscarConvocatoria?buscar=" + buscar + "&criterio=codigo";
        axios
          .get(url)
          .then(function (response) {
            var respuesta = response.data;
            me.arrayTempConvocatoria = respuesta.convocatoria;
            if (me.arrayTempConvocatoria.length > 0) {
              var url = "/acta/buscarConvocatoriaActa";
              axios
                .get(url)
                .then(function (response) {
                  var respuesta = response.data;
                  var convocatoriasV = respuesta.convocatorias;
                  var cont = 0;
                  for (var j = 0; j < convocatoriasV.length; j++) {
                    if (
                      me.arrayTempConvocatoria[0].id == convocatoriasV[j].id
                    ) {
                      me.arrayTempConvocatoria = [];
                      me.codigoVerifi = "Código inválido";
                      Swal.fire({
                        title: "Error...",
                        text: "Esta convocatoria ya tiene su acta definida!",
                        icon: "error",
                        confirmButtonText: "OK",
                      });
                    } else {
                      me.codigoVerifi = "Código valido";
                    }
                  }
                })
                .catch(function (error) {
                  // console.log(error);
                });
            } else {
              me.codigoVerifi = "Código inválido";
            }
          })
          .catch(function (error) {
            // console.log(error);
          });
      } else {
        Swal.fire({
          title: "Error...",
          text: "Debe ingresar un código de acta!",
          icon: "error",
          confirmButtonText: "OK",
        });
      }
    },
    registrarActa() {
      let me = this;
      me.agregarNombreAlt();
      if (me.validarActa()) {
        var cade = "<p align='left' >";
        for (let i = 0; i < me.errorMostrarMsjActa.length; i++) {
          if (i > 0) {
            cade += " <br> ";
          }
          cade += me.errorMostrarMsjActa[i];
        }
        cade += "</p>";
        Swal.fire({
          title: "Error!",
          html: cade,
          icon: "error",
          confirmButtonText: "OK",
        });
      } else {
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger",
          },
          buttonsStyling: false,
        });
        swalWithBootstrapButtons
          .fire({
            title: "¿Está seguro(a) de guardar el acta?",
            text: "No se podra revertir esto!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Si, Guardar",
            cancelButtonText: "No, Cancelar",
            reverseButtons: false,
          })
          .then((result) => {
            if (result.isConfirmed) {
              swalWithBootstrapButtons.fire(
                "Guardada!",
                "El acta ha sido guardada.",
                "success"
              );
              //Guardar Acta
              axios
                .post("/acta/registrar", {
                  titulo: me.titulo,
                  codigo: me.codigo,
                  descripcion: me.descripcion,
                  data_ordendia: me.arrayVistaOrdendia,
                  data_descripcion: me.arrayDescripcion,
                  data_resolucion: me.arrayResolucion,
                  data_persona: me.arrayPersona,
                })
                .then(function (response) {
                  me.listado = 1;
                  me.listarActa(1, "", "codigo");
                  me.titulo = "";
                  me.codigo = "";
                  me.descripcion = "";
                  me.tituloModal = "";
                  me.arraySelectConvocatoria = [];
                  me.arraySelectOrdendia = [];
                  me.arrayVistaOrdendia = [];
                  me.arrayVistaOrdendia2 = [];
                  me.arrayPersona = [];
                  me.arrayPersonaB = [];
                  me.arrayDescripcion = [];
                  me.arrayResolucion = [];
                })
                .catch(function (error) {
                  // console.log(error);
                });
            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
            ) {
              swalWithBootstrapButtons.fire(
                "Cancelada",
                "La acta todavía no fue guardada.",
                "error"
              );
            }
          });
      }
    },
    validarActa() {
      let me = this;
      me.errorActa = 0;
      me.errorMostrarMsjActa = [];
      if (!me.titulo)
        me.errorMostrarMsjActa.push("Ingrese el título del acta.");
      if (!me.codigo)
        me.errorMostrarMsjActa.push("Ingrese el código del acta.");
      if (!me.descripcion)
        me.errorMostrarMsjActa.push("Ingrese la descripción del acta.");
      if (me.arrayVistaOrdendia2.length == 0) {
        me.errorMostrarMsjActa.push("Seleccione una convocatoria.");
      } else {
        var confirmarv = false;
        for (let i = 0; i < me.arrayVistaOrdendia.length; i++) {
          if (
            me.arrayVistaOrdendia[i].descripcion != "Ninguna" ||
            me.arrayVistaOrdendia[i].resolucion != "Ninguna"
          ) {
            confirmarv = true;
          }
        }
        if (confirmarv == false) {
          me.errorMostrarMsjActa.push(
            "Ingrese una descripción o una resolución."
          );
        }
      }
      var arrayId = [];
      var cadenaId = "";
      var confirmar = false;
      var cade = "Ingrese un nombre en los puntos :";
      for (let i = 0; i < me.arrayVistaOrdendia2.length; i++) {
        cadenaId = me.arrayVistaOrdendia2[i].idordendia + "";
        arrayId = cadenaId.split(",");
        if (arrayId.length > 1) {
          if (me.arrayVistaOrdendia2[i].nombrealternativo == "") {
            confirmar = true;
            cade +=
              "<br>&nbsp;&nbsp;•   (" +
              me.arrayVistaOrdendia2[i].idordendia +
              ").";
          }
        }
      }
      if (confirmar == true) {
        me.errorMostrarMsjActa.push(cade);
      }
      if (me.arrayPersona.length <= 0)
        me.errorMostrarMsjActa.push(
          "Seleccione uno o varios usuarios dirigidos."
        );

      if (me.errorMostrarMsjActa.length) me.errorActa = 1;
      return me.errorActa;
    },
    mostrarDetalleActa() {
      let me = this;
      this.listado = 0;
      me.titulo = "";
      //me.codigo = "";
      me.descripcion = "";
      me.arrayDetalleActa = [];
      me.arraySelectConvocatoria = [];
      me.arraySelectOrdendia = [];
    },
    ocultarAgregarActa() {
      let me = this;
      me.listado = 1;
      me.titulo = "";
      me.codigo = "";
      me.descripcion = "";
      me.tituloModal = "";
      me.arraySelectConvocatoria = [];
      me.arraySelectOrdendia = [];
      me.arrayVistaOrdendia = [];
      me.arrayVistaOrdendia2 = [];
      me.arrayPersona = [];
      me.arrayPersonaB = [];
      me.arrayDescripcion = [];
      me.arrayResolucion = [];
    },
    ordenarPersonaInv() {
      let me = this;
      var arrayTemp = [];
      var cont = 0;
      var text = "";
      for (var i = 0; i < me.arrayDetalleInvitado.length; i++) {
        cont = 0;
        text = "";
        for (var j = 0; j < me.arrayDetalleInvitado.length; j++) {
          if (
            me.arrayDetalleInvitado[i].numeroIdentificacion ==
            me.arrayDetalleInvitado[j].numeroIdentificacion
          ) {
            if (cont > 0) {
              text += ", ";
            }
            cont++;
            text += me.arrayDetalleInvitado[j].numerador;
          }
        }
        if (
          !me.encuentraPuntoInv(
            me.arrayDetalleInvitado[i].numeroIdentificacion,
            arrayTemp
          )
        ) {
          arrayTemp.push({
            numerador: text,
            numeroIdentificacion:
              me.arrayDetalleInvitado[i].numeroIdentificacion,
            apellidos: me.arrayDetalleInvitado[i].apellidos,
            nombres: me.arrayDetalleInvitado[i].nombres,
            EMail: me.arrayDetalleInvitado[i].EMail,
            perfil: me.arrayDetalleInvitado[i].perfil,
          });
        }
      }
      me.arrayDetalleInvitado = arrayTemp;
    },
    encuentraPuntoInv(numeroIdentificacion, array = []) {
      var sw = 0;
      for (var i = 0; i < array.length; i++) {
        if (array[i].numeroIdentificacion == numeroIdentificacion) {
          sw = true;
        }
      }
      return sw;
    },
    verActa(id) {
      let me = this;
      me.aidConvocatoriaActa = 0;
      me.arrayDescripcionDetalle = [];
      me.arrayResolucionDetalle = [];
      me.arrayDetalle = [];
      me.arrayDetalleEnvio = [];
      me.arrayDetalleInvitado = [];

      me.listado = 2;

      //Obtener datos de la convocatoria
      var arrayActaT = [];
      var url = "/acta/obtenerCabecera?id=" + id;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          arrayActaT = respuesta.acta;
          me.titulo = arrayActaT[0]["titulo"];
          me.codigo = arrayActaT[0]["codigo"];
          me.descripcion = arrayActaT[0]["descripcion"];
          me.estado = arrayActaT[0]["estado"];
          //Ver redactor
          me.cedulaRedactor = arrayActaT[0]["iduser"];
          me.apellidoRedactor = arrayActaT[0]["apellidos"];
          me.nombreRedactor = arrayActaT[0]["nombres"];
          me.emailRedactor = arrayActaT[0]["EMail"];
          me.perfilRedactor = arrayActaT[0]["perfil"];
        })
        .catch(function (error) {
          // console.log(error);
        });
      //Otener Descripciones
      var url = "/acta/obtenerDescripcion?id=" + id;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayDescripcionDetalle = respuesta.descripciones;
        })
        .catch(function (error) {
          // console.log(error);
        });
      //Otener Resoluciones
      var url = "/acta/obtenerResolucion?id=" + id;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayResolucionDetalle = respuesta.resoluciones;
        })
        .catch(function (error) {
          // console.log(error);
        });
      //Otener Id Convocatoria
      var url = "/acta/obtenerIdConvocatoria?id=" + id;
      var arrayIdTem = [];
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          arrayIdTem = respuesta.idConvocatoria;
          me.aidConvocatoriaActa = arrayIdTem[0]["idconvocatoria"];
          //Obtener datos de la Onden dia
          var url =
            "/convocatoria/obtenerOrdenDias?id=" + me.aidConvocatoriaActa;
          axios
            .get(url)
            .then(function (response) {
              var respuesta = response.data;
              me.arrayDetalle = respuesta.orden_dias;
              me.inicializarOrdenDiaSelectDetalle();
              me.llenarVistaDescripcionResolucionDetalle();
              me.combinarDescripcionResolucionDetalle();
            })
            .catch(function (error) {
              // console.log(error);
            });
        })
        .catch(function (error) {
          // console.log(error);
        });

      //Obtener datos de las personas enviadas el acta
      var url = "/acta/obtenerFirma?id=" + id;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayDetalleEnvio = respuesta.firmas;
        })
        .catch(function (error) {
          // console.log(error);
        });
      // //Obtener datos de las personas invitadas al punto Orden dia
      // var url = "/convocatoria/obtenerDetalleOrdenDias?id=" + id;
      // axios
      //   .get(url)
      //   .then(function (response) {
      //     var respuesta = response.data;
      //     me.arrayDetalleInvitado = respuesta.detalleorden_dias;
      //     me.ordenarPersonaInv();
      //   })
      //   .catch(function (error) {
      //     console.log(error);
      //   });
    },
    inicializarOrdenDiaSelectDetalle() {
      let me = this;
      me.arrayCombinarDetalle = [];
      for (var i = 0; i < me.arrayDetalle.length; i++) {
        me.arrayCombinarDetalle.push({
          idordendia: me.arrayDetalle[i]["id"] + "",
          numerador: me.arrayDetalle[i]["numerador"] + "",
          nombre: me.arrayDetalle[i]["nombre"],
          nombrealternativo: "",
          descripcion: "Ninguna",
          resolucion: "Ninguna",
        });
      }
    },
    llenarVistaDescripcionResolucionDetalle() {
      let me = this;
      var cont = 0;
      var cade = "";
      var cade2 = "";
      for (var i = 0; i < me.arrayCombinarDetalle.length; i++) {
        //===Verificar actualizacion descripcion
        var verifivarDes = false;
        for (var j = 0; j < me.arrayDescripcionDetalle.length; j++) {
          if (
            me.arrayCombinarDetalle[i].idordendia ==
            me.arrayDescripcionDetalle[j].idordendia
          ) {
            me.arrayCombinarDetalle[i].descripcion =
              me.arrayDescripcionDetalle[j].descripcion;
            me.arrayCombinarDetalle[i].nombrealternativo =
              me.arrayDescripcionDetalle[j].nombrealternativo;
            verifivarDes = true;
          }
        }
        if (verifivarDes == false) {
          me.arrayCombinarDetalle[i].descripcion = "Ninguna";
        }
        //===Fin
        //===Verificar actualizacion resolucion
        cont = 0;
        cade = "";
        cade2 = "";
        for (var q = 0; q < me.arrayResolucionDetalle.length; q++) {
          if (
            me.arrayCombinarDetalle[i].idordendia ==
            me.arrayResolucionDetalle[q].idordendia
          ) {
            cade2 = me.arrayResolucionDetalle[q].nombrealternativo;
            if (cont > 0) {
              cade += " <br> ";
            }
            cade +=
              "  <b>• " +
              me.arrayResolucionDetalle[q].codigo +
              ":</b> " +
              me.arrayResolucionDetalle[q].descripcion;
            cont++;
          }
        }
        if (cade != "") {
          me.arrayCombinarDetalle[i].resolucion = cade;
          me.arrayCombinarDetalle[i].nombrealternativo = cade2;
        } else {
          me.arrayCombinarDetalle[i].resolucion = "Ninguna";
        }
        //===Fin
      }
    },
    combinarDescripcionResolucionDetalle() {
      let me = this;
      var arrayTemp = [];
      var idT = "";
      var numeradorT = "";
      var nombreT = "";
      var descripcionT = "";
      var resolucionT = "";
      var cont = 0;
      var cont2 = 0;
      var nombreAlT = "";
      //========================================================
      for (var i = 0; i < me.arrayCombinarDetalle.length; i++) {
        cont = 0;
        idT = "";
        numeradorT = "";
        nombreT = "";
        descripcionT = "";
        for (var j = 0; j < me.arrayCombinarDetalle.length; j++) {
          if (
            (me.arrayCombinarDetalle[i].descripcion != "Ninguna" &&
              me.arrayCombinarDetalle[i].descripcion ==
                me.arrayCombinarDetalle[j].descripcion) ||
            (me.arrayCombinarDetalle[i].resolucion != "Ninguna" &&
              me.arrayCombinarDetalle[i].resolucion ==
                me.arrayCombinarDetalle[j].resolucion)
          ) {
            cont++;
            if (cont > 1) {
              idT += ",";
              numeradorT += ",";
              nombreT += ",";
            }
            idT += me.arrayCombinarDetalle[j].idordendia;
            numeradorT += me.arrayCombinarDetalle[j].numerador;
            nombreT += me.arrayCombinarDetalle[j].nombre;
            nombreAlT = me.arrayCombinarDetalle[j].nombrealternativo;
            descripcionT = me.arrayCombinarDetalle[j].descripcion;
            resolucionT = me.arrayCombinarDetalle[j].resolucion;
          }
        }
        if (cont > 1) {
          if (!me.buscarD(idT, arrayTemp)) {
            arrayTemp.push({
              idordendia: idT,
              numerador: numeradorT,
              nombre: nombreT,
              nombrealternativo: nombreAlT,
              descripcion: descripcionT,
              resolucion: resolucionT,
            });
          }
        } else {
          arrayTemp.push({
            idordendia: me.arrayCombinarDetalle[i].idordendia,
            numerador: me.arrayCombinarDetalle[i].numerador,
            nombre: me.arrayCombinarDetalle[i].nombre,
            nombrealternativo: me.arrayCombinarDetalle[i].nombrealternativo,
            descripcion: me.arrayCombinarDetalle[i].descripcion,
            resolucion: me.arrayCombinarDetalle[i].resolucion,
          });
        }
      }

      me.arrayCombinarDetalle = [];
      me.arrayCombinarDetalle = arrayTemp;
      //========================================================
    },
    ocultarDetalleActa() {
      let me = this;
      me.listado = 1;
      me.arrayDetalleActa = [];
      me.arrayDetalle = [];
      me.arrayDetalleEnvio = [];
      me.arrayDescripcionDetalle = [];
      me.arrayResolucionDetalle = [];
      me.arrayCombinarDetalle = [];
      me.aidConvocatoriaActa = 0;

      me.arrayListConvocatoria = [];
      me.arraySelectConvocatoria = [];
      me.arraySelectOrdendia = [];
      me.arrayPersona = [];
      me.arrayPersonaB = [];
      me.titulo = "";
      me.codigo = "";
      me.descripcion = "";
      me.estado = "";
      me.cedulaRedactor = "";
      me.apellidoRedactor = "";
      me.nombreRedactor = "";
      me.emailRedactor = "";
      me.perfilRedactor = "";
    },
    cerrarModalConvocatoria() {
      this.modal = 0;
      this.valModal = false;
      this.tituloModal = "";
      this.buscarA = "";
      this.arrayListConvocatoria = [];
    },
    cerrarModalPersona() {
      this.modal = 0;
      this.valModal = false;
      this.tituloModal = "";
      this.buscarP = "";
      this.arrayPersonaB = [];
    },
    cerrarModalResolucion() {
      this.tituloModal = "";
      this.descripcionResolucion = "";
      this.arrayCheckbox = [];
      this.arrayCheckboxSelect = [];
      this.arrayResolucionVista = [];
    },
    cerrarModalDescripcion() {
      this.tituloModal = "";
      this.descripcionActa = "";
      this.arrayCheckbox = [];
      this.arrayCheckboxSelect = [];
    },
    abrirModal() {
      if (this.codigo == "") {
        this.valModal = false;
        Swal.fire({
          title: "Error...",
          text: "Debe ingresar un código de acta!",
          icon: "error",
          confirmButtonText: "OK",
        });
      } else {
        this.valModal = true;
        this.tituloModal = "";
        this.buscarA = "";
        this.arrayListConvocatoria = [];
        this.tituloModal = "Seleccione una convocatoria";
      }
    },
    abrirModalPersona() {
      this.valModal = true;
      this.arrayPersonaB = [];
      this.tituloModal = "";
      this.buscarP = "";
      this.tituloModal = "Seleccione uno o varios usuarios";
    },
    abrirModalDescripcion(index) {
      if (this.codigo != "") {
        this.valModal = true;
        this.tituloModal = "Ingrese descripción";
        this.descripcionActa = "";
        this.llenarCheckbox2(index);
        this.agregarNombreAlt();
        this.valBoolean = false;
        this.idordendia = index;
      } else {
        this.valModal = false;
        Swal.fire({
          title: "Error...",
          text: "Debe ingresar un código de acta!",
          icon: "error",
          confirmButtonText: "OK",
        });
      }
    },
    abrirModalResolucion(index) {
      if (this.codigo != "") {
        this.valModal = true;
        this.tituloModal = "Ingrese una o varias resoluciones";
        this.descripcionResolucion = "";
        this.llenarCheckbox2(index);
        this.agregarNombreAlt();
        this.arrayResolucionVista = [];
        this.valBoolean = false;
        this.idordendia = index;
      } else {
        this.valModal = false;
        Swal.fire({
          title: "Error...",
          text: "Debe ingresar un código de acta!",
          icon: "error",
          confirmButtonText: "OK",
        });
      }
    },
  },
  mounted() {
    this.listarActa(1, this.buscar, this.criterio);
  },
};
</script>

<style>
.modal-content {
  width: 100% !important;
  position: absolute !important;
}
.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  position: absolute !important;
  background-color: #3c29297a !important;
}
.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red !important;
  font-weight: bold;
}
@media (min-width: 900px) {
  .btnagregar {
    margin-top: 1.7rem;
  }
  .btnagregarR {
    font-size: 20px;
    margin-top: 0.1rem;
    height: 88px;
  }
}
</style>