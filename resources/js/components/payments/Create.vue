<template>
  <v-container>
    <v-app autocomplete="off">
      <v-layout >
        <v-flex xs12>
          <div class="card card-body col-md-12 container" style="margin-top:25px;">
            <div class=" row justify-content-md-center">
              <div class="col-md-12 col-xl-12">
                <div class="card-header px-0 mt-2 bg-transparent clearfix">
                  <h4 class="float-left pt-2">Registrar Pago:  {{this.sale.quote.customer_order.customer.full_name}}</h4>
                  <div class="card-header-actions mr-1">
                    <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="create">
                      <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
                      <span class="ml-1 v-else"> Guardar <i class="icon-check"></i></span>
                    </a>
                  </div>
                </div>
                <div class="col-md-12 card-body px-0">
                  <div class="row">
                    <div class="form-group col-md-6">
                        <label>Agente</label>
                        <input disabled readonly type="text" class="form-control" required :class="{'is-invalid': errors.name}" v-model="username" placeholder="Agente...">
                        <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Forma de pago</label>
                        <multiselect
                          v-model="payment.type_of_payment" 
                          :options="type_of_payment"
                          openDirection="bottom"
                          track-by="id"
                          @input="changeTypePayment"
                          label="name"
                          :class="{'border border-danger rounded': errors.type_of_payment}">
                        </multiselect>
                        <small class="form-text text-danger" v-if="errors.type_of_payment">{{errors.type_of_payment[0]}}</small>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Importe <strong>({{sale.quote.currency}})</strong></label>
                        <vue-numeric @input="changePrice" class="form-control"  :class="{'is-invalid': errors.price}" currency="$" separator="," :precision="2" v-model="payment.price"></vue-numeric>
                        <div class="invalid-feedback" v-if="errors.price">{{errors.price[0]}}</div>
                    </div>
                    <div v-if="payment.type_of_payment.with_authorization == 1" class="form-group col-md-3">
                        <v-text-field v-model="payment.authorization_number" :class="{'is-invalid': errors.authorization_number}" type="text" label="Autorización"></v-text-field>
                        <div class="invalid-feedback" v-if="errors.authorization_number">{{errors.authorization_number[0]}}</div>
                    </div>
                    <div v-if="payment.type_of_payment.with_authorization == 1" class="form-group col-md-3">
                        <v-menu
                          lazy
                          offset-y
                          full-width
                          :close-on-content-click="true"
                          v-model="menu"
                          transition="scale-transition"
                          :nudge-right="40"
                          max-width="290px"
                          min-width="290px"
                          >
                          <v-text-field
                          slot="activator"
                          label="Fecha deposito"
                          required
                          v-model="payment.deposit_date"
                          prepend-icon="event"
                          readonly
                          :error-messages="errors.date"
                          ></v-text-field>
                          <v-date-picker
                                color="blue lighten-1" locale="es-mx"
                                show-current
                                class="mt-3"
                                v-model="payment.deposit_date"
                            >
                            <template slot-scope="{ save, cancel }">

                            </template>
                         </v-date-picker>
                        </v-menu>
                        <div class="invalid-feedback" v-if="errors.deposit_date">{{errors.deposit_date[0]}}</div>
                    </div>
                    <div v-if="payment.type_of_payment.with_percentage == 1" class="form-group col-md-3">
                        <v-text-field  @input="changePrice" :suffix="'%'" :class="{'is-invalid': errors.percentage}" v-model="payment.percentage" type="number" step=".01" label="Porcentaje"></v-text-field>
                        <div class="invalid-feedback" v-if="errors.percentage">{{errors.percentage[0]}}</div>
                    </div>
                    <div v-if="sale.quote.currency != 'NACIONAL'" class="form-group col-md-3">
                        <v-text-field  @input="changePrice" :class="{'is-invalid': errors.exchange_rate}" v-model="payment.exchange_rate" type="number" label="Tipo de cambio"></v-text-field>
                        <div class="invalid-feedback" v-if="errors.exchange_rate">{{errors.exchange_rate[0]}}</div>
                    </div>
                    <div v-if="sale.quote.currency != 'NACIONAL' || payment.percentage > 0" class="form-group col-md-3">
                        <v-text-field :class="{'is-invalid': errors.real_price}" v-model="payment.real_price" type="number" step=".01" label="Importe (MXN)"></v-text-field>
                        <div class="invalid-feedback" v-if="errors.real_price">{{errors.real_price[0]}}</div>
                    </div>
                    <div class="form-group col-md-12">
                      <label>Comentario</label>
                      <textarea class="form-control" rows="5" :class="{'is-invalid': errors.comments}"  v-model="payment.comments"></textarea>
                      <div class="invalid-feedback" v-if="errors.comments">{{errors.comments[0]}}</div>
                  </div>
                  </div>
                  <div class="row float-right">
                    <div class="form-group col-md-12 float-right text h4">
                        <label><strong>Importe Total</strong></label>
                        {{ sale.price | currency }}<br>
                        <label><strong>Pagado</strong></label>
                        {{ totalPayment | currency }}<br>
                        <label><strong>Saldo</strong></label>
                        {{ payment.balance | currency }}<br>
                    </div>
                  </div>
                </div>
                <div class="card-body px-0">
                  <div class="row justify-content-between">
                    <div class="col-7 col-md-5">
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="fas fa-search"></i>
                          </span>
                        </div>
                        <input type="text" class="form-control" placeholder="Buscar.." v-model.trim="searchQuery">
                      </div>
                    </div>
                  </div>
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th class="">
                          <a href="#" class="text-dark" @click.prevent="sort('id')">Folio</a>
                          <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'id' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'id' && filters.orderBy.direction == 'desc'}"></i>
                        </th>
                        <th>
                          <a href="#" class="text-dark" @click.prevent="sort('product_type_id')">Importe pagado</a>
                          <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'product_type_id' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'product_type_id' && filters.orderBy.direction == 'desc'}"></i>
                        </th>
                        <th>
                          <a href="#" class="text-dark" @click.prevent="sort('url_image')">Agente</a>
                          <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'url_image' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'url_image' && filters.orderBy.direction == 'desc'}"></i>
                        </th>
                        <th>
                          <a href="#" class="text-dark" @click.prevent="sort('location')">Forma de pago</a>
                          <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'location' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'location' && filters.orderBy.direction == 'desc'}"></i>
                        </th>
                        <th>
                          <a href="#" class="text-dark" @click.prevent="sort('deposit_date')">Fecha de deposito</a>
                          <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'deposit_date' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'deposit_date' && filters.orderBy.direction == 'desc'}"></i>
                        </th>
                        <th class="">
                          <a href="#" class="text-dark" @click.prevent="sort('created_at')">Registrado</a>
                          <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'created_at' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'created_at' && filters.orderBy.direction == 'desc'}"></i>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="payment in paymentsFilter ">
                        <td class="">{{payment.id}}</td>
                        <td class="">{{ payment.price | currency }}</td>
                        <td class="">{{payment.user.name}}</td>
                        <td class="">{{payment.type_of_payment}}</td>
                        <td class="">{{payment.deposit_date}}</td>
                        <td class="">
                          <small>{{payment.created_at | moment("LL")}}</small> - <small class="text-muted">{{payment.created_at | moment("LT")}}</small>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="row" v-if='!loading && filters.pagination.total > 0'>
                    <div class="col pt-2">
                      {{filters.pagination.from}}-{{filters.pagination.to}} of {{filters.pagination.total}}
                    </div>
                    <div class="col" v-if="filters.pagination.last_page>1">
                      <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end">
                          <li class="page-item" :class="{'disabled': filters.pagination.current_page <= 1}">
                            <a class="page-link" href="#" @click.prevent="changePage(filters.pagination.current_page -  1)"><i class="fas fa-angle-left"></i></a>
                          </li>
                          <li class="page-item" v-for="page in filters.pagination.last_page" :class="{'active': filters.pagination.current_page == page}">
                            <span class="page-link" v-if="filters.pagination.current_page == page">{{page}}</span>
                            <a class="page-link" href="#" v-else @click.prevent="changePage(page)">{{page}}</a>
                          </li>
                          <li class="page-item" :class="{'disabled': filters.pagination.current_page >= filters.pagination.last_page}">
                            <a class="page-link" href="#" @click.prevent="changePage(filters.pagination.current_page +  1)"><i class="fas fa-angle-right"></i></a>
                          </li>
                        </ul>
                      </nav>
                    </div>
                  </div>
                  <div class="no-items-found text-center mt-5" v-if="!loading && !payments.length > 0">
                    <i class="icon-magnifier fa-3x text-muted"></i>
                    <p class="mb-0 mt-3"><strong>No se pudo encontrar ningún pago</strong></p>
                    <p class="text-muted">Intenta cambiar los filtros o añadir uno nuevo.</p>
                    <a class="btn btn-success" href="./payments/create" role="button">
                      <i class="fa fa-plus"></i>&nbsp; Nuevo pago
                    </a>
                  </div>
                  <content-placeholders v-if="loading">
                    <content-placeholders-text/>
                  </content-placeholders>
                </div>
              </div>
            </div>
          </div>
        </v-flex>
      </v-layout>
    </v-app>
  </v-container>
