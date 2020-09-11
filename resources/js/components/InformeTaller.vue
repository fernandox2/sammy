<template>

  <div>
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-2"></div>

      <div class="container-fluid mt--7">
        <div class="header-body">
      </div>

    <div class="card shadow">
      
      <div class="card-header border-0">
        <div class="row">
          <div class="col">
              <div class="alert alert-default alert-dismissible fade show" role="alert">
                <span class="alert-icon"><i class="ni ni-chart-bar-32"></i></span>
                <span class="alert-text text-uppercase"><strong>Informe de Servicios Automotrices</strong></span>
              </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3">

              <!-- Calendario Inicio -->
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="ni ni-calendar-grid-58"></i>
                    </span>
                  </div>
                  <input
                    class="form-control"
                    v-model="inicio"
                    placeholder="Select date"
                    type="date"
                  />
                </div>
              </div>

            </div>

            <div class="col-md-3">
              <!-- Calendario Fin -->
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="ni ni-calendar-grid-58"></i>
                    </span>
                  </div>
                  <input
                    class="form-control"
                    v-model="fin"
                    placeholder="Seleccione Fecha"
                    type="date"
                  />
                </div>
              </div>
            </div>

                        <div class="col-md-3">
              <!-- Calendario Fin -->
              <div class="form-group row">
                <div class="col-md-9">
                  <select v-model="estado" class="form-control" v-on:change.prevent="">
                              <option value="En curso">En curso</option>
                              <option value="Terminada">Terminada</option>
                    </select>
                </div>
              </div>
            </div>
            
            <div class="col-md-3">

              <button type="button" class="btn btn-default" @click.prevent="listarVentas()">Filtrar</button>  
             
            </div>

          </div>
          <br>
          <div class="row">
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Día</th>
                    <th scope="col">Serv.</th>
                    <th scope="col">MoM ($)</th>
                    <th scope="col">MoE ($)</th>
                    <th scope="col">VyS ($)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="venta in arrayVenta" :key="venta.id">
                    <td><h5>{{ venta.fecha}}</h5></td>
                    <td>{{ venta.numero_servicios}}</td>
                    <td>${{venta.mom | currency }}</td>
                    <td>${{venta.moe | currency}}</td>
                    <td>${{venta.vys | currency}}</td>
                  </tr>
                  <tr>
                    <td><h5 class="text-primary">TOTALES</h5></td>
                    <td><h5 class="text-primary">{{ t_serv | currency}}</h5></td>
                    <td><h5 class="text-primary">${{ t_mom | currency}}</h5></td>
                    <td><h5 class="text-primary">${{ t_moe | currency}}</h5></td>
                    <td><h5 class="text-primary">${{ t_vys | currency}}</h5></td>
                  </tr>
                </tbody>
              </table>
            </div>

                <div class="col-md-3">
                  <h6>MoM = Mano de Obra Mecánica</h6>
                  <h6>MoE = Mano de Obra Eléctrica</h6>
                  <h6>VyS = Ventas y Servicios</h6>
                </div>
          </div>
        </div>
      </div>
    </div>


  </div>
</template>

<script>
// Importaciones
import Swal from "sweetalert2";
import VueCurrencyFilter from "vue-currency-filter";
// Usos
Vue.use(VueCurrencyFilter);

export default {
  props: ["ruta"],
  data() {
    return {
      inicio:null,
      fin: null,
      t_serv: 0,
      t_mom : 0,
      t_moe : 0,
      t_vys: 0,
      arrayVenta: [], 
      estado: "Terminada"
    };
  },
  computed: {
    isActived: function() {},
    },
  methods: {  
    listarVentas() {
      let me = this;
      me.t_serv = 0;
      me.t_mom = 0;
      me.t_moe = 0;
      me.t_vys = 0;
      axios.post("/serviciosporfecha", {
          inicio: me.inicio,
          fin: me.fin,
          estado: me.estado
        })
        .then(function(response) {
          var res = response.data;
          res.forEach(e => {
            if (e.mom == null ){
              e.mom = 0;
            }
            if (e.moe == null ){
              e.moe = 0;
            }
            if (e.vys == null ){
              e.vys = 0;
            }           
            me.t_serv = me.t_serv + parseInt(e.numero_servicios);
            me.t_mom = me.t_mom + parseInt(e.mom);
            me.t_moe = me.t_moe + parseInt(e.moe);
            me.t_vys = me.t_vys + parseInt(e.vys);
          });
          me.arrayVenta = res;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

  },
  mounted() {

  }
};
</script>
