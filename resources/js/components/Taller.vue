<template>
  <div>
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-2"></div>
    <div class="container-fluid mt--7">
      <div class="header-body">
        <a
          class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"
        >Taller Mecánico</a>
      </div>

      <!-- Table -->
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
                        <div class="row">
          <div class="col">
              <div class="alert alert-default alert-dismissible fade show" role="alert">
                <span class="alert-icon"><i class="ni ni-settings"></i></span>
                <span class="alert-text text-uppercase"><strong>Taller Mecánico</strong></span>
              </div>
          </div>
        </div>
              <button
                @click.prevent="abrirModal('taller','registrar')"
                type="button"
                class="btn btn-success float-left"
              >Nuevo Ingreso</button>

              <input
                v-on:change="listarTaller(1,buscar)"
                type="text"
                v-model="buscar"
                class="form-control float-right"
                placeholder="Buscar por Patente o Propietario"
                style="max-width:50%;"
              />
              
            </div>

            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">N°</th>
                    <th scope="col">Patente</th>
                    <th scope="col">Comentario</th>
                    <th scope="col">Propietario</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="taller in arrayTaller" :key="taller.id">

                    <td class="text-left text-uppercase"><small v-text="taller.id"></small></td>
                    <td class="text-left text-uppercase"><small v-text="taller.patente"></small></td>
                    <td class="text-left text-uppercase"><small v-text="taller.comentario"></small></td>
                    <td class="text-left text-uppercase"><small v-text="taller.propietario"></small></td>
                    <td class="text-left text-uppercase"><small v-text="taller.estado"></small></td>
                    <td class="text-left text-uppercase"><small v-text="taller.created_at"></small></td>
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
                            @click.prevent="CrearPDF(taller)"
                          >Generar PDF</a>
                          <a
                            style="cursor:pointer;"
                            class="dropdown-item"
                            @click.prevent="abrirModal('taller','actualizar',taller)"
                          >Modificar</a>
                          <a
                          style="cursor:pointer;"
                            class="dropdown-item"
                            @click.prevent="eliminarTaller(taller.id)"
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
            <button type="button" class="close" @click.prevent="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form action method="post" enctype="multipart/form-data" class="form-horizontal">

            <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Vehículo : </label>
                <div class="col-md-6">
                  <select v-model="vehiculo" class="form-control" v-on:change.prevent="">
                              <option value="0">Seleccione una patente</option>
                              <option v-for="option in vehiculos" v-bind:value="option.id">
                                  {{ option.patente }}
                              </option>
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="button" class="btn btn-default" @click.prevent="abrirModalNuevoVehiculo()">Nuevo Vehiculo</button>
                </div>

              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Comentario :</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="comentario"
                    class="form-control"
                    placeholder="Ej : Entregar antes del 15/10/2020"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Estado</label>
                <div class="col-md-9">
                  <select v-model="estado" class="form-control" v-on:change.prevent="">
                              <option value="En curso">En curso</option>
                              <option value="Terminada">Terminada</option>
                    </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Valor Neto:</label>
                <div class="col-md-9">
                  <h5 class="text-left text-uppercase">${{ valor_neto | currency}}</h5>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Valor Total:</label>
                <div class="col-md-9">
                  <h5 class="text-left text-uppercase">${{ valor_total | currency}}</h5>
                </div>
              </div>

              <h4 class="text-primary">Servicios o Insumos</h4>
              
              <br>
              
              <div class="form-group row">
                             

                <div class="col-md-3">
                  <select v-model="tipo" class="form-control" v-on:change.prevent="">
                              <option value="Mano de Obra Mecánica">Mano de Obra Mecánica</option>
                              <option value="Mano de Obra Eléctrica">Mano de Obra Eléctrica</option>
                              <option value="Ventas o Servicios">Ventas o Servicios</option>
                              <option value="Repuestos o Insumos">Repuestos o Insumos</option>
                    </select>
                </div>


                <div class="col-md-5">
                    <input
                    type="text"
                    maxlength="500"
                    v-model="detalle"
                    class="form-control"
                    placeholder="Pastillas de freno"
                  />
                </div>
                
                <div class="col-md-2">
                    <input
                    type="text"
                    v-model="detalle_neto"
                    class="form-control"
                    placeholder="$ 40.000"
                    maxlength="9" 
                    onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                  />
                </div>

                <div class="col-md-2">
                   <button type="button" class="btn btn-success" @click="agregarDetalle()">Agregar</button>
                </div>

              </div>

              <div class="form-group row">
                    <div class="table-responsive col-md-12">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Categoría</th>
                    <th scope="col">Servicio</th>
                    <th scope="col">Valor Neto</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="det in detalles" :key="det.posicion_array">
                    <td class="text-left text-uppercase"><small v-text="det.tipo"></small></td>
                    <td class="text-left text-uppercase"><small v-text="det.servicio"></small></td>
                    <td class="text-left text-uppercase">${{ det.detalle_neto | currency}}</td>
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
                            @click.prevent="eliminarDetalle(det)"
                          >Eliminar</a>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
              </div>
              </div>

              <div v-show="errorTaller" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjTaller" :key="error" v-text="error"></div>
                </div>
              </div>

            </form>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            <button
              type="button"
              v-if="tallerAccion==1"
              class="btn btn-primary"
              @click="registrarTaller()"
            >Guardar</button>
            <button
              type="button"
              v-if="tallerAccion==2"
              class="btn btn-primary"
              @click="actualizarTaller()"
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
import jsPDF from 'jspdf';