</template>

<script>
var csrf_token = $('meta[name="csrf-token"]').attr('content');
import Datepicker from 'vuejs-datepicker';
import {en, es} from 'vuejs-datepicker/dist/locale'
import moment from 'moment';

export default {
  components: {
      Datepicker,
  },
  computed: {
    /*totalPayment() {
      let total =  this.payments.reduce((acc, item) => acc + item.price, 0);
      //this.payment.balance = parseFloat(this.sale.price - this.total);
      //this.$emit('update:payment.balance',parseFloat(this.sale.price - total))
      return total;
    }*/
    paymentsFilter (){
        var filterKey = this.searchQuery && this.searchQuery.toLowerCase()
        var payments = this.payments
        if (filterKey) {
          payments = payments.filter(function (row) {
            return Object.keys(row).some(function (key) {
              return String(row[key]).toLowerCase().indexOf(filterKey) > -1
            })
          })
        }
        return payments;
      }
  },
  data () {
    return {
      payment: {
        type_of_payment:{
          name:null
        },
        user:{
          name:null
        },
        exchange_rate:0,
        percentage:0,
      },
      filters: {
        pagination: {
          from: 0,
          to: 0,
          total: 0,
          per_page: 25,
          current_page: 1,
          last_page: 0
        },
        orderBy: {
          column: 'id',
          direction: 'asc'
        },
        search: ''
      },
      loading: true,
      menu:false,
      payments: {},
      search:'',
      searchQuery: '',
      type_of_payment: [],
      errors: {},
      es: es,
      totalPayment: 0,
      sale:{
        quote:{
          customer_order:{
            customer:{full_name:null}
          }
        }
      },
      image: '',
      url: null,
      submiting: false,
    }
  },
  mounted () {
    this.getTypePayments()
    this.getSale();
    this.csrf = window.Laravel.csrfToken;
    this.payment.user_id =  this.user_id;
  },
  props: {
      user_id: Number ,
      username: String,
  },
  watch:{
    'user_id'(){
        this.$emit('update:user_id',this.user_id)  
        this.payment.user_id =  this.user_id;
    },
    'payment_balance'(){
        this.$emit('update:payment_balance',this.payment.payment_balance)  
        this.payment.payment_balance =  this.payment_balance;
    },
  },
  methods: {
      create () { 
        if (!this.submiting) {
          this.submiting = true

          if(!this.payment.type_of_payment.name){
            swal("Error, seleccionar forma de pago para continuar", {
                icon: 'error',
                buttons: false,
                timer: 3000
            });
            this.submiting = false
            return false;
          }
          swal("Espere unos segundos..", {
                icon: 'info',
                buttons: false,
          });
          let percentage =  (parseFloat(this.payment.price * this.payment.percentage) / 100);

          if(this.sale.quote.currency == 'NACIONAL' && this.payment.percentage == 0){
            this.payment.real_price = this.payment.real_price_without_percentage = this.payment.price;
          }else if(this.sale.quote.currency == 'NACIONAL' && this.payment.percentage > 0){
            this.payment.real_price_without_percentage = this.payment.price;
          }else if(this.sale.quote.currency != 'NACIONAL' && this.payment.percentage == 0){
            this.payment.real_price_without_percentage = parseFloat(this.payment.price * this.payment.exchange_rate);
          }else{

            let percentage =  (parseFloat(this.payment.price * this.payment.percentage) / 100);
            let rest = parseFloat(this.payment.exchange_rate * percentage);
            this.payment.real_price_without_percentage = parseFloat(this.payment.real_price - rest);
          }

          this.payment.sale_id = this.sale.id
          this.payment.customer_id = this.sale.quote.customer_order.customer_id;
          let sale_id = this.sale.id;
          axios.post(`../../api/payments/store`, this.payment)
          .then(response => {
              swal("Pago registrado correctamente!", {
                icon: 'success',
                timer:2000,
                buttons: false,
              });
              this.getPayments(this.payment.sale_id);

              setTimeout(function(){
                location.href = '../../payments/create/' + sale_id
                
              }, 1500);
          })
          .catch(error => {
            this.errors = error.response.data.errors
            this.submiting = false
            swal("Error, ingrese los datos nuevamente", {
                icon: 'error',
                buttons: false,
                timer: 3000
            });
          })

        }
      },
      customFormatterDate(date) {
          this.payment.payment_date = moment(date).format('YYYY/MM/DD hh:mm:ss');
          return moment(date).format('YYYY/MM/DD hh:mm:ss');
      },
      getTypePayments() {

          axios.get(`../../api/type_of_payments/all`).then(response => {
              this.type_of_payment = response.data
          })
          .catch(error => {
              this.errors = error.response.data.errors
          })
      },
      changePrice(){
          if(this.payment.type_of_payment.with_percentage != 1){
            this.payment.percentage = 0;
          }
          let percentage =  (parseFloat(this.payment.price * this.payment.percentage) / 100);
          if(this.payment.exchange_rate > 0){
            this.payment.real_price = parseFloat((this.payment.price + percentage) * (this.payment.exchange_rate)).toFixed(2)
          }else{
            this.payment.real_price = parseFloat(this.payment.price + percentage).toFixed(2)
          }
      },
      changeTypePayment(){
          if(this.payment.type_of_payment.with_percentage != 1){
            this.payment.percentage = 0;
          }
          this.changePrice();
      },
      getSale() {
          let str = window.location.pathname
          let res = str.split("/")

          axios.get(`../../api/sales/get-sale/${res[4]}`)
            .then(response => {
                this.sale = response.data
                this.getPayments(this.sale.id)
            })
            .catch(error => {
                this.$toasted.global.error('Venta no encontrada!')
                setTimeout(function(){ location.href = '../../sales' }, 3000);
            })
            .then(() => {
              this.loading = false
          });
      },
      getTypePayments() {
          axios.get(`../../api/type_of_payments/all`).then(response => {
              this.type_of_payment = response.data
          })
          .catch(error => {
              this.errors = error.response.data.errors
          })
      },
      getPayments(id) {
          axios.get(`../../api/payments/get-payments-by-sale/` + id).then(response => {
              this.payments = response.data
              this.totalPayment = this.payments.reduce((acc, item) => acc + parseFloat(item.price), 0);

              let total = this.payments.reduce((acc, item) => acc + parseFloat(item.price), 0);
              this.payment.balance = parseFloat(this.sale.price - total);
          })
          .catch(error => {
              this.errors = error.response.data.errors
          })
      },
  }
}
</script>
