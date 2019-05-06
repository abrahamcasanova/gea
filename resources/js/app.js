
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


// Dependencies --------------------------------------
import es from './es';
import axios from 'axios';
import 'moment/locale/es';
import UUID from 'vue-uuid';
import moment from 'moment';
import swal from 'sweetalert';
import Vuetify from 'vuetify';
import VueFire from 'vuefire';
import Pusher from 'pusher-js';
import VueClip from 'vue-clip';
import Echo from "laravel-echo";
import Firebase from 'firebase';
import Raters from 'vue-rate-it';
import VModal from 'vue-js-modal';
import Toasted from 'vue-toasted';
import VueNumeric from 'vue-numeric';
import Timeline from 'timeline-vuejs';
import 'vuetify/dist/vuetify.min.css';
//import VeeValidate from 'vee-validate';
import Vue2Filters from 'vue2-filters';
import BootstrapVue from 'bootstrap-vue';
import Datepicker from 'vuejs-datepicker';
import Multiselect from 'vue-multiselect';
import locale from 'element-ui/lib/locale';
import uploader from 'vue-simple-uploader';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import App from './components/folder/App.vue';
import VueInternationalization from 'vue-i18n';
import 'timeline-vuejs/dist/timeline-vuejs.css';
import vueUrlParameters from 'vue-url-parameters';
import Locale from './vue-i18n-locales.generated';
import lang_es from 'element-ui/lib/locale/lang/es';
import VueContentPlaceholders from 'vue-content-placeholders';
import Permissions from './components/Permissions';
import VueApexCharts from 'vue-apexcharts'
import VdtnetTable from 'vue-datatables-net'
import 'datatables.net-bs4'

Vue.use(VdtnetTable);
Vue.use(VueApexCharts);
Vue.component('apexchart', VueApexCharts);
Vue.use(UUID);
locale.use(lang_es);
Vue.use(Vuetify)
Vue.component('star-rating', Raters.StarRating);
Vue.component('image-rating', Raters.ImageRating);
Vue.use(VueFire)
Vue.use(Firebase)
Vue.use(Datepicker)
Vue.use(BootstrapVue)
Vue.use(VueNumeric)
Vue.use(Vue2Filters)
Vue.use(VueInternationalization);


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
/*Vue.use(VeeValidate, {
  errorBagName: 'vErrors'
});*/

Vue.use(require('moment/locale/es'));

import VueMoment from 'vue-moment'


require('moment/locale/es')
Vue.use(VueMoment, {
  moment
})
moment.locale("es");
var m = moment().format("LLL")

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
console.log(process.env.NODE_ENV)

if ( process.env.NODE_ENV == 'production' ) {
     var config = {
      apiKey: "AIzaSyCiRoD159FcVQ6efXT0a6f4s-AsXFXoVqY",
      authDomain: "travel-f0875.firebaseapp.com",
      databaseURL: "https://travel-f0875.firebaseio.com",
      projectId: "travel-f0875",
      storageBucket: "travel-f0875.appspot.com",
      messagingSenderId: "76422469644"
    };
} else {
     var config = {
       apiKey: "AIzaSyCbyyvqiAuJeWjNvMakL9_R-jhkpa0oml4",
       authDomain: "proyecto-gea-dfbee.firebaseapp.com",
       databaseURL: "https://proyecto-gea-dfbee.firebaseio.com",
       projectId: "proyecto-gea-dfbee",
       storageBucket: "proyecto-gea-dfbee.appspot.com",
       messagingSenderId: "571952390252",
     };
}


export const firebase = Firebase.initializeApp(config)
export const db = firebase.database()
export const db_dashbord = firebase.database().ref('quote_tracks')
        .orderByChild('contact_date')
        .limitToLast(100)


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
Vue.component('quotes-count', require('./components/dashboard/QuotesCount.vue'));
Vue.component('quotes', require('./components/dashboard/Quotes.vue'));
Vue.component('quote-indicator', require('./components/dashboard/QuoteIndicator.vue'));
Vue.component('top-products', require('./components/dashboard/TopProducts.vue'));
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
Vue.component('customers-orden', require('./components/customers/Orden.vue'));
Vue.component('customers-orden-public', require('./components/customers/OrdenPublic.vue'));

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

