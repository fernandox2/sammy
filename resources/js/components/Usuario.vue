<template>
  <div>
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-2"></div>
    <div class="container-fluid mt--7">
      <div class="header-body">
        <a
          class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"
        >Usuarios del Sistema</a>
      </div>

      <!-- Table -->
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
                        <div class="row">
          <div class="col">
              <div class="alert alert-default alert-dismissible fade show" role="alert">
                <span class="alert-icon"><i class="fas fa-users"></i></span>
                <span class="alert-text text-uppercase"><strong>Usuarios</strong></span>
              </div>
          </div>
        </div>
              <button
                @click="abrirModal('usuario','registrar')"
                type="button"
                class="btn btn-success float-left"
              >Nuevo Usuario</button>

              <input
                v-on:change="listarUsuario(1,buscar)"
                type="text"
                v-model="buscar"
                class="form-control float-right"
                placeholder="Buscar por nombre o correo ..."
                style="max-width:50%;"
              />
              
            </div>

            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Correo Electrónico</th>
                    <th scope="col">Rol</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="usuario in arrayUsuario" :key="usuario.id">

                    <td class="text-left text-uppercase"><small v-text="usuario.name"></small></td>
                    <td class="text-left text-uppercase"><small v-text="usuario.email"></small></td>
                    <td class="text-left text-uppercase"><small v-text="usuario.rol"></small></td>
                    <td class="text-right">
                      <div class="dropdown">
                        <a
                          class="btn btn-sm btn-icon-only"
                          href="#"
                          role="button"
                          data-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                          <a
                            style="cursor:pointer;"
                            class="dropdown-item"
                            @click.prevent="abrirModal('usuario','actualizar',usuario)"
                          >Modificar</a>
                          <a
                          style="cursor:pointer;"
                            class="dropdown-item"
                            @click.prevent="eliminarUsuario(usuario.id)"
                          >Eliminar</a>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="card-footer py-4">
              <nav aria-label="...">
                <ul class="pagination justify-content-end mb-0">
                  <li class="page-item" v-if="pagination.current_page > 1">
                    <a
                      class="page-link"
                      tabindex="-1"
                      @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)"
                    >
                      <i class="fas fa-angle-left"></i>
                      <span class="sr-only">Atrás</span>
                    </a>
                  </li>
                  <li
                    class="page-item active"
                    v-for="page in pagesNumber"
                    :key="page"
                    :class="[page == isActived ? 'active' : '']"
                  >
                    <a
                      class="page-link"
                      @click.prevent="cambiarPagina(page,buscar,criterio)"
                      v-text="page"
                    ></a>
                  </li>
                  <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a
                      class="page-link"
                      @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)"
                    >
                      <i class="fas fa-angle-right"></i>
                      <span class="sr-only">Siguiente</span>
                    </a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--Inicio del modal agregar/actualizar-->
    <div
      class="modal fade"
      tabindex="-1"
      :class="{'mostrar' : modal}"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none; overflow-y: scroll;"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title text-primary" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form action method="post" enctype="multipart/form-data" class="form-horizontal">

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Nombre del Usuario :</label>
                    <div class="col-md-9">
                        <input type="text" v-model="name" class="form-control" placeholder="Ej : Javier Neira Soto"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Correo Electronico :</label>
                    <div class="col-md-9">
                        <input type="email" v-model="email" class="form-control" placeholder="Ej : correo_del_usuario@gmail.com"/>
                    </div>
                </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Rol :</label>
                <div class="col-md-9">
                  <select v-model="rol" class="form-control" v-on:change.prevent="">
                              <option value="Administrador">Administrador</option>
                              <option value="Trabajador">Trabajador</option>
                    </select>
                </div>
              </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Contraseña :</label>
                    <div class="col-md-9">
                        <input type="password" maxlength="10" v-model="psw1" class="form-control" placeholder="Ingrese contraseña"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Confirmar Contraseña :</label>
                    <div class="col-md-9">
                        <input type="password" maxlength="10" v-model="psw2" class="form-control" placeholder="Ingrese nuevamente contraseña"/>
                    </div>
                </div>


              <div v-show="errorUsuario" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjUsuario" :key="error" v-text="error"></div>
                </div>
              </div>
            </form>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            <button
              type="button"
              v-if="usuarioAccion==1"
              class="btn btn-primary"
              @click="registrarUsuario()"
            >Guardar</button>
            <button
              type="button"
              v-if="usuarioAccion==2"
              class="btn btn-primary"
              @click="actualizarUsuario()"
            >Actualizar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
  </div>
