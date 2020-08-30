<template>
  <div>
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-2"></div>
    <div class="container-fluid mt--7">
      <div class="header-body">
        <a
          class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"
        >Mantenedor de Tipos de Vehículos</a>
      </div>

      <!-- Table -->
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <button
                @click="abrirModal('tipo','registrar')"
                type="button"
                class="btn btn-default float-left"
              >Nuevo Tipo</button>

              <input
                v-on:change="listarTipo(1,buscar)"
                type="text"
                v-model="buscar"
                class="form-control float-right"
                placeholder="Buscar ..."
                style="max-width:300px;"
              />
              
            </div>

            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="tipo in arrayTipo" :key="tipo.id">
                    
                    <td class="text-left text-uppercase"><small v-text="tipo.nombre"></small></td>

                    <td class="text-right">
                      <div class="dropdown">
                        <a
                          class="btn btn-sm btn-icon-only text-light"
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
                            class="dropdown-item"
                            @click.prevent="abrirModal('tipo','actualizar',tipo)"
                          >Modificar</a>
                          <a
                            class="dropdown-item"
                            @click.prevent="eliminarTipo(tipo.id)"
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
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form action method="post" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Tipo</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="nombre"
                    class="form-control"
                    placeholder="Ej : Autos, Camionetas y 4x4"
                  />
                </div>
              </div>

              <div v-show="errorTipo" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjTipo" :key="error" v-text="error"></div>
                </div>
              </div>

            </form>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            <button
              type="button"
              v-if="tipoAccion==1"
              class="btn btn-primary"
              @click="registrarTipo()"
            >Guardar</button>
            <button
              type="button"
              v-if="tipoAccion==2"
              class="btn btn-primary"
              @click="actualizarTipo()"
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
import VueCurrencyFilter from "vue-currency-filter";

Vue.use(VueCurrencyFilter);
export default {
  props: ["ruta"],
  data() {
    return {
      id: 0,
      nombre: "",
      arrayTipo: [],
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorTipo: 0,
      errorMostrarMsjTipo: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      criterio: "nombre",
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

    listarTipo(page, buscar, criterio) {
      let me = this;
      var url =
        "/tipovehiculo?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayTipo = respuesta.tipos.data;
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
      me.listarTipo(page, buscar, criterio);
    },
    registrarTipo() {
      if (this.validarTipo()) {
        return;
      }

      let me = this;

      axios
        .post("/tipovehiculo/registrar", {
          nombre: this.nombre
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarTipo(1, "", "nombre");
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
    actualizarTipo() {
      if (this.validarTipo()) {
        return;
      }

      let me = this;

      axios
        .put("/tipovehiculo/actualizar", {
          nombre: this.nombre,
          id: this.id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarTipo(1, "", "nombre");
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
    eliminarTipo(id) {
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
          axios
            .put("/tipovehiculo/eliminar", {
              id: id
            })
            .then(function(response) {
              me.listarTipo(1, "", "nombre");
              Swal.fire(
                "Borrado!",
                "El registro fue liminado del sistema.",
                "success"
              );
            })
            .catch(function(error) {
              console.log(error);
            });
        }
      });
    },
    validarTipo() {
      this.errorTipo = 0;
      this.errorMostrarMsjTipo = [];

      if (!this.nombre)
        this.errorMostrarMsjTipo.push(
          "El tipo no puede estar vacío."
        );

      if (this.errorMostrarMsjTipo.length) this.errorTipo = 1;

      return this.errorTipo;
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.nombre = "";
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "tipo": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Tipo";
              this.nombre = "";
              this.tipoAccion = 1;
              break;
            }

            case "actualizar": {
              this.modal = 1;
              this.tituloModal = "Actualizar tipo";
              this.tipoAccion = 2;
              this.id = data["id"];
              this.nombre = data["nombre"];
              break;
            }
          }
        }
      }
    }
  },
  mounted() {
    this.listarTipo(1, this.buscar, this.criterio);
  }
};
</script>