// Product Type
Vue.component('products-type-index', require('./components/products_type/Index.vue'));
Vue.component('products-type-create', require('./components/products_type/Create.vue'));
Vue.component('products-type-edit', require('./components/products_type/Edit.vue'));

// Suppliers
Vue.component('suppliers-index', require('./components/suppliers/Index.vue'));
Vue.component('suppliers-create', require('./components/suppliers/Create.vue'));
Vue.component('suppliers-edit', require('./components/suppliers/Edit.vue'));

// Custsomer Orders
Vue.component('customers-orders-index', require('./components/customers_orders/Index.vue'));
Vue.component('customers-orders-create', require('./components/customers_orders/Create.vue'));
Vue.component('customers-quote-create', require('./components/customers_orders/Quote.vue'));
Vue.component('customers-orders-edit', require('./components/customers_orders/Edit.vue'));

// quotes
Vue.component('quotes-index', require('./components/quotes/Index.vue'));
Vue.component('quotes-edit', require('./components/quotes/Edit.vue'));
Vue.component('quotes-track', require('./components/quotes/Track.vue'));

// sales
Vue.component('sales-input', require('./components/sales/Input.vue'));
Vue.component('sales-index', require('./components/sales/Index.vue'));
Vue.component('sales-edit', require('./components/sales/Edit.vue'));

//Calendar
Vue.component('calendar', require('./components/dashboard/Calendar.vue'));
Vue.component('tracing-count', require('./components/dashboard/Tracing.vue'));
Vue.component('sold-count', require('./components/dashboard/Sold.vue'));

// Product
Vue.component('destinations-index', require('./components/destinations/Index.vue'));
Vue.component('destinations-create', require('./components/destinations/Create.vue'));
Vue.component('destinations-edit', require('./components/destinations/Edit.vue'));

// Payment
Vue.component('payments-index', require('./components/payments/Index.vue'));
Vue.component('payments-create', require('./components/payments/Create.vue'));
Vue.component('payments-edit', require('./components/payments/Edit.vue'));

//Type Payment
Vue.component('type-payments-index', require('./components/type_payments/Index.vue'));
Vue.component('type-payments-create', require('./components/type_payments/Create.vue'));
Vue.component('type-payments-edit', require('./components/type_payments/Edit.vue'));

//Permmissions
Vue.use(require('./components/plugins/acl.js'));

//Reports
Vue.component('reports-index', require('./components/reports/Index.vue'));

//General Config
Vue.component('general-config-index', require('./components/general_config/Index.vue'));

// Enable pusher logging - don't include this in production
Pusher.logToConsole = false;

var pusher = new Pusher('758c1ced7318559c6c9d', {
  cluster: 'us2',
  forceTLS: true
});


export default {
  components: {
    Datepicker,
    Timeline
  },
  mixins: [Vue2Filters.mixin,vueUrlParameters],
}


const app = new Vue({
    el: '#app',
    i18n,
    created() {
        window.Echo.channel('task-channel')
        .listen('.task-event', (e) => {
            console.log(e)
            let toast = this.$toasted.show("Seguimiento Actualizado!", {
            	 theme: "toasted-primary",
               iconPack: 'fontawesome',
               icon : 'user',
            	 position: "top-right",
            	 duration : 15000
            });
        });

        window.Echo.channel('task-channel')
        .listen('.task-event-supplier', (e) => {
            console.log(e)
            let toast = this.$toasted.show("Nuevo proveedor creado!", {
            	 theme: "toasted-primary",
               iconPack: 'fontawesome',
               icon : 'user',
            	 position: "top-right",
            	 duration : 30000
            });
        });
    },
});
