<template>
  <div>
    <div class="header bg-gradient-warning pb-8 pt-5 pt-md-2"></div>
    <div class="container-fluid mt--7">
      <div class="header-body">
        <a
          class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"
        >Mantenedor Vehículos</a>
      </div>

      <!-- Table -->
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <button
                @click="abrirModal('vehiculo','registrar')"
                type="button"
                class="btn btn-default float-left"
              >Nuevo Vehiculo</button>

              <input
                v-on:change="listarVehiculo(1,buscar)"
                type="text"
                v-model="buscar"
                class="form-control float-right"
                placeholder="Buscar Patente..."
                style="max-width:300px;"
              />
              
            </div>

            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Patente</th>
                    <th scope="col">Marca</th>
                    <th scope="col">Modelo</th>
                    <th scope="col">Propietario</th>
                    <th scope="col">Cel. Propietario</th>
                    <th scope="col">Tipo</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="vehiculo in arrayVehiculo" :key="vehiculo.id">
                    
                    <td class="text-left text-uppercase"><small v-text="vehiculo.patente"></small></td>
                    <td class="text-left text-uppercase"><small v-text="vehiculo.marca"></small></td>
                    <td class="text-left text-uppercase"><small v-text="vehiculo.modelo"></small></td>
                    <td class="text-left text-uppercase"><small v-text="vehiculo.nombre_propietario"></small></td>
                    <td class="text-left text-uppercase"><small v-text="vehiculo.fono_propietario"></small></td>
                    <td class="text-left text-uppercase"><small v-text="vehiculo.tipo"></small></td>
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
                            @click.prevent="abrirModal('vehiculo','actualizar',vehiculo)"
                          >Modificar</a>
                          <a
                            class="dropdown-item"
                            @click.prevent="eliminarVehiculo(vehiculo.id)"
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
                <label class="col-md-3 form-control-label" for="text-input">Patente</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="patente"
                    class="form-control"
                    placeholder="Ej : ABCD01"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Marca</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="marca"
                    class="form-control"
                    placeholder="Ej : Toyota"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Modelo</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="modelo"
                    class="form-control"
                    placeholder="Ej : Yaris"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Propietario</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="nombre_propietario"
                    class="form-control"
                    placeholder="Ej : Juan Soto"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Cel. Propietario</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="fono_propietario"
                    class="form-control"
                    placeholder="Ej : 987654321"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Correo Propietario</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="correo_propietario"
                    class="form-control"
                    placeholder="Ej : elpropietario@gmail.com"
                  />
                </div>
              </div>
 
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">N° de Motor</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="motor"
                    class="form-control"
                    placeholder="Ej : elpropietario@gmail.com"
                  />
                </div>
              </div>  

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">N° Vin</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="vin"
                    class="form-control"
                    placeholder="Ej : 0154S4S4CXVX"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Chasis</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="chasis"
                    class="form-control"
                    placeholder="Ej : 0154S4S4CXVX"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Tipo de Vehículo</label>
                <div class="col-md-9">
                  <select v-model="tipo" class="form-control" v-on:change.prevent="">
                              <option value="0">Seleccione una opción</option>
                              <option v-for="option in tipos" v-bind:value="option.id">
                                  {{ option.nombre }}
                              </option>
                    </select>
                </div>
              </div>

              <div v-show="errorVehiculo" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjVehiculo" :key="error" v-text="error"></div>
                </div>
              </div>

            </form>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            <button
              type="button"
              v-if="vehiculoAccion==1"
              class="btn btn-primary"
              @click="registrarVehiculo()"
            >Guardar</button>
            <button
              type="button"
              v-if="vehiculoAccion==2"
              class="btn btn-primary"
              @click="actualizarVehiculo()"
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
  created(){
    axios.get('/todoslostiposdevehiculos').then(response => { this.tipos = response.data; }).catch(errors => { console.log(errors); })

  },
  data() {
    return {
      id: 0,
      patente: "",
      marca:"",
      modelo:"",
      nombre_propietario:"",
      fono_propietario:"",
      correo_propietario:"",
      motor:"",
      vin:"",
      chasis:"",
      tipo:0,
      tipo_vehiculo:"",
      tipos:[],
      arrayVehiculo: [],
      modal: 0,
      tituloModal: "",
      vehiculoAccion: 0,
      errorVehiculo: 0,
      errorMostrarMsjVehiculo: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      criterio: "patente",
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

    listarVehiculo(page, buscar, criterio) {
      let me = this;
      var url =
        "/vehiculo?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayVehiculo = respuesta.vehiculos.data;
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
      me.listarVehiculo(page, buscar, criterio);
    },
    registrarVehiculo() {
      if (this.validarVehiculo()) {
        return;
      }

      let me = this;

      axios
        .post("/vehiculo/registrar", {
          patente: this.patente,
          marca: this.marca,
          modelo: this.modelo,
          nombre_propietario: this.nombre_propietario,
          fono_propietario: this.fono_propietario,
          correo_propietario: this.correo_propietario,
          motor: this.motor,
          vin: this.vin,
          chasis: this.chasis,
          tipo: this.tipo
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarVehiculo(1, "", "patente");
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
    actualizarVehiculo() {
      if (this.validarVehiculo()) {
        return;
      }

      let me = this;

      axios
        .put("/vehiculo/actualizar", {
          patente: this.patente,
          marca: this.marca,
          modelo: this.modelo,
          nombre_propietario: this.nombre_propietario,
          fono_propietario: this.fono_propietario,
          correo_propietario: this.correo_propietario,
          motor: this.motor,
          vin: this.vin,
          chasis: this.chasis,
          tipo: this.tipo,
          id: this.id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarVehiculo(1, "", "patente");
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
    eliminarVehiculo(id) {
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
            .put("/vehiculo/eliminar", {
              id: id
            })
            .then(function(response) {
              me.listarVehiculo(1, "", "patente");
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
    validarVehiculo() {
      this.errorVehiculo = 0;
      this.errorMostrarMsjVehiculo = [];

      if (!this.patente)
        this.errorMostrarMsjVehiculo.push(
          "El vehiculo no puede estar vacío."
        );

      if (this.errorMostrarMsjVehiculo.length) this.errorVehiculo = 1;

      return this.errorVehiculo;
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.patente = "";
    },

    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "vehiculo": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Vehiculo";
              this.patente = "";
              this.marca = "";
              this.motor = "";
              this.modelo = "";
              this.vin = "";
              this.chasis = "";
              this.nombre_propietario = "";
              this.correo_propietario = "";
              this.fono_propietario = "";
              this.tipo = 0;
              this.vehiculoAccion = 1;
              break;
            }
            case "actualizar": {
              this.modal = 1;
              this.tituloModal = "Actualizar vehiculo";
              this.vehiculoAccion = 2;
              this.id = data["id"];
              this.patente = data["patente"];
              this.marca = data["marca"];
              this.modelo = data["modelo"];
              this.nombre_propietario = data["nombre_propietario"];
              this.correo_propietario = data["correo_propietario"];
              this.fono_propietario = data["fono_propietario"];
              this.motor = data["motor"];
              this.vin = data["vin"];
              this.chasis = data["chasis"];
              this.tipo = data["tipo_vehiculo"];
              break;
            }
          }
        }
      }
    }
  },
  mounted() {
    this.listarVehiculo(1, this.buscar, this.criterio);

  }
};
</script>
