<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
      <li class="breadcrumb-item">Convocatoria</li>
      <li class="breadcrumb-item active">Redacción</li>
    </ol>
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i>Convocatorias
          <button
            type="button"
            @click="mostrarDetalleConvocatoria()"
            class="btn btn-secondary"
          >
            <i class="icon-plus"></i>&nbsp;Nueva
          </button>
        </div>
        <!--Listado Convocatoria-->
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
                    @keyup.enter="listarConvocatoria(1, buscar, criterio)"
                    class="form-control"
                    placeholder="Texto a buscar"
                  />
                  <button
                    type="submit"
                    @click="listarConvocatoria(1, buscar, criterio)"
                    class="btn btn-primary"
                    title="Buscar convocatoria"
                  >
                    <i class="fa fa-search"></i>Buscar
                  </button>
                  <button
                    type="submit"
                    @click="listarConvocatoria(1, '', '')"
                    class="btn btn-info"
                    title="Ver todas las convocatorias"
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
                <tbody v-if="arrayConvocatoria.length">
                  <tr
                    v-for="convocatoria in arrayConvocatoria"
                    :key="convocatoria.id"
                  >
                    <td
                      width="100"
                      align="center"
                      style="vertical-align: middle"
                    >
                      <button
                        type="button"
                        @click="verConvocatoria(convocatoria.id)"
                        class="btn btn-success btn-sm"
                        title="Ver convocatoria"
                      >
                        <i class="icon-eye"></i>
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
                    <td
                      width="220"
                      align="justify"
                      style="vertical-align: middle"
                      v-text="convocatoria.titulo"
                    ></td>
                    <td
                      width="200"
                      align="center"
                      style="vertical-align: middle"
                      v-text="convocatoria.codigo"
                    ></td>
                    <td align="justify" v-text="convocatoria.descripcion"></td>
                    <td width="1" align="center" style="vertical-align: middle">
                      <div>
                        <span
                          class="badge badge-success"
                          v-text="convocatoria.estado"
                        ></span>
                      </div>
                    </td>
                  </tr>
                </tbody>
                <tbody v-else>
                  <tr>
                    <td colspan="6">No hay convocatorias agregadas</td>
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
        <!--Fin Listado Convocatoria-->
        <!--Listado Agregar Convocatoria-->
        <template v-else-if="listado == 0">
          <div class="card-body">
            <b>Convocatoria</b>
            <div class="form-group row border">
              <div class="col-md-9">
                <label for=""
                  >Título<span style="color: red" v-show="titulo == ''"
                    >(*Ingrese)</span
                  ></label
                >
                <input
                  type="text"
                  placeholder="Ingrese título de la convocatoria"
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
                  placeholder="Ingrese el código de la convocatoria"
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
                    placeholder="Ingrese la descripción de la convocatoria"
                    v-model="descripcion"
                    rows="3"
                  ></textarea>
                </div>
              </div>
            </div>
            <b>Seleccione usuarios dirigidos</b>
            <div class="form-group row border">
              <div class="col-md-8">
                <div class="form-group">
                  <label
                    >Usuario
                    <span style="color: red" v-show="arrayPersona.length < 1"
                      >(*Ingrese)</span
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
                      @click="abrirModal()"
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
            <b>Orden del día</b>
            <div class="form-group row border">
              <div class="col-md-10">
                <div class="form-group">
                  <label
                    >Nombre
                    <span style="color: red" v-show="arrayOrdenDia.length < 1"
                      >(*Ingrese)</span
                    ></label
                  >
                  <input
                    type="text"
                    @keyup.enter="agregarOrdendia()"
                    placeholder="Ingrese el nombre del punto de la orden del día"
                    class="form-control"
                    v-model="nombre"
                  />
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <button
                    @click="agregarOrdendia()"
                    class="btn btn-success form-control btnagregar"
                    title="Agregar Orden Día"
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
                      <th>Nro</th>
                      <th>Nombre</th>
                      <th>Invitado</th>
                      <th>Adjunto</th>
                    </tr>
                  </thead>
                  <tbody v-if="arrayOrdenDia.length">
                    <tr
                      v-for="(detalle, index) in arrayOrdenDia"
                      :key="detalle.id"
                    >
                      <td
                        width="140"
                        align="center"
                        style="vertical-align: middle"
                      >
                        <button
                          @click="eliminarOrdendia(index)"
                          type="button"
                          class="btn btn-danger btn-sm"
                          title="Eliminar Punto"
                        >
                          <i class="icon-close"></i></button
                        >&nbsp;
                        <button
                          @click="abrirModalInvitado(index)"
                          type="button"
                          class="btn btn-success btn-sm"
                          title="Agregar invitado"
                          data-toggle="modal"
                          data-target="#modalPersona"
                        >
                          <i class="icon-user-follow"></i>
                        </button>
                        <button
                          v-if="detalle.invitado != 'Ninguno'"
                          @click="eliminarInvitado(detalle.nro)"
                          type="button"
                          class="btn btn-danger btn-sm"
                          title="Eliminar invitado"
                        >
                          <i class="icon-user-unfollow"></i>
                        </button>
                      </td>
                      <td
                        align="center"
                        style="vertical-align: middle"
                        v-text="detalle.nro"
                      ></td>
                      <td>
                        <input
                          v-model="detalle.nombre"
                          type="text"
                          value=""
                          class="form-control"
                        />
                      </td>
                      <td
                        style="vertical-align: middle"
                        v-text="detalle.invitado"
                      ></td>
                      <td
                        v-if="detalle.invitado != 'Ninguno'"
                        width="1"
                        align="center"
                        style="vertical-align: middle"
                      >
                        <input
                          type="file"
                          :id="'archivoInput' + detalle.nro"
                          class="form-control-file"
                          accept="application/*"
                        />
                      </td>
                      <td v-else style="vertical-align: middle">Ninguno</td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="5">No hay orden del día agregada</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12">
                <div
                  v-show="errorConvocatoria"
                  class="form-group row div-error"
                >
                  <div class="text-center text-error">
                    <div
                      v-for="error in errorMostrarMsjConvocatoria"
                      :key="error"
                      v-text="error"
                    ></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12">
                <button
                  type="button"
                  @click="ocultarDetalleConvocatoria()"
                  class="btn btn-secondary"
                >
                  Cerrar
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="registrarConvocatoria()"
                >
                  Registrar Convocatoria
                </button>
              </div>
            </div>
          </div>
        </template>
        <!--Fin Listado Agregar Convocatoria-->
        <!--Ver Detalle Convocatoria-->
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
                      <th colspan="5"><b>Orden día</b></th>
                    </tr>
                    <tr>
                      <th><b>Opción</b></th>
                      <th><b>Nro</b></th>
                      <th><b>Nombre</b></th>
                      <th><b>Documento</b></th>
                      <th><b>Estado</b></th>
                    </tr>
                  </thead>
                  <tbody v-if="arrayDetalle.length">
                    <tr
                      align="center"
                      v-for="(detalle, index) in arrayDetalle"
                      :key="detalle.id"
                      v-bind:index="index"
                    >
                      <td
                        width="1"
                        align="center"
                        style="vertical-align: middle"
                      >
                        <template v-if="detalle.estado == 'Activo'">
                          <button
                            type="button"
                            class="btn btn-danger btn-sm"
                            @click="
                              inactivarOrdendia(
                                detalle.id,
                                detalle.idconvocatoria
                              )
                            "
                            title="Inactivar punto de la orde diá"
                          >
                            <i class="icon-close"></i>
                          </button>
                        </template>
                        <template v-else>
                          <button
                            type="button"
                            class="btn btn-success btn-sm"
                            @click="
                              activarOrdendia(
                                detalle.id,
                                detalle.idconvocatoria
                              )
                            "
                            title="Activar punto de la orde diá"
                          >
                            <i class="icon-check"></i>
                          </button>
                        </template>
                      </td>
                      <td
                        style="vertical-align: middle"
                        v-text="detalle.numerador"
                      ></td>
                      <td
                        style="vertical-align: middle"
                        v-text="detalle.nombre"
                      />
                      <td
                        style="vertical-align: middle"
                        v-if="detalle.nomdoc == null"
                      >
                        Ninguno
                      </td>
                      <td
                        v-else
                        width="1"
                        align="center"
                        style="vertical-align: middle"
                      >
                        <a
                          class="nav-link nav-dropdown-toggle"
                          :href="'storage/' + detalle.nomdoc"
                          target="_blank"
                          ><i class="icon-doc" v-text="detalle.nomdoc"></i
                        ></a>
                      </td>
                      <td
                        width="1"
                        align="center"
                        style="vertical-align: middle"
                      >
                        <div v-if="detalle.estado == 'Activo'">
                          <span
                            class="badge badge-success"
                            v-text="detalle.estado"
                          ></span>
                        </div>
                        <div v-else>
                          <span
                            class="badge badge-danger"
                            v-text="detalle.estado"
                          ></span>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="5">No hay orden del dia agregados</td>
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
                    <tr
                      align="center"
                      v-for="detalleInv in arrayDetalleInvitado"
                      :key="detalleInv.numeroIdentificacion"
                    >
                      <td v-text="detalleInv.numerador" />
                      <td v-text="detalleInv.numeroIdentificacion" />
                      <td v-text="detalleInv.apellidos" />
                      <td v-text="detalleInv.nombres" />
                      <td v-text="detalleInv.EMail" />
                      <td v-text="detalleInv.perfil" />
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="6">No hay usuarios enviados</td>
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
                  @click="ocultarDetalleConvocatoria()"
                  class="btn btn-secondary"
                >
                  Cerrar
                </button>
              </div>
            </div>
          </div>
        </template>
        <!--Fin Ver Detalle Convocatoria-->
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar-->
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
              @click="cerrarModal()"
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
                    <option value="apellidos">Apellido</option>
                    <option value="nombres">Nombre</option>
                    <option value="EMail">Correo</option>
                    <option value="perfil">Perfil</option>
                  </select>
                  <input
                    type="text"
                    v-model="buscarA"
                    @keyup.enter="listarPersona(buscarA, criterioA)"
                    class="form-control"
                    placeholder="Texto a buscar"
                  />
                  <button
                    type="submit"
                    @click="listarPersona(buscarA, criterioA)"
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
                    <td width="1" align="center" style="vertical-align: middle">
                      <button
                        type="button"
                        v-if="tipoAccionInv == 0"
                        @click="agregarPersonaModal(persona)"
                        class="btn btn-success btn-sm"
                      >
                        <i class="icon-check"></i>
                      </button>
                      <button
                        type="button"
                        v-if="tipoAccionInv == 1"
                        @click="agregarInvitado(persona)"
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
              @click="cerrarModal()"
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
    <!--Fin del modal-->
    <!--Inicio del modal Activar o Inactivar-->
    <div
      class="modal fade"
      id="modalActivar"
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
              @click="cerrarModal()"
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
                    <option value="apellidos">Apellido</option>
                    <option value="nombres">Nombre</option>
                    <option value="EMail">Correo</option>
                    <option value="perfil">Perfil</option>
                  </select>
                  <input
                    type="text"
                    v-model="buscarA"
                    @keyup.enter="listarPersona(buscarA, criterioA)"
                    class="form-control"
                    placeholder="Texto a buscar"
                  />
                  <button
                    type="submit"
                    @click="listarPersona(buscarA, criterioA)"
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
                    <td width="1" align="center" style="vertical-align: middle">
                      <button
                        type="button"
                        v-if="tipoAccionInv == 0"
                        @click="agregarPersonaModal(persona)"
                        class="btn btn-success btn-sm"
                      >
                        <i class="icon-check"></i>
                      </button>
                      <button
                        type="button"
                        v-if="tipoAccionInv == 1"
                        @click="agregarInvitado(persona)"
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
              @click="cerrarModal()"
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
    <!--Fin del modal-->
  </main>
