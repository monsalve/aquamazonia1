/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'
import Swal from 'sweetalert2'
import Vue from 'vue'
import { Form, HasError, AlertError } from 'vform'

import JsonExcel from 'vue-json-excel'
Vue.component('downloadExcel', JsonExcel)
window.Swal = Swal
Vue.use(VueRouter)

let routes = [
    { path: '/', component: require('./components/Dashboard.vue').default },
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/contenedores', component: require('./components/Contenedores.vue').default },
    { path: '/especies', component: require('./components/Especies.vue').default },
    { path: '/alimentos', component: require('./components/Alimentos.vue').default },
    { path: '/recursos', component: require('./components/Recursos.vue').default },
    { path: '/usuarios', component: require('./components/Usuarios.vue').default },
    { path: '/siembras', component: require('./components/Siembras.vue').default },
    { path: '/recursos-necesarios', component: require('./components/RecursosNecesarios.vue').default },
    { path: '/informes', component: require('./components/Informes.vue').default },
    { path: '/alimentacion', component: require('./components/Alimentacion.vue').default },
    { path: '/ciclo-productivo', component: require('./components/InformeCicloProductivo.vue').default },
    { path: '/calidad-agua', component: require('./components/CalidadAgua.vue').default },
    { path: '/informe-siembras', component: require('./components/InformeSiembras.vue').default },
    { path: '/informes-parametros', component: require('./components/InformeParametros.vue').default },
    { path: '/informe-actividades', component: require('./components/InformeActividades.vue').default },
    { path: '/informe-consolidado', component: require('./components/InformeConsolidado.vue').default },
    { path: '/informes-recursos-necesarios', component: require('./components/InformeRecursosNecesarios.vue').default },
    { path: '/informes-biomasa-alimento', component: require('./components/InformeBiomasaAlimento.vue').default },
    { path: '/informe-costos', component: require('./components/InformeCostos.vue').default },
    { path: '/historial-costos-alimentos', component: require('./components/HistorialCostosAlimentos.vue').default },
    { path: '/historial-costos-recursos', component: require('./components/HistorialCostosRecursos.vue').default },
    { path: '/informe-consolidado-alimentos', component: require('./components/InformeConsolidadoAlimentos.vue').default },
    { path: '/example', component: require('./components/ExampleComponent.vue').default },

]

const router = new VueRouter({

    routes // short for `routes: routes`
})
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('example-component', require('./components/ExampleComponent.vue').default);

//Vue.component('dashboard-component', require('./components/Dashboard.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
});