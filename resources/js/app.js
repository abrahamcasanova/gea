
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


// Dependencies --------------------------------------

import Toasted from 'vue-toasted';
import VueClip from 'vue-clip'
import Multiselect from 'vue-multiselect'
import swal from 'sweetalert';
import VueContentPlaceholders from 'vue-content-placeholders'
import axios from 'axios';
import VeeValidate from 'vee-validate'
import uploader from 'vue-simple-uploader'
import App from './components/folder/App.vue'
import VModal from 'vue-js-modal'
import Pusher from 'pusher-js';
import Echo from "laravel-echo";
import VueFire from 'vuefire';
import Firebase from 'firebase';
import LightTimeline from 'vue-light-timeline';
import VueInternationalization from 'vue-i18n';
import Locale from './vue-i18n-locales.generated';

Vue.use(VueInternationalization);
Vue.use(LightTimeline);
Vue.use(VueFire)
Vue.use(Firebase)

const lang = document.documentElement.lang.substr(0, 2);

const i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});

Vue.config.lang = 'es';

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '758c1ced7318559c6c9d',
    cluster: 'us2',
});

Vue.use(VModal)
Vue.use(VeeValidate, {
  errorBagName: 'vErrors'
});

Vue.use(require('moment/locale/es'));

import VueMoment from 'vue-moment'
const moment = require('moment')
require('moment/locale/es')
Vue.use(VueMoment, {
  moment
})

Vue.use(Toasted)
Vue.toasted.register('error', message => message, {
    position : 'bottom-center',
    duration : 1500
})


Vue.use(VueClip)
Vue.component('multiselect', Multiselect)
Vue.use(VueContentPlaceholders)
Vue.use(uploader)
// Initialize Firebase
let config = {
  apiKey: "AIzaSyAl0sdBOKeyIGcV25WXWPSapv-_aCFisNY",
  authDomain: "caos-4fa8a.firebaseapp.com",
  databaseURL: "https://caos-4fa8a.firebaseio.com",
  projectId: "caos-4fa8a",
  storageBucket: "caos-4fa8a.appspot.com",
  messagingSenderId: "512709825599"
};


var firebase = Firebase.initializeApp(config)
export const db = firebase.database()



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 // Layout
 Vue.component('sidebar', require('./components/layout/Sidebar.vue'));

// Dashboard
Vue.component('users-count', require('./components/dashboard/UsersCount.vue'));
Vue.component('roles-count', require('./components/dashboard/RolesCount.vue'));
Vue.component('prospectings-count', require('./components/dashboard/ProspectingsCount.vue'));

// Profile
Vue.component('profile', require('./components/profile/Profile.vue'));
Vue.component('profile-password', require('./components/profile/Password.vue'));

// Users
Vue.component('users-index', require('./components/users/Index.vue'));
Vue.component('users-create', require('./components/users/Create.vue'));
Vue.component('users-edit', require('./components/users/Edit.vue'));

// Roles
Vue.component('roles-index', require('./components/roles/Index.vue'));
Vue.component('roles-create', require('./components/roles/Create.vue'));
Vue.component('roles-edit', require('./components/roles/Edit.vue'));

// Permissions
Vue.component('permissions-index', require('./components/permissions/Index.vue'));
Vue.component('permissions-create', require('./components/permissions/Create.vue'));
Vue.component('permissions-edit', require('./components/permissions/Edit.vue'));

// Customers
Vue.component('customers-index', require('./components/customers/Index.vue'));
Vue.component('customers-create', require('./components/customers/Create.vue'));
Vue.component('customers-edit', require('./components/customers/Edit.vue'));

// Folder
Vue.component('folder', require('./components/folder/App.vue'));

// Prospecting
Vue.component('prospecting-index', require('./components/prospectings/Index.vue'));
Vue.component('prospecting-create', require('./components/prospectings/Create.vue'));
Vue.component('prospecting-edit', require('./components/prospectings/Edit.vue'));
Vue.component('prospecting-track', require('./components/prospectings/Track.vue'));

// branch
Vue.component('branches-index', require('./components/branches/Index.vue'));
Vue.component('branches-create', require('./components/branches/Create.vue'));
Vue.component('branches-edit', require('./components/branches/Edit.vue'));

// Product
Vue.component('products-index', require('./components/products/Index.vue'));
Vue.component('products-create', require('./components/products/Create.vue'));
Vue.component('products-edit', require('./components/products/Edit.vue'));

// Enable pusher logging - don't include this in production
Pusher.logToConsole = false;

var pusher = new Pusher('758c1ced7318559c6c9d', {
  cluster: 'us2',
  forceTLS: true
});

const app = new Vue({
    el: '#app',
    i18n,
    created() {
        window.Echo.channel('task-channel')
        .listen('.task-event', (e) => {
            console.log(e['first_name'])
            let toast = this.$toasted.show("New prospecting created!", {
            	 theme: "toasted-primary",
               iconPack: 'fontawesome',
               icon : 'user',
            	 position: "top-right",
            	 duration : 30000
            });
        });
    },
});