</template>

<script>
export default {
  data() {
    return {
      //Inicio
      arrayConvocatoria: [],
      arrayDetalle: [],
      arrayDetalleEnvio: [],
      arrayDetalleInvitado: [],
      arrayOrdenDia: [],
      arrayUser: [],
      arrayPersona: [],
      arrayPersonaB: [],
      arrayPersonaInv: [],
      codigo: "",
      titulo: "",
      descripcion: "",
      estado: "",
      cedulaRedactor: "",
      apellidoRedactor: "",
      nombreRedactor: "",
      emailRedactor: "",
      perfilRedactor: "",
      nombre: "",
      docUser: "",
      personaUser: "",
      emailPersona: "",
      perfilPersona: "",
      errorConvocatoria: 0,
      errorMostrarMsjConvocatoria: [],
      tipoAccionInv: 0,
      idordendia: 0,
      idVal: "",
      arrayArchivo: [],
      //Fin

      listado: 1,
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
      criterioA: "apellidos",
      buscarA: "",
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
  },
  methods: {
    listarConvocatoria(page, buscar, criterio) {
      let me = this;
      var url =
        "/convocatoria?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayConvocatoria = respuesta.convocatorias.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    buscarPersona() {
      let me = this;
      var url = "convocatoria/buscarPersona?filtro=" + me.docUser;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          console.log(me.arrayUser);
          me.arrayUser = respuesta.personas;
          console.log(me.arrayUser);

          if (me.arrayUser.length > 0) {
            me.docUser = me.arrayUser[0]["numeroIdentificacion"];
            me.personaUser =
              me.arrayUser[0]["apellidos"] + " " + me.arrayUser[0]["nombres"];
            me.perfilPersona = me.arrayUser[0]["perfil"];
            me.emailPersona = me.arrayUser[0]["EMail"];
          } else {
            me.personaUser = "No existe persona";
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      me.pagination.current_page = page;
      me.listarConvocatoria(page, buscar, criterio);
    },
    encuentra(nombre) {
      var sw = 0;
      for (var i = 0; i < this.arrayOrdenDia.length; i++) {
        if (this.arrayOrdenDia[i].nombre == nombre) {
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
    encuentraPersonaInv(cedula) {
      var sw = 0;
      for (var i = 0; i < this.arrayPersonaInv.length; i++) {
        if (
          this.arrayPersonaInv[i].cedula == cedula &&
          this.arrayPersonaInv[i].idordendia == this.idordendia
        ) {
          sw = true;
        }
      }
      return sw;
    },
    eliminarOrdendia(index) {
      let me = this;
      me.eliminarInvitado(me.arrayOrdenDia[index].nro);
      me.arrayOrdenDia.splice(index, 1);
      for (var i = 0; i < me.arrayOrdenDia.length; i++) {
        me.arrayOrdenDia[i].nro = i + 1;
      }
    },
    eliminarRI(num, id) {
      let me = this;
      if (num == 1) {
        for (var i = 0; i < me.arrayPersonaInv.length; i++) {
          if (me.arrayPersonaInv[i].idordendia == id) {
            me.arrayPersonaInv.splice(i, 1);
            break;
          }
        }
      } else {
        num--;
        for (var i = 0; i < me.arrayPersonaInv.length; i++) {
          if (me.arrayPersonaInv[i].idordendia == id) {
            me.arrayPersonaInv.splice(i, 1);
            me.eliminarRI(num, id);
          }
        }
      }
    },
    eliminarInvitado(id) {
      let me = this;
      var num = 0;
      for (var i = 0; i < me.arrayOrdenDia.length; i++) {
        if (me.arrayOrdenDia[i].nro == id) {
          me.arrayOrdenDia[i].invitado = "Ninguno";
        }
      }
      for (var i = 0; i < me.arrayPersonaInv.length; i++) {
        if (me.arrayPersonaInv[i].idordendia == id) {
          num++;
        }
      }
      me.eliminarRI(num, id);
    },
    eliminarPersona(index) {
      let me = this;
      me.arrayPersona.splice(index, 1);
    },
    guardarArchivos() {
      let me = this;
      me.arrayArchivo = [];
      me.arrayOrdenDia.forEach((od) => {
        var cadena = "archivoInput" + od.nro;
        var archivoExt = document.getElementById(cadena);
        try {
          var archivo = archivoExt.files[0];
          if (archivo != undefined) {
            me.arrayArchivo.push({
              file: archivo,
            });
            od.nomdoc = archivo.name;
          } else {
            od.nomdoc = "";
          }
        } catch (error) {}
      });
      if (me.arrayArchivo.length) {
        const formDataDoc = new FormData();
        var cont = 0;
        me.arrayArchivo.forEach((element) => {
          formDataDoc.append(cont, element.file);
          cont += 1;
        });
        axios
          .post("/convocatoria/storeArchivo", formDataDoc)
          .then(function (response) {})
          .catch(function (error) {
            console.log(error);
          });
      }
    },
    agregarOrdendia() {
      let me = this;
      if (me.nombre == "") {
        Swal.fire({
          title: "Error...",
          text: "Ingrese algun nombre del punto de la orden del día!",
          icon: "error",
          confirmButtonText: "OK",
        });
      } else {
        if (me.encuentra(me.nombre)) {
          Swal.fire({
            title: "Error...",
            text: "Ese nombre ya se encuentra agregado!",
            icon: "error",
            confirmButtonText: "OK",
          });
        } else {
          me.arrayOrdenDia.push({
            nro: me.arrayOrdenDia.length + 1,
            nombre: me.nombre,
            invitado: "Ninguno",
            nomdoc: "",
          });
          me.nombre = "";
        }
      }
    },
    agregarInvitado(data = []) {
      let me = this;
      if (me.encuentraPersonaInv(data["numeroIdentificacion"])) {
        Swal.fire({
          title: "Error...",
          text:
            "La perona ya se encuentra invitada a este punto de la orden del dia!",
          icon: "error",
          confirmButtonText: "OK",
        });
      } else {
        me.arrayPersonaInv.push({
          idordendia: me.idordendia,
          cedula: data["numeroIdentificacion"],
          nombre: data["apellidos"] + " " + data["nombres"],
          EMail: data["EMail"],
          perfil: data["perfil"],
        });
        var invitados = "";
        for (var i = 0; i < me.arrayOrdenDia.length; i++) {
          if (me.arrayOrdenDia[i].nro == me.idordendia) {
            if (me.arrayOrdenDia[i].invitado != "Ninguno") {
              invitados += ", " + data["apellidos"] + " " + data["nombres"];
              me.arrayOrdenDia[i].invitado += invitados;
            } else {
              invitados = data["apellidos"] + " " + data["nombres"];
              me.arrayOrdenDia[i].invitado = invitados;
            }
            break;
          }
        }
      }
    },
    agregarPersona() {
      let me = this;
      if (me.personaUser == "" || me.personaUser == "No existe persona") {
        Swal.fire({
          title: "Error...",
          text: "Ingrese la cédula de una persona válida!",
          icon: "error",
          confirmButtonText: "OK",
        });
      } else {
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
          console.log(error);
        });
    },
    registrarConvocatoria() {
      if (this.validarConvocatoria()) {
        return;
      }
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
      });
      swalWithBootstrapButtons
        .fire({
          title: "¿Está seguro(a) de guardar la convocatoria?",
          text: "No se podra revertir esto!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Si, Guardar",
          cancelButtonText: "No, Cancelar",
          reverseButtons: false,
        })
        .then((result) => {
          if (result.isConfirmed) {
            //Guardar Convocatoria
            let me = this;
            me.guardarArchivos();

            axios
              .post("/convocatoria/registrar", {
                titulo: this.titulo,
                codigo: this.codigo,
                descripcion: this.descripcion,
                data_ordendia: this.arrayOrdenDia,
                data_persona: this.arrayPersona,
                data_persona_invitada: this.arrayPersonaInv,
              })
              .then(function (response) {
                me.EnvioCorreo();
                me.listado = 1;
                me.listarConvocatoria(1, "", "codigo");
                me.titulo = "";
                me.codigo = "";
                me.descripcion = "";
                me.arrayConvocatoria = [];
                me.arrayOrdenDia = [];
                me.arrayPersona = [];
                me.arrayPersonaInv = [];
                swalWithBootstrapButtons.fire(
                  "Guardada!",
                  "La convocatoria ha sido guardada.",
                  "success"
                );
              })
              .catch(function (error) {
                // smsError=error;
                // console.log(error);
                swalWithBootstrapButtons.fire(
                  "Ocurrió un conflicto",
                  "El código de la convocatoria ya existe.",
                  "error"
                );
              });
            //
          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {
            swalWithBootstrapButtons.fire(
              "Cancelada",
              "La convocatoria todavía no fue guardada.",
              "error"
            );
          }
        });
    },
    EnvioCorreo() {
      try {
        axios
          .post("/convocatoria/storeEmail", {
            titulo: this.titulo,
            codigo: this.codigo,
            descripcion: this.descripcion,
            data_persona: this.arrayPersona,
          })
          .then(function (response) {})
          .catch(function (error) {
            console.log(error);
            Swal.fire({
              icon: "error",
              title: "Ocurrió un conflicto",
              text: "Un usuario OCS no cuenta con un correo existente.",
            });
          });
        if (this.arrayPersonaInv.length > 0) {
          axios
            .post("/convocatoria/storeEmailInv", {
              titulo: this.titulo,
              codigo: this.codigo,
              descripcion: this.descripcion,
              data_persona_invitada: this.arrayPersonaInv,
            })
            .then(function (response) {})
            .catch(function (error) {
              Swal.fire({
                icon: "error",
                title: "Ocurrió un conflicto",
                text: "Un usuario OCS no cuenta con un correo existente.",
              });
            });
        }
      } catch (error) {}
    },
    validarConvocatoria() {
      this.errorConvocatoria = 0;
      this.errorMostrarMsjConvocatoria = [];

      if (!this.titulo)
        this.errorMostrarMsjConvocatoria.push(
          "Ingrese el título de la convocatoria"
        );
      if (!this.codigo)
        this.errorMostrarMsjConvocatoria.push(
          "Ingrese el codigo de la convocatoria"
        );
      if (!this.descripcion)
        this.errorMostrarMsjConvocatoria.push(
          "Ingrese la descripcion de la convocatoria"
        );
      if (this.arrayPersona.length <= 0)
        this.errorMostrarMsjConvocatoria.push("Ingrese usuarios dirigidos");
      if (this.arrayOrdenDia.length <= 0)
        this.errorMostrarMsjConvocatoria.push("Ingrese el Orden del dia");
      if (this.errorMostrarMsjConvocatoria.length) this.errorConvocatoria = 1;
      return this.errorConvocatoria;
    },
    mostrarDetalleConvocatoria() {
      let me = this;
      this.listado = 0;
      me.titulo = "";
      me.codigo = "";
      me.descripcion = "";
      me.arrayOrdenDia = [];
      me.arrayPersona = [];
      me.arrayDetalle = [];
      me.arrayDetalleEnvio = [];
      me.arrayDetalleInvitado = [];
    },
    ocultarDetalleConvocatoria() {
      this.listado = 1;
      this.arrayDetalle = [];
      this.arrayDetalleEnvio = [];
      this.arrayDetalleInvitado = [];
      this.arrayOrdenDia = [];
      this.arrayPersonaInv = [];
      this.errorMostrarMsjConvocatoria = [];
      this.titulo = "";
      this.codigo = "";
      this.descripcion = "";
      this.estado = "";
      this.cedulaRedactor = "";
      this.apellidoRedactor = "";
      this.nombreRedactor = "";
      this.emailRedactor = "";
      this.perfilRedactor = "";
      this.errorConvocatoria = 0;
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
    verConvocatoria(id) {
      this.arrayDetalle = [];
      this.arrayDetalleEnvio = [];
      this.arrayDetalleInvitado = [];
      let me = this;
      this.listado = 2;

      //Obtener datos de la convocatoria
      var arrayConvocatoriaT = [];
      var url = "/convocatoria/obtenerCabecera?id=" + id;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          arrayConvocatoriaT = respuesta.convocatoria;
          me.titulo = arrayConvocatoriaT[0]["titulo"];
          me.codigo = arrayConvocatoriaT[0]["codigo"];
          me.descripcion = arrayConvocatoriaT[0]["descripcion"];
          me.estado = arrayConvocatoriaT[0]["estado"];
          //Ver redactor
          me.cedulaRedactor = arrayConvocatoriaT[0]["iduser"];
          me.apellidoRedactor = arrayConvocatoriaT[0]["apellidos"];
          me.nombreRedactor = arrayConvocatoriaT[0]["nombres"];
          me.emailRedactor = arrayConvocatoriaT[0]["EMail"];
          me.perfilRedactor = arrayConvocatoriaT[0]["perfil"];
        })
        .catch(function (error) {
          console.log(error);
        });
      //Obtener datos de la Onden dia
      var url = "/convocatoria/obtenerOrdenDias?id=" + id;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayDetalle = respuesta.orden_dias;
        })
        .catch(function (error) {
          console.log(error);
        });
      //Obtener datos de las personas enviadas la convocatoria
      var url = "/convocatoria/obtenerDetalles?id=" + id;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayDetalleEnvio = respuesta.detalles;
        })
        .catch(function (error) {
          console.log(error);
        });
      //Obtener datos de las personas invitadas al punto Orden dia
      var url = "/convocatoria/obtenerDetalleOrdenDias?id=" + id;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayDetalleInvitado = respuesta.detalleorden_dias;
          me.ordenarPersonaInv();
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    cerrarModal() {
      this.tituloModal = "";
      this.buscarA = "";
      this.arrayUser = [];
      this.arrayPersonaB = [];
      this.tipoAccionInv = 0;
      this.idordendia = 0;
    },
    abrirModal() {
      this.arrayPersonaB = [];
      this.arrayUser = [];
      this.modal = 1;
      this.tituloModal = "Seleccione uno o varios usuarios";
    },
    abrirModalInvitado(id) {
      this.idordendia = id + 1;
      this.arrayUser = [];
      this.tituloModal = "Seleccione uno o varios usuarios";
      this.tipoAccionInv = 1;
    },
    inactivarOrdendia(id, idconvocatoria) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "Esta seguro de inactivar este punto de la orden día?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Aceptar!",
          cancelButtonText: "Cancelar!",
          reverseButtons: true,
        })
        .then((result) => {
          if (result.isConfirmed) {
            let me = this;
            axios
              .put("/convocatoria/inactivar", {
                id: id,
              })
              .then(function (response) {
                me.envioCorreoConf(id, idconvocatoria);
                //Obtener datos de la Onden dia
                var url = "/convocatoria/obtenerOrdenDias?id=" + idconvocatoria;
                axios
                  .get(url)
                  .then(function (response) {
                    me.arrayDetalle = [];
                    var respuesta = response.data;
                    me.arrayDetalle = respuesta.orden_dias;
                  })
                  .catch(function (error) {
                    console.log(error);
                  });
                swalWithBootstrapButtons.fire(
                  "Desactivado!",
                  "El punto de la orden diá ha sido inactivado con éxito.",
                  "success"
                );
              })
              .catch(function (error) {
                console.log(error);
              });
          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {
          }
        });
    },
    activarOrdendia(id, idconvocatoria) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "Esta seguro de activar este punto de la orden día?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Aceptar!",
          cancelButtonText: "Cancelar!",
          reverseButtons: true,
        })
        .then((result) => {
          if (result.isConfirmed) {
            let me = this;
            axios
              .put("/convocatoria/activar", {
                id: id,
              })
              .then(function (response) {
                //Obtener datos de la Onden dia
                me.envioCorreoConf(id, idconvocatoria);
                var url = "/convocatoria/obtenerOrdenDias?id=" + idconvocatoria;
                axios
                  .get(url)
                  .then(function (response) {
                    me.arrayDetalle = [];
                    var respuesta = response.data;
                    me.arrayDetalle = respuesta.orden_dias;
                  })
                  .catch(function (error) {
                    console.log(error);
                  });
                swalWithBootstrapButtons.fire(
                  "Activado!",
                  "El punto de la orden diá ha sido activado con éxito.",
                  "success"
                );
              })
              .catch(function (error) {
                console.log(error);
              });
          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {
          }
        });
    },
    envioCorreoConf(id, idconvocatoria) {
      axios
        .post("/convocatoria/storeEmailConf", {
          titulo: this.titulo,
          codigo: this.codigo,
          descripcion: this.descripcion,
          id: id,
          idConvocatoria: idconvocatoria,
          data_persona_invitada: this.arrayDetalleInvitado,
        })
        .then(function (response) {})
        .catch(function (error) {
          Swal.fire({
            icon: "error",
            title: "Ocurrió un conflicto",
            text: "El usuario no cuenta con un correo existente.",
          });
        });
    },
  },
  mounted() {
    this.listarConvocatoria(1, this.buscar, this.criterio);
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
}
</style>