Vue.use(VueCurrencyFilter);


export default {
  props: ["ruta"],
  created(){
    axios.get('/todoslosvehiculos').then(response => { this.vehiculos = response.data; }).catch(errors => { console.log(errors); })
  },
  data() {
    return {
      id: 0,
      estado:"En curso",
      vehiculo:0,
      tipo:"Mano de Obra Mecánica",
      detalle:"",
      valor_neto:0,
      valor_total:0,
      detalle_neto:"",
      patente_vehiculo:"",
      comentario:"",
      vehiculos:[],
      arrayTaller:[],
      detalles:[],
      modal: 0,
      tituloModal: "",
      tallerAccion: 0,
      errorTaller: 0,
      errorMostrarMsjTaller: [],
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

    listarTaller(page, buscar, criterio) {
      let me = this;
      var url =
        "/taller?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayTaller = respuesta.servicios.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    abrirModalNuevoVehiculo(){
      const { value: formValues } =  Swal.fire({
        title: 'Nuevo Vehículo',
        html:
          '<input id="swal-input1" class="swal2-input" placeholder="Ingresa Patente Válida">' +
          '<input id="swal-input2" class="swal2-input" placeholder="Nombre del Propietario">' +
          '<input id="swal-input3" class="swal2-input" placeholder="Teléfono de Contacto">',
        focusConfirm: false,
        preConfirm: () => {
          this.registrarVehiculo(document.getElementById('swal-input1').value,document.getElementById('swal-input2').value, document.getElementById('swal-input3').value);        
        }
        
      })

      if (formValues) {
        Swal.fire(JSON.stringify(formValues))
      }
    },
  agregarDetalle(){
    let me = this;
    if (me.detalle != "")
    {
      // Agregar al array
      me.detalles.push ({
        servicio: me.detalle,
        detalle_neto: me.detalle_neto,
        tipo: me.tipo
      });
      // Actualizar el Valor Neto del Servicio
      me.valor_neto = parseInt(me.valor_neto) + parseInt(me.detalle_neto);
      // Actualizar el Valor Total del Servicio
      me.valor_total = parseInt(me.valor_neto) * 1.19;
      // Limpiar los inputs
      me.detalle_neto = "";
      me.detalle = "";
    }
  },
      registrarVehiculo(patente,nombre,fono) {
      let me = this;
      axios
        .post("/vehiculo/registrar", {
          patente: patente,
          nombre_propietario: nombre,
          fono_propietario: fono,
          tipo: 1
        })
        .then(function(response) {
        
            axios.get('/todoslosvehiculos').then(response => { me.vehiculos = response.data; }).catch(errors => { console.log(errors); });

        })
        .catch(function(error) {
          console.log(error);
        });
    },
  eliminarDetalle(item){
    let me = this;
    var i = me.detalles.indexOf(item);
    if ( i !== -1 ) {
        me.detalles.splice(i,1);
        // Actualizar el Valor Neto del Servicio
        me.valor_neto = parseInt(me.valor_neto) - parseInt(item.detalle_neto);
        // Actualizar el Valor Total del Servicio
        me.valor_total = parseInt(me.valor_neto) * 1.19;
    }
    
  },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      //Actualiza la página actual
      me.pagination.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarTaller(page, buscar, criterio);
    },
    registrarTaller() {
      if (this.validarTaller()) {
        return;
      }

      let me = this;

      axios
        .post("/taller/registrar", {
          valor_neto: me.valor_neto,
          valor_total: me.valor_total,
          comentario: me.comentario,
          estado: me.estado,
          vehiculo: me.vehiculo,
          detalles: me.detalles
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarTaller(1, "", "id");
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
    actualizarTaller() {
      if (this.validarTaller()) {
        return;
      }

      let me = this;

      axios
        .put("/taller/actualizar", {
          valor_neto: me.valor_neto,
          valor_total: me.valor_total,
          comentario: me.comentario,
          estado: me.estado,
          vehiculo: me.vehiculo,
          detalles: me.detalles,
          id: me.id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarTaller(1, "", "id");
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
    eliminarTaller(id) {
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
          axios.put("/taller/eliminar", {
              id: id
            })
            .then(function(response) {
              me.listarTaller(1, "", "id");
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

    CrearPDF(servicio){
      axios.get('/detallesporservicio/'+ servicio.id).then(response => { this.generarPDF(servicio,response.data); }).catch(errors => { console.log(errors); });
      
    },

   isNull(texto){
      if (texto === null){
        return "";
      }else{
        return texto;
      }
    },

    generarPDF(servicio,detalles){
       // Nombre del Documento
      let pdfName = 'Orden_de_Servicio_' + servicio.id; 
      // Dar Tamaño al PDF
      var doc = new jsPDF('p','pt','letter');
      // Agregar Logo Principal
      var logo = new Image();
      logo.src = 'argon/img/brand/sammy.jpg';
      doc.addImage(logo, 'JPG', 40, 40);
      doc.setFontSize(12);
      doc.text("SAMMI LTDA", 160, 60);
      doc.setFontSize(10);
      doc.text("Servicio Automotriz" , 160, 75);
      doc.text("Rut 76.783.908-1", 160, 90);
      doc.text("Vicuña Mackenna N° 1812 - San Carlos, Ñuble", 160, 105);
      doc.text("Teléfono: +56988054341", 160, 120);
      var asisepaga = new Image();
      asisepaga.src = 'img/formasdepago.png';
      doc.addImage(asisepaga, 'PNG', 500, 40,50,50);
      // Datos del Cliente - Verificar Blancos
      var nombre_propietario = this.isNull(servicio.propietario);
      var patente = this.isNull(servicio.patente);
      var marca = this.isNull(servicio.marca);
      var modelo = this.isNull(servicio.modelo);
      // Imprimir Datos Cliente
      doc.text("Cliente : " + nombre_propietario.trim().toLowerCase().replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase()))), 40, 150);
      doc.text("Patente : " + patente.trim().toUpperCase(), 40, 165);
      doc.text("Marca : " + marca.trim().toLowerCase().replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase()))), 40, 180);
      doc.text("Modelo  : " + modelo.trim().toLowerCase().replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase()))), 40, 195);
      doc.text("Fecha : " + servicio.created_at, 40, 210);
      doc.setFontSize(12);
      doc.text("Orden de Servicio N° " + servicio.id, 425, 150);
      doc.setFontSize(10);
      
      
      // Comienza Tabla de Deatlles
      
      // Titulo Detalles
      doc.setFontSize(11);
      doc.text("Descripción", 40, 250);
      doc.text("Valores", 500, 250);
      doc.text('___________________________________________________________________________________', 40, 253);
      doc.setFontSize(10);
      
      // Crea Variable Contador de Lineas
      var linea = 270;

      //Impirmir Mano de Obra Mecánica
      doc.setFontSize(11);
      doc.text("Mano de Obra Mecánica", 40, linea);
      doc.setFontSize(10);
      // Variable Sub Total Mano de Obra Mecanica
      var mom = 0;
      // Agregar Detalles
      detalles.forEach(element => {
        if (element.tipo === "Mano de Obra Mecánica"){
          linea = linea + 20;
          var lines = doc.splitTextToSize(element.servicio.replace(/^\w/, (c) => c.toUpperCase()), 250);
          doc.text(40, linea, lines, {maxWidth: 350, align: "justify"});
          mom = mom + parseInt(element.valor_neto);
          doc.text("$ " + new Intl.NumberFormat().format(element.valor_neto) , 500, linea);
          // Verificar si es multi linea y saltar las lineas adecuadas
          if (lines.length > 1){
            linea = linea + (lines.length * 10);
          } 
          // Si llega a 600pt entonces agrega una nueva página    
          if(linea > 600){
            doc.addPage('letter', 'pt', 'p');
            linea = 40;          
          }   
        }       
      });
      doc.text('____________________________________________________________________________________________', 40, linea + 5);
      linea = linea + 20;
      doc.text("Sub Total (1) : " , 430, linea);
      doc.text("$ " + new Intl.NumberFormat().format(mom) , 500, linea);
      
      //Impirmir Mano de Obra Eléctrica
      linea = linea + 20;
      doc.setFontSize(11);
      doc.text("Mano de Obra Eléctrica", 40, linea);
      doc.setFontSize(10);
      var moe = 0;
      // Agregar Detalles
      detalles.forEach(element => {
        if (element.tipo === "Mano de Obra Eléctrica"){
         linea = linea + 20;
          var lines = doc.splitTextToSize(element.servicio.replace(/^\w/, (c) => c.toUpperCase()), 250);
          doc.text(40, linea, lines, {maxWidth: 350, align: "justify"});
          moe = moe + parseInt(element.valor_neto);   
          doc.text("$ " + new Intl.NumberFormat().format(element.valor_neto) , 500, linea);
          // Verificar si es multi linea y saltar las lineas adecuadas
          if (lines.length > 1){
            linea = linea + (lines.length * 10);
          } 
          // Si llega a 600pt entonces agrega una nueva página    
          if(linea > 600){
            doc.addPage('letter', 'pt', 'p');
            linea = 40;          
          }   
        }       
      });
      doc.text('____________________________________________________________________________________________', 40, linea + 5);
      linea = linea + 20;
      doc.text("Sub Total (2) : " , 430, linea);
      doc.text("$ " + new Intl.NumberFormat().format(moe) , 500, linea);      
      
      
      //Impirmir Ventas o Servicios
      linea = linea + 20;
      doc.setFontSize(11);
      doc.text("Ventas o Servicios", 40, linea);
      doc.setFontSize(10);
      var vos = 0;
      // Agregar Detalles
      detalles.forEach(element => {
        if (element.tipo === "Ventas o Servicios"){
          linea = linea + 20;
          var lines = doc.splitTextToSize(element.servicio.replace(/^\w/, (c) => c.toUpperCase()), 250);
          doc.text(40, linea, lines, {maxWidth: 350, align: "justify"});
          vos = vos + parseInt(element.valor_neto);    
          doc.text("$ " + new Intl.NumberFormat().format(element.valor_neto) , 500, linea);
          // Verificar si es multi linea y saltar las lineas adecuadas
          if (lines.length > 1){
            linea = linea + (lines.length * 10);
          } 
          // Si llega a 600pt entonces agrega una nueva página    
          if(linea > 600){
            doc.addPage('letter', 'pt', 'p');
            linea = 40;          
          }   
        }       
      });
      doc.text('____________________________________________________________________________________________', 40, linea + 5);
      linea = linea + 20;
      doc.text("Sub Total (3) : " , 430, linea);
      doc.text("$ " + new Intl.NumberFormat().format(vos) , 500, linea);              
         
      //Impirmir Repuestos o Insumos
      linea = linea + 20;
      doc.setFontSize(11);
      doc.text("Repuestos o Insumos", 40, linea);
      doc.setFontSize(10);
      var roi = 0;
      // Agregar Detalles
      detalles.forEach(element => {
        if (element.tipo === "Repuestos o Insumos"){
         linea = linea + 20;
          var lines = doc.splitTextToSize(element.servicio.replace(/^\w/, (c) => c.toUpperCase()), 250);
          doc.text(40, linea, lines, {maxWidth: 350, align: "justify"});   
          roi = roi + parseInt(element.valor_neto);
          doc.text("$ " + new Intl.NumberFormat().format(element.valor_neto) , 500, linea);
          // Verificar si es multi linea y saltar las lineas adecuadas
          if (lines.length > 1){
            linea = linea + (lines.length * 10);
          } 
          // Si llega a 600pt entonces agrega una nueva página    
          if(linea > 600){
            doc.addPage('letter', 'pt', 'p');
            linea = 40;          
          }   
        }       
      });
      doc.text('____________________________________________________________________________________________', 40, linea + 5);
      linea = linea + 20;
      doc.text("Sub Total (4) : " , 430, linea);
      doc.text("$ " + new Intl.NumberFormat().format(roi) , 500, linea);              
      linea = linea + 20;

      //Imprimir Totales
      linea = linea + 20;
      doc.text("Total Neto : " , 430, linea);
      doc.text("$ " + new Intl.NumberFormat().format(servicio.valor_neto) , 500, linea);
      linea = linea + 20;
      doc.text("I.V.A (%) : " , 430, linea);
      doc.text("$ " + new Intl.NumberFormat().format(servicio.valor_total - servicio.valor_neto) , 500, linea);
      linea = linea + 20;
      doc.text("A Pagar : " , 430, linea);
      doc.text("$ " + new Intl.NumberFormat().format(servicio.valor_total) , 500, linea);
      linea = linea + 30;
      doc.text("Notas u Observaciones : " , 40, linea);
      linea = linea + 20;
      var comentario = this.isNull(servicio.comentario);
      var lines = doc.splitTextToSize(comentario.trim().toLowerCase().replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase()))), 250);
      doc.text(40, linea, lines, {maxWidth: 500, align: "justify"}) 
      if (lines.length > 1){
        linea = linea + (lines.length * 5);
      }
      linea = linea + 20;
      doc.text("Estado : " , 40, linea);
      doc.text(servicio.estado , 80, linea);   
      linea = linea + 30;
      var lines = doc.splitTextToSize("Formas de Pago : Depósito Bancario a nombre de SAMMI LTDA, Chequera Electrónica Banco Estado N° 517-7-044612-4, Rut : 76.783.908-1, Correo Electrónico : guillermo.sammi@gmail.com", 250);
      doc.text(40, linea, lines, {maxWidth: 500, align: "justify"}) 
      // generar el PDF
      doc.save(pdfName + '.pdf');
    },


    validarTaller() {
      this.errorTaller = 0;
      this.errorMostrarMsjTaller = [];

      if (this.vehiculo==0 )
        this.errorMostrarMsjTaller.push(
          "Debe seleccionar un vehiculo al menos."
        );

      if (this.detalles.length == 0)
        this.errorMostrarMsjTaller.push(
          "Debe agregar al menos un servicio o insumo."
        );

      if (this.errorMostrarMsjTaller.length) this.errorTaller = 1;

      return this.errorTaller;
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.comentario = "";
      this.valor_neto = 0;
      this.valor_total = 0;
      this.vehiculo = 0;
      this.detalles = [];
      this.estado = "En curso";
    },

    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "taller": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Nuevo Servicio";
              this.patente = "";
              this.marca = "";
              this.motor = "";
              this.modelo = "";
              this.vin = "";
              this.chasis = "";
              this.nombre_propietario = "";
              this.correo_propietario = "";
              this.fono_propietario = "";
              this.tallerAccion = 1;
              break;
            }
            case "actualizar": {
              this.modal = 1;
              this.tituloModal = "Actualizar Servicio N° " + data["id"];
              this.tallerAccion = 2;
              this.id = data["id"];
              this.comentario = data["comentario"];
              this.valor_neto = data["valor_neto"];
              this.valor_total = data["valor_total"];
              this.vehiculo = data["patente_vehiculo"];
              this.estado = data["estado"];
              this.detalles = data["detalles"];
              // Buscar Detalles del Servicio
              axios.get('/detallesporservicio/'+ this.id).then(response => { this.detalles = response.data; }).catch(errors => { console.log(errors); })
             
              break;
            }
          }
        }
      }
    }
  },
  mounted() {
    this.listarTaller(1, this.buscar, this.criterio);

  }
};
</script>
