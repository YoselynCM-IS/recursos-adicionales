/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

// Import Bootstrap an BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

// MOMENT
Vue.use(require('vue-moment'));

// PAGINATION
Vue.component('pagination', require('laravel-vue-pagination'));
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// COMPONENTS
// *** USER
Vue.component('user-lista-component', require('./components/user/ListaComponent.vue').default);
// ADD
Vue.component('user-save-inf-component', require('./components/user/add/SaveInfComponent.vue').default);
Vue.component('user-add-ind-component', require('./components/user/add/AddIndComponent.vue').default);
Vue.component('user-add-mas-component', require('./components/user/add/AddMasComponent.vue').default);

// *** LIBROS
Vue.component('libros-lista-component', require('./components/libros/ListaComponent.vue').default);
// ADD LIBROS
Vue.component('libros-add-edit-component', require('./components/libros/add-libros/AddEditComponent.vue').default);
Vue.component('libros-add-recursos-component', require('./components/libros/add-libros/AddRecursosComponent.vue').default);
// RECURSOS
Vue.component('recursos-lista-component', require('./components/libros/recursos/ListaComponent.vue').default);
Vue.component('recursos-libro-component', require('./components/libros/recursos/RecursosLibroComponent.vue').default);
Vue.component('recursos-enlaces-component', require('./components/libros/recursos/EnlacesComponent.vue').default);

// *** RECURSOS
Vue.component('recursos-acceso-component', require('./components/recursos/AccesoComponent.vue').default);

const app = new Vue({
    el: '#app',
});
