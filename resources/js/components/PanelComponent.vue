<template>
       <div>

           <div class="header bg-gradient-primary pb-8 pt-5 pt-md-5">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Servicios Automotricez</h5>
                                    <span class="h2 font-weight-bold mb-0"><b>{{ ingresos }}</b></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-car"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"> servicios</span>
                                <span class="text-nowrap">ingresados hasta el momento </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Recaudación Mano de Obra Mecánica</h5>
                                    <span class="h2 font-weight-bold mb-0"><b>$ {{ mom | currency}}</b></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-cogs"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"> pesos</span>
                                <span class="text-nowrap">durante este mes </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Recaudación Mano de Obra Eléctrica</h5>
                                    <span class="h2 font-weight-bold mb-0"> <b>${{ moe | currency }}</b></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-bolt"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"> pesos</span>
                                <span class="text-nowrap">durante este mes </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Recaudación Ventas y Servicios</h5>
                                    <span class="h2 font-weight-bold mb-0"><b>${{ vys | currency }}</b></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-car-battery"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"> pesos</span>
                                <span class="text-nowrap">durante este mes </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
      mom: 0,
      moe:0,
      ingresos: 0,
      vys: 0
    };
  },
  computed: {

  },
  methods: {

    GetMOM() {
      let me = this;
      var url = "/momdeldia";
      axios
        .get(url)
        .then(function(response) {         
          me.mom = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

        GetVYS() {
      let me = this;
      var url = "/vys";
      axios
        .get(url)
        .then(function(response) {         
          me.vys = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    GetMOE() {
      let me = this;
      var url = "/moe";
      axios
        .get(url)
        .then(function(response) {         
          me.moe = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    GetIngresos() {
      let me = this;
      var url = "/ingresosaldia";
      axios
        .get(url)
        .then(function(response) {            
          me.ingresos = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },


    recaudaciondeldia() {
      let me = this;
      var url = "/recaudaciondeldia";
      axios
        .get(url)
        .then(function(response) {
            me.recaudacion_al_minuto = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    vendedordelmes() {
      let me = this;
      var url = "/vendedordelmes";
      axios
        .get(url)
        .then(function(response) {
            me.vendedor_del_mes = response.data[0]['vendedor'];
            me.ventas_del_vendedor = response.data[0]['ventas_realizadas'];
        })
        .catch(function(error) {
          console.log(error);
        });
    },



  },
   created: function(){
      
      this.GetIngresos();
      this.GetMOE();
      this.GetVYS();
      this.GetMOM();
    },
  mounted() {
      

      
  }
};
</script>

