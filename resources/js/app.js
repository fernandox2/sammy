
require('./bootstrap');

window.Vue = require('vue');



Vue.component('panel-component', require('./components/PanelComponent.vue').default);
Vue.component('ventas-component', require('./components/Venta.vue').default);
Vue.component('empresa-component', require('./components/Empresa.vue').default);
Vue.component('certifica-component', require('./components/Certifica.vue').default);
Vue.component('productos-component', require('./components/Producto.vue').default);
Vue.component('reportes-component', require('./components/Reporte.vue').default);
Vue.component('compras-component', require('./components/Compra.vue').default);
Vue.component('vehiculos-component', require('./components/Vehiculo.vue').default);
Vue.component('tipos-vehiculos-component', require('./components/TipoVehiculo.vue').default);
Vue.component('taller-component', require('./components/Taller.vue').default);
Vue.component('cotizacion-component', require('./components/Cotizacion.vue').default);
Vue.component('informe-taller-component', require('./components/InformeTaller.vue').default);
Vue.component('usuario-component', require('./components/Usuario.vue').default);


const app = new Vue({
    el: '#app',
    data :{
        menu : 0
    }
});
