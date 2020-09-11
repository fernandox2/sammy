<template>
  <div>
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-2"></div>
    <div class="container-fluid mt--7">
      <div class="header-body">
        <a
          class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"
        >Cotizador Sammi</a>
      </div>

      <!-- Table -->
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
                        <div class="row">
          <div class="col">
              <div class="alert alert-default alert-dismissible fade show" role="alert">
                <span class="alert-icon"><i class="ni ni-ruler-pencil"></i></span>
                <span class="alert-text text-uppercase"><strong>Cotizaciones</strong></span>
              </div>
          </div>
        </div>
              <button
                @click="abrirModal('cotizacion','registrar')"
                type="button"
                class="btn btn-success float-left"
              >Nueva Cotizacion</button>

              <input
                v-on:change="listarCotizacion(1,buscar)"
                type="text"
                v-model="buscar"
                class="form-control float-right"
                placeholder="Buscar por nombre del cliente ..."
                style="max-width:50%;"
              />
              
            </div>

            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">N°</th>
                    <th scope="col">Nombre Cliente</th>
                    <th scope="col">Contacto Cliente</th>
                    <th scope="col">División</th>
                    <th scope="col">Fecha</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="cotizacion in arrayCotizacion" :key="cotizacion.id">

                    <td class="text-left text-uppercase"><small v-text="cotizacion.id"></small></td>
                    <td class="text-left text-uppercase"><small v-text="cotizacion.nombre_cliente"></small></td>
                    <td class="text-left text-uppercase"><small v-text="cotizacion.contacto_cliente"></small></td>
                    <td class="text-left text-uppercase"><small v-text="cotizacion.tipo"></small></td>
                    <td class="text-left text-uppercase"><small v-text="cotizacion.created_at"></small></td>
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
                            @click.prevent="CrearPDF(cotizacion)"
                          >Generar PDF</a>
                          <a
                            style="cursor:pointer;"
                            class="dropdown-item"
                            @click.prevent="abrirModal('cotizacion','actualizar',cotizacion)"
                          >Modificar</a>
                          <a
                          style="cursor:pointer;"
                            class="dropdown-item"
                            @click.prevent="eliminarCotizacion(cotizacion.id)"
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
                    <label class="col-md-3 form-control-label" for="text-input">Señor(es) :</label>
                    <div class="col-md-9">
                        <input type="text" v-model="nombre_cliente" class="form-control" placeholder="Ej : IANSAGRO S.A"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Rut :</label>
                    <div class="col-md-9">
                        <input type="text" v-model="rut_cliente" class="form-control" placeholder="Ej : 96.772.810-1"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Dirección :</label>
                    <div class="col-md-9">
                        <input type="text" v-model="direccion_cliente" class="form-control" placeholder="Ej : Cocharcas Km"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Atención Sr :</label>
                    <div class="col-md-9">
                        <input type="text" v-model="contacto_cliente" class="form-control" placeholder="Ej : Yurian Jiménez"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Comentario :</label>
                    <div class="col-md-9">
                        <input type="text" v-model="comentario" class="form-control" placeholder="Ej : Visto los requerimientos en terreno ..."/>
                    </div>
                </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">División :</label>
                <div class="col-md-9">
                  <select v-model="tipo" class="form-control" v-on:change.prevent="">
                              <option value="Maestranza">Maestranza</option>
                              <option value="Mueblería">Mueblería</option>
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
                
               
                <div class="col-md-5">
                    <input
                    type="text"
                    maxlength="500"
                    v-model="descripcion"
                    class="form-control"
                    placeholder="Ej : Fabricación de soportes porta sensor"
                  />
                </div>

                <div class="col-md-2">
                    <input
                    type="text"
                    v-model="cantidad"
                    class="form-control"
                    placeholder="Ej : 5"
                    maxlength="9" 
                    onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                  />
                </div>
                
                <div class="col-md-3">
                    <input
                    type="text"
                    v-model="precio_unidad"
                    class="form-control"
                    placeholder="Ej : $ 40.000"
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
                    <th scope="col">Descripcion</th>
                    <th scope="col">Cant.</th>
                    <th scope="col">Precio Unidad</th>
                    <th scope="col">Valores</th>
                    <th scope="col"></th>
                  </tr>
                </thead>

                <tbody>

                  <tr v-for="det in detalles" :key="det.posicion_array">
                    
                    <td class="text-left text-uppercase"><small v-text="det.descripcion"></small></td>
                    <td class="text-left text-uppercase"><small v-text="det.cantidad"></small></td>
                    <td class="text-left text-uppercase">${{ det.precio_unidad | currency}}</td>
                    <td class="text-left text-uppercase">${{ det.valores | currency}}</td>
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

            <h4 class="text-primary">Condiciones</h4>
              
              <br>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Forma de Pago :</label>
                    <div class="col-md-9">
                        <input type="text" v-model="forma_de_pago" class="form-control" placeholder="Ej : 30 días depósito bancario, factura contra entrega"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Plazo de Entrega :</label>
                    <div class="col-md-9">
                        <input type="text" v-model="plazo_entrega" class="form-control" placeholder="Ej : 1 día / hábil siguiente"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Validez Cotización :</label>
                    <div class="col-md-9">
                        <input type="text" v-model="validez_cotizacion" class="form-control" placeholder="Ej : 15 días"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="text-input">Lugar de Entrega :</label>
                    <div class="col-md-9">
                        <input type="text" v-model="lugar_entrega" class="form-control" placeholder="Ej : Instalaciones IANSA"/>
                    </div>
                </div>

              <div v-show="errorCotizacion" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjCotizacion" :key="error" v-text="error"></div>
                </div>
              </div>

            </form>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            <button
              type="button"
              v-if="cotizacionAccion==1"
              class="btn btn-primary"
              @click="registrarCotizacion()"
            >Guardar</button>
            <button
              type="button"
              v-if="cotizacionAccion==2"
              class="btn btn-primary"
              @click="actualizarCotizacion()"
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
      item: 0,
      descripcion:"",
      nombre_cliente:"",
      rut_cliente:"",
      direccion_cliente:"",
      contacto_cliente:"",
      cantidad:"",
      tipo:"Maestranza",
      valor_neto:0,
      valor_total:0,
      precio_unidad:"",
      valores:0,
      comentario:"",
      forma_de_pago:"",
      plazo_entrega:"",
      lugar_entrega:"",
      validez_cotizacion:"",
      vehiculos:[],
      arrayCotizacion:[],
      detalles:[],
      modal: 0,
      tituloModal: "",
      cotizacionAccion: 0,
      errorCotizacion: 0,
      errorMostrarMsjCotizacion: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      criterio: "id",
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

    listarCotizacion(page, buscar, criterio) {
      let me = this;
      var url =
        "/cotizacion?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayCotizacion = respuesta.cotizaciones.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
  agregarDetalle(){
    let me = this;
    if (me.descripcion != "")
    {
      // Agregar al array
      me.detalles.push ({
        descripcion: me.descripcion,
        precio_unidad: me.precio_unidad,
        cantidad: parseInt(me.cantidad),
        valores: parseInt(me.cantidad) * parseInt(me.precio_unidad)
        
      });
      // Actualizar el Valor Neto del Servicio
      me.valor_neto = parseInt(me.valor_neto) + (parseInt(me.cantidad) * parseInt(me.precio_unidad));

      // Actualizar el Valor Total del Servicio
      me.valor_total = parseInt(me.valor_neto) * 1.19;
      // Limpiar los inputs
      me.precio_unidad = "";
      me.descripcion = "";
      me.cantidad = "";
    }
  },
  eliminarDetalle(item){
    let me = this;
    var i = me.detalles.indexOf(item);
    if ( i !== -1 ) {
        me.detalles.splice(i,1);
        // Actualizar el Valor Neto del Servicio
        me.valor_neto = parseInt(me.valor_neto) - parseInt(item.valores);
        // Actualizar el Valor Total del Servicio
        me.valor_total = parseInt(me.valor_neto) * 1.19;
    }
   
  },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      //Actualiza la página actual
      me.pagination.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarCotizacion(page, buscar, criterio);
    },

    registrarCotizacion() {
      if (this.validarCotizacion()) {
        return;
      }

      let me = this;

      axios
        .post("/cotizacion/registrar", {
          nombre_cliente: me.nombre_cliente,
          tipo:me.tipo,
          rut_cliente: me.rut_cliente,
          direccion_cliente: me.direccion_cliente,
          contacto_cliente: me.contacto_cliente,
          forma_de_pago: me.forma_de_pago,
          plazo_entrega: me.plazo_entrega,
          validez_cotizacion: me.validez_cotizacion,
          lugar_entrega: me.lugar_entrega,
          valor_neto: me.valor_neto,
          valor_total: me.valor_total,
          comentario: me.comentario,
          detalles: me.detalles
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarCotizacion(1, "", "id");
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
    actualizarCotizacion() {
      if (this.validarCotizacion()) {
        return;
      }

      let me = this;

      axios
        .put("/cotizacion/actualizar", {
          nombre_cliente: me.nombre_cliente,
          tipo:me.tipo,
          rut_cliente: me.rut_cliente,
          direccion_cliente: me.direccion_cliente,
          contacto_cliente: me.contacto_cliente,
          forma_de_pago: me.forma_de_pago,
          plazo_entrega: me.plazo_entrega,
          validez_cotizacion: me.validez_cotizacion,
          lugar_entrega: me.lugar_entrega,
          valor_neto: me.valor_neto,
          valor_total: me.valor_total,
          comentario: me.comentario,
          detalles: me.detalles,
          id: me.id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarCotizacion(1, "", "id");
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
    eliminarCotizacion(id) {
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
          axios.put("/cotizacion/eliminar", {
              id: id
            })
            .then(function(response) {
              me.listarCotizacion(1, "", "id");
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

    CrearPDF(cotizacion){
      axios.get('/detallesporcotizacion/'+ cotizacion.id).then(response => { this.generarPDF(cotizacion,response.data); }).catch(errors => { console.log(errors); });
      
    },


    isNull(texto){
      if (texto === null){
        return "";
      }else{
        return texto;
      }
    },

    generarPDF(cotizacion,detalles){
       // Nombre del Documento
      let pdfName = 'Cotizacion_' + cotizacion.id; 
      // Dar Tamaño al PDF
      var doc = new jsPDF('p','pt','letter');
      // Agregar Logo Principal
      var logo = new Image();
      logo.src = 'argon/img/brand/sammi.jpg';
      doc.addImage(logo, 'JPG', 40, 40);
      doc.setFontSize(12);
      doc.text("SAMMI LTDA", 160, 60);
      doc.setFontSize(10);
      if (cotizacion.tipo == "Maestranza"){
        doc.text("Maestranza y Mantención Industrial" , 160, 75);
      }else{
        doc.text("Muebles de Madera", 160, 75);
      }
      doc.text("Rut 76.783.908-1", 160, 90);
      doc.text("Vicuña Mackenna N° 1812 - San Carlos, Ñuble", 160, 105);
      doc.text("Teléfono: +56988054341", 160, 120);

      // Datos del Cliente - Verificar Blancos
      var nombre_cliente = this.isNull(cotizacion.nombre_cliente);
      var rut_cliente = this.isNull(cotizacion.rut_cliente);
      var direccion_cliente = this.isNull(cotizacion.direccion_cliente);
      var contacto_cliente = this.isNull(cotizacion.contacto_cliente);
      // Imprimir Datos Cliente
      doc.text("Señor(es) : " + nombre_cliente.trim().toLowerCase().replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase()))), 40, 150);
      doc.text("Rut : " + rut_cliente.trim().toLowerCase().replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase()))), 40, 165);
      doc.text("Dirección : " + direccion_cliente.trim().toLowerCase().replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase()))), 40, 180);
      doc.text("Atención  : " + contacto_cliente.trim().toLowerCase().replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase()))), 40, 195);
      doc.setFontSize(12);
      doc.text("Cotización N° " + cotizacion.id, 450, 150);
      doc.setFontSize(10);
      doc.text("Fecha : " + cotizacion.created_at, 40, 220);
      
      // Comienza Tabla de Deatlles
      
      // Titulo Detalles
      doc.setFontSize(11);
      doc.text("Item", 40, 250);
      doc.text("Descripción", 80, 250);
      doc.text("Cant", 350, 250);
      doc.text("Precio Unidad", 400, 250);
      doc.text("Valores", 500, 250);
      doc.text('___________________________________________________________________________________', 40, 253);
      doc.setFontSize(10);
      
      // Crea Variable Contador de Lineas
      var linea = 250;

      // Agregar Detalles
      detalles.forEach(element => {
        linea = linea + 20;
        doc.text(String(element.item), 45, linea);
        var lines = doc.splitTextToSize(element.descripcion.trim().toLowerCase().replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase()))), 250);
        doc.text(82, linea, lines, {maxWidth: 258, align: "justify"});   
        doc.text(String(element.cantidad), 355, linea);
        doc.text("$ " + new Intl.NumberFormat().format(element.precio_unidad) , 400, linea);
        doc.text("$ " + new Intl.NumberFormat().format(element.valores) , 500, linea);
        // Verificar si es multi linea y saltar las lineas adecuadas
        if (lines.length > 1){
          linea = linea + (lines.length * 10);
        } 
        doc.text('____________________________________________________________________________________________', 40, linea + 5);
        // Si llega a 600pt entonces agrega una nueva página    
        if(linea > 600){
          doc.addPage('letter', 'pt', 'p');
          linea = 40;          
        }   
      });

      linea = linea + 20;
      doc.text("Total Neto : " , 430, linea);
      doc.text("$ " + new Intl.NumberFormat().format(cotizacion.valor_neto) , 500, linea);
      linea = linea + 20;
      doc.text("I.V.A (%) : " , 430, linea);
      doc.text("$ " + new Intl.NumberFormat().format(cotizacion.valor_total - cotizacion.valor_neto) , 500, linea);
      linea = linea + 20;
      doc.text("A Pagar : " , 430, linea);
      doc.text("$ " + new Intl.NumberFormat().format(cotizacion.valor_total) , 500, linea);
      linea = linea + 30;
      doc.text("Notas u Observaciones : " , 40, linea);
      linea = linea + 20;
      var comentario = this.isNull(cotizacion.comentario);
      var lines = doc.splitTextToSize(comentario.trim().toLowerCase().replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase()))), 250);
      doc.text(40, linea, lines, {maxWidth: 500, align: "justify"}) 
      if (lines.length > 1){
        linea = linea + (lines.length * 5);
      }     
      linea = linea + 20;
      doc.text("Condiciones " , 40, linea);
      linea = linea + 15;
      var forma_de_pago = this.isNull(cotizacion.forma_de_pago);
      doc.text("Forma de Pago : " + forma_de_pago.trim().toLowerCase().replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase()))) , 40, linea);
      linea = linea + 15;
      var plazo_entrega = this.isNull(cotizacion.plazo_entrega);
      doc.text("Plazo de Entrega : " + plazo_entrega.trim().toLowerCase().replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase()))) , 40, linea);
      linea = linea + 15;
      var validez_cotizacion = this.isNull(cotizacion.validez_cotizacion);
      doc.text("Validéz de la Cotización : " + validez_cotizacion.trim().toLowerCase().replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase()))) , 40, linea);
      linea = linea + 15;
      var lugar_entrega = this.isNull(cotizacion.lugar_entrega);
      doc.text("Lugar de Entrega : " + lugar_entrega.trim().toLowerCase().replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase()))) , 40, linea);
      doc.save(pdfName + '.pdf');
    },


    validarCotizacion() {
      this.errorCotizacion = 0;
      this.errorMostrarMsjCotizacion = [];

      if (this.nombre_cliente==0 )
        this.errorMostrarMsjCotizacion.push(
          "Debe ingresar un cliente al cual cotizar."
        );

      if (this.detalles.length == 0)
        this.errorMostrarMsjCotizacion.push(
          "Debe agregar al menos un detalle a la cotización."
        );

      if (this.errorMostrarMsjCotizacion.length) this.errorCotizacion = 1;

      return this.errorCotizacion;
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.comentario = "";
      this.valor_neto = 0;
      this.valor_total = 0;
      this.nombre_cliente = 0;
      this.detalles = [];
      this.contacto_cliente = "";
      this.rut_cliente = "";
      this.direccion_cliente = "";
      this.forma_de_pago = "";
      this.plazo_entrega = "";
      this.validez_cotizacion = "";
      this.lugar_entrega = "";
      this.tipo = "Maestranza";
    },

    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "cotizacion": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Nueva Cotización";
              this.nombre_cliente = "";
              this.rut_cliente = "";
              this.direccion_cliente = "";
              this.contacto_cliente = "";
              this.comentario = "";
              this.detalles = [];
              this.valor_neto = 0;
              this.valor_neto = 0;
              this.forma_de_pago = "";
              this.plazo_entrega = "";
              this.validez_cotizacion = "";
              this.lugar_entrega = "";
              this.tipo = "Maestranza";
              this.cotizacionAccion = 1;
              break;
            }
            case "actualizar": {
              this.modal = 1;
              this.tituloModal = "Actualizar Cotización N° " + data["id"];
              this.cotizacionAccion = 2;
              this.id = data["id"];
              this.comentario = data["comentario"];
              this.valor_neto = data["valor_neto"];
              this.valor_total = data["valor_total"];
              this.nombre_cliente = data["nombre_cliente"];
              this.rut_cliente = data["rut_cliente"];
              this.direccion_cliente = data["direccion_cliente"];
              this.contacto_cliente = data["contacto_cliente"];
              this.forma_de_pago = data["forma_de_pago"];
              this.plazo_entrega = data["plazo_entrega"];
              this.validez_cotizacion = data["validez_cotizacion"];
              this.tipo = data["tipo"];
              this.lugar_entrega = data["lugar_entrega"];
              // Buscar Detalles del Servicio
              axios.get('/detallesporcotizacion/'+ this.id).then(response => { this.detalles = response.data; }).catch(errors => { console.log(errors); })
              break;
            }
          }
        }
      }
    }
  },
  mounted() {
    this.listarCotizacion(1, this.buscar, this.criterio);

  }
};
</script>
