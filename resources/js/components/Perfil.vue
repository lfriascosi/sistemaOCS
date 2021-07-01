<template>
  <main class="main">
    <div align="center" style="background-color: white">
      <br /><br /><br /><br /><br /><br />
      <div class="row justify-content-center">
        <div class="col-md-10">
          <div class="card-group mb-0">
            <div class="card p-4">
              <div class="card-body">
                <h2>Datos de usuario</h2>
                <br /><br /><br />
                <div class="col-md-12" align="left">
                  <FONT SIZE="4">
                    <div>
                      <span><i class="icon-user-following"></i></span>
                      <label v-text="apellido_user"></label>
                    </div>
                    <div>
                      <span><i class="icon-user-following"></i></span>
                      <label v-text="nombre_user"></label>
                    </div>
                    <div>
                      <span><i class="icon-credit-card"></i></span>
                      <label v-text="cedula_user"></label>
                    </div>
                    <div>
                      <span><i class="icon-envelope"></i></span>
                      <label v-text="email_user"></label>
                    </div>
                    <div>
                      <span><i class="icon-graduation"></i></span>
                      <label v-text="perfil_user"></label>
                    </div>
                  </FONT>
                </div>
              </div>
            </div>
            <div
              class="card text-white bg-dark py-5 d-md-down-none"
              style="width: 44%"
            >
              <div class="card-body text-center">
                <div>
                  <h2>Sistema OCS</h2>
                  <br />
                  <img src="img/logo.png" class="img-logo-s" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br /><br /><br /><br /><br /><br />
    </div>
  </main>
</template>

<script>
export default {
  data() {
    return {
      apellido_user: "",
      nombre_user: "",
      cedula_user: "",
      email_user: "",
      perfil_user: "",
    };
  },
  methods: {
    listarDatos() {
      let me = this;
      var arrayUser = [];
      var url = "/datospersona";
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          arrayUser = respuesta.personas;

          me.apellido_user = arrayUser[0]["apellidos"];
          me.nombre_user = arrayUser[0]["nombres"];
          me.cedula_user = arrayUser[0]["numeroIdentificacion"];
          me.perfil_user = arrayUser[0]["perfil"];
          me.email_user = arrayUser[0]["EMail"];
        })
        .catch(function (error) {
          console.log(error);
        });
    },
  },
  mounted() {
    this.listarDatos();
  },
};
</script>