</template>

<script>

import Swal from "sweetalert2";


export default {
  props: ["ruta"],
  created(){
    axios.get('/todoslosvehiculos').then(response => { this.vehiculos = response.data; }).catch(errors => { console.log(errors); })
  
  },
  data() {
    return {
      id: 0,
      name: "",
      email:"",
      rol:"Trabajador",
      psw1:"",
      psw2:"",
      usuarios:[],
      arrayUsuario:[],
      modal: 0,
      tituloModal: "",
      usuarioAccion: 0,
      errorUsuario: 0,
      errorMostrarMsjUsuario: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      criterio: "name",
      buscar: ""
    };
  },
  computed: {
    isActived: function() {
      return this.pagination.current_page;
    },
    //Calcula los elementos de la paginación
    pagesNumber: function() {
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
    }
  },
  methods: {

    listarUsuario(page, buscar, criterio) {
      let me = this;
      var url =
        "/usuario?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayUsuario = respuesta.usuarios.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      //Actualiza la página actual
      me.pagination.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarUsuario(page, buscar, criterio);
    },

    registrarUsuario() {
      if (this.validarUsuario()) {
        return;
      }

      let me = this;

      axios
        .post("/usuario/registrar", {
          name: me.name,
          email:me.email,
          rol: me.rol,
          psw1: me.psw1,
          psw2: me.psw2
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarUsuario(1, "", "name");
          Swal.fire({
            icon: "success",
            title: "Excelente ...",
            text: "Operacion Completada !"
          });
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    actualizarUsuario() {
      if (this.validarUsuario()) {
        return;
      }

      let me = this;

      axios
        .put("/usuario/actualizar", {
          name: me.name,
          email:me.email,
          rol: me.rol,
          psw1: me.psw1,
          psw2: me.psw2,
          id: me.id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarUsuario(1, "", "id");
          Swal.fire({
            icon: "success",
            title: "Excelente ...",
            text: "Operación Completada!"
          });
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    eliminarUsuario(id) {
      let me = this;

      Swal.fire({
        title: "¿Está Seguro?",
        text: "Esta acción no es reversible",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, Bórralo"
      }).then(result => {
        if (result.value) {
          axios.put("/usuario/eliminar", {
              id: id
            })
            .then(function(response) {
              me.listarUsuario(1, "", "id");
              Swal.fire(
                "Borrado!",
                "El registro fue eliminado del sistema.",
                "success"
              );
            })
            .catch(function(error) {
              console.log(error);
            });
        }
      });
    },

    validarUsuario() {
      this.errorUsuario = 0;
      this.errorMostrarMsjUsuario = [];

      if (this.name=="" )
        this.errorMostrarMsjUsuario.push(
          "Debe ingresar un nombre al usuario."
        );

      if (this.email == "")
        this.errorMostrarMsjUsuario.push(
          "Debe agregar un email válido."
        );

        if (this.psw1 != this.psw2)
        this.errorMostrarMsjUsuario.push(
          "Las contraseñas no coinciden"
        );


      if (this.errorMostrarMsjUsuario.length) this.errorUsuario = 1;

      return this.errorUsuario;
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.name = "";
      this.email = "";
      this.psw1 = "";
      this.psw2 = "";
      this.rol = "Trabajador";
    },

    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "usuario": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Nuevo Usuario";
              this.name = "";
              this.email = "";
              this.psw1 = "";
              this.psw2 = "";
              this.rol = "Trabajador";
              this.usuarioAccion = 1;
              break;
            }
            case "actualizar": {
              this.modal = 1;
              this.tituloModal = "Actualizar Usuario N° " + data["id"];
              this.usuarioAccion = 2;
              this.id = data["id"];
              this.name = data["name"];
              this.email = data["email"];
              this.psw1 = data["password"];
              this.psw2 = data["password"];
              this.rol = data["rol"];
              break;
            }
          }
        }
      }
    }
  },
  mounted() {
    this.listarUsuario(1, this.buscar, this.criterio);

  }
};
</script>
