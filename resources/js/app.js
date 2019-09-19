/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')
window.Vue = require('vue')

import Gate from "./Gate"
Vue.prototype.$gate = new Gate(window.user)

// vFrom
import { Form, HasError, AlertError } from 'vform'
window.Form = Form
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

Vue.component('pagination', require('laravel-vue-pagination'));

// SweetAlert2
import Swal from 'sweetalert2'
window.Swal = Swal

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});
window.Toast = Toast

// VueRouter
import VueRouter from 'vue-router'
Vue.use(VueRouter)

let routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/developer', component: require('./components/Developer.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/users', component: require('./components/Users.vue').default },
    { path: '*', component: require('./components/NotFound.vue').default }
]

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
});

// Event
window.Fire = new Vue()

// VueProgressBar
import VueProgressBar from 'vue-progressbar'
const options = {
    color: '#3498db',
    failedColor: '#874b4b',
    thickness: '4px',
    transition: {
      speed: '0.4s',
      opacity: '0.6s',
      termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
}
Vue.use(VueProgressBar, options)

// Vue global filter
Vue.filter('upChar', function(val) {
    return val.charAt(0).toUpperCase() + val.slice(1)
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

Vue.component(
    'not-found',
    require('./components/NotFound.vue').default
);

const app = new Vue({
    el: '#app',
    router,
    
    data: {
        search: ''
    },

    methods: {
        searchFor: _.debounce(() => {
            Fire.$emit('searching')
        }, 1000),

        printMe() {
            window.print()
        }
    }
});
