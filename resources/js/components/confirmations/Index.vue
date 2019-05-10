<template>
  <div class="container">
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <div class="card-header-actions mr-1">

      </div>
    </div>
    <v-app v-cloak>
    <v-card>
      <v-toolbar-title>
        <div class="h3 text-muted text-right mb-12">
          <h4 style="margin-right:10px;margin-top:10px;" class="text-muted text-uppercase font-weight-bold">
              Confirmación de pagos
              <i class="fa-lg fas fa-dollar-sign text-primary"></i>
          </h4>
        </div>
      </v-toolbar-title>
      <v-card-title>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Buscar.."
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="productDetailSales"
        :search="search"
        v-bind:pagination.sync="pagination"
      >
        <template v-slot:items="props">
          <td class="text-xs-center">{{ props.item.id }}</td>
          <td class="text-xs-left">{{ props.item.rate_price | currency }}</td>
          <td class="text-xs-center">{{ props.item.confirmation }}</td>
          <td class="text-xs-left">{{ props.item.supplier.name }}</td>
          <td class="text-xs-center">{{ props.item.date_payment_supplier }}</td>
          <td class="text-xs-center">
            <center>
              <span v-if="props.item.liquidate == 1" class='badge badge-primary'>SI</span>
              <span v-else class='badge badge-secondary'>NO</span>
            </center>
          </td>
          <td class="text-xs-center">{{ props.item.date_liquidate }}</td>
          <td class="text-xs-center">{{ props.item.created_at }}</td>

          <td class="justify-center layout px-0">
            <a href="#" @click="modalPaymentProductDetails(props.item.id)" v-if="$can('add-payments-confirmations')" class="card-header-action ml-1 text-muted">
              <i class="fa-lg fas fa-hand-holding-usd text-primary"></i>
            </a>
          </td>

        </template>
        <template v-slot:no-results>
          <v-alert :value="true" color="error" icon="warning" style="background-color:#ff5252!important;">
            Su búsqueda de "{{search}}" no encontró resultados.
          </v-alert>
        </template>
        <template v-slot:no-data>
          <v-alert :value="true" color="error" icon="warning" style="background-color:#ff5252!important;">
            Lo sentimos, no hay nada que mostrar aquí :(
          </v-alert>
        </template>
      </v-data-table>
      </v-card>
    </v-app>
    <!-- Modal Component -->
    <b-modal size="lg" id="modal2" ref="myModalRef" hide-footer title="Agregar pago">
      <div class="d-block">
        <div class="col-md-12 card-body px-0">
          <div class="row">
            <div class="form-group col-md-4">
                <label>Importe</label>
                <vue-numeric class="form-control"  :class="{'is-invalid': errors.amount}" currency="$" separator="," :precision="2" v-model="confirmation.amount"></vue-numeric>
                <div class="invalid-feedback" v-if="errors.amount">{{errors.amount[0]}}</div>
            </div>
            <div class="form-group col-md-4">
                <label>Fecha en que se recibe</label>
                <datepicker :bootstrap-styling="true" :language="es" :format="customFormatterLimit" v-model="confirmation.date_confirmation" :input-class="{'is-invalid': errors.date_confirmation}"></datepicker>
                <div class="invalid-feedback" v-if="errors.date_confirmation">{{errors.date_confirmation[0]}}</div>
            </div>
            <div class="form-group col-md-4">
                <label>Forma de pago</label>
                <multiselect
                  v-model="confirmation.type_of_payment"
                  :options="type_of_payment"
                  openDirection="bottom"
                  track-by="id"
                  label="name"
                  :class="{'border border-danger rounded': errors.type_of_payment}">
                </multiselect>
                <small class="form-text text-danger" v-if="errors.type_of_payment">{{errors.type_of_payment[0]}}</small>
            </div>
            <div class="form-group col-md-4">
                <label>Tipo de comprobante</label>
                <multiselect
                  v-model="confirmation.type_of_voucher"
                  :options="type_of_voucher"
                  openDirection="bottom"
                  track-by="name"
                  label="name"
                  :class="{'border border-danger rounded': errors.type_of_voucher}">
                </multiselect>
                <small class="form-text text-danger" v-if="errors.type_of_voucher">{{errors.type_of_voucher[0]}}</small>
            </div>
            <div class="form-group col-md-4">
                <v-text-field v-model="confirmation.number_voucher" :class="{'is-invalid': errors.number_voucher}" type="text" label="Folio"></v-text-field>
            </div>
            <div class="form-group col-md-12">
                <label>Nota</label>
                <b-form-textarea
                  v-model="confirmation.note"
                  placeholder="Nota..."
                  rows="6"
                  max-rows="6"
                ></b-form-textarea>
                <div class="invalid-feedback" v-if="errors.date_received">{{errors.date_received[0]}}</div>
            </div>
            <div class="form-group col-md-12">
                <label><strong>Pagos realizados</strong></label>
                <table class="table">
                  <thead>
                    <tr>
                      <th class="">
                        <a href="#" class="text-dark">Importe</a>
                      </th>
                      <th>
                        <a href="#" class="text-dark">Dia confirmado</a>
                      </th>
                      <th>
                        <a href="#" class="text-dark">Tipo de comprobante</a>
                      </th>
                      <th>
                        <a href="#" class="text-dark">Folio</a>
                      </th>
                      <th>
                        <a href="#" class="text-dark">Forma de pago</a>
                      </th>
                      <th>
                        <a href="#" class="text-dark">Agente</a>
                      </th>
                      <th>
                        <a href="#" class="text-dark">Nota</a>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                      <tr v-for="supplier_payment in supplierPayment.supplierPayments">
                          <td class="">{{ supplier_payment.amount | currency }}</td>
                          <td class="">{{ supplier_payment.date_confirmation}}</td>
                          <td class="">{{ supplier_payment.type_of_voucher}}</td>
                          <td class="">{{ supplier_payment.number_voucher}}</td>
                          <td class="">{{ supplier_payment.type_of_payment ? supplier_payment.type_of_payment.name:''}}</td>
                          <td class="">{{ supplier_payment.user.name}}</td>
                          <td class="">{{ supplier_payment.note}}</td>
                      </tr>
                      <tr>
                          <td class=""><strong>Total: </strong>{{total | currency }}</td>
                          <td class=""><strong>Saldo: </strong>{{ balance | currency }}</td>
                      </tr>
                  </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
      <b-button class="mt-3" variant="outline-primary" block @click="saveConfirmPayment()">Confirmar</b-button>
    </b-modal>
    <!-- Modal Component -->
    <!--
    <b-modal size="lg" id="modal2" ref="myModalRef2" hide-footer title="Confirmar">
      <div class="d-block">
        <div class="col-md-12 card-body px-0">
          <div class="row">
            <div class="form-group col-md-6">
                <label>Corte</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.break}" v-model="payment.break" placeholder="">
                <div class="invalid-feedback" v-if="errors.break">{{errors.break[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Fecha en que se recibe</label>
                <datepicker :bootstrap-styling="true" :language="es" :format="customFormatterLimit" v-model="payment.date_received" :input-class="{'is-invalid': errors.date_payment_limit}"></datepicker>
                <div class="invalid-feedback" v-if="errors.date_received">{{errors.date_received[0]}}</div>
            </div>
            <div class="form-group col-md-12">
                <label>Nota</label>
                <b-form-textarea
                  v-model="payment.note"
                  placeholder="Nota..."
                  rows="6"
                  max-rows="6"
                ></b-form-textarea>
                <div class="invalid-feedback" v-if="errors.date_received">{{errors.date_received[0]}}</div>
            </div>
          </div>
        </div>
      </div>
      <b-button class="mt-3" variant="outline-primary" block @click="saveConfirm()">Confirmar</b-button>
    </b-modal>
    -->
  </div>
</template>

<script>
let csrf_token = $('meta[name="csrf-token"]').attr('content');
import {en, es} from 'vuejs-datepicker/dist/locale'
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';

export default {
  components: {
      Datepicker,
  },
  data () {
    return {
      customers: [{
          first_name: '',
          last_name: ''}
      ],
      docs: [],
      sum:0,
      supplierPayment:[],
      supplierPaymentReduce:[],
      payment:[],
      confirmation:{},
      errors:{},
      type_of_payment: [],
      type_of_voucher: [
        {'name':'Factura'},
        {'name':'Recibo'},
      ],
      es:es,
      search:null,
      path_pdf:null,
      headers: [
        { value: 'id', align: 'left', text: 'Folio' },
        { value: 'rate_price',text:'Precio Tarifa',sortable: true,},
        { value: 'liquidate',text:'Confirmación', sortable: true },
        { value: 'supplier.name', sortable: true,text:'Proveedor' },
        { value: 'date_payment_supplier', sortable: true,text:'Fecha pago de proveedor' },
        { value: 'liquidate', sortable: true,text:'Liquidado' },
        { value: 'date_liquidate', sortable: true,text:'Fecha Liquidado' },
        { value: 'created_at', sortable: true,text:'Registrado' },
        { value: 'actions', sortable: false,text:'Acciones' },
      ],
      productDetailSales:[],
      pagination: {'sortBy': 'id', 'descending': true, rowsPerPage: 15 },
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
      submitingDestroy: false,
    }
  },
  computed: {
    fullName: function() {
        return this.customers.map(function(customer) {
            return customer.first_name + ' ' + customer.last_name;
        });
    },
    total: function(){
        let vm = this;
        return this.supplierPaymentReduce.reduce(function(prev, item){
            return vm.sum += item.amount;
        },0);
     },
     balance: {
        get() { return this.balance = (this.supplierPayment.rate_price - this.total); },
        set(newValue) {  }
     }
  },
  mounted () {
    this.getProductDetailSales();
    this.getTypePayments();
  },
  methods: {
    saveConfirmPayment(){
        axios.post(`./api/supplier_payments/store`,this.confirmation).then(response => {
            swal("Pago generada correctamente!!", {
              icon: 'success',
              buttons: false,
            });
            setTimeout(function(){
                location.href = './confirmations'
            }, 1500);
        })
        .catch(error => {
            this.errors = error.response.data.errors
        })
    },
    customFormatterLimit(date) {
        this.confirmation.date_confirmation = moment(this.confirmation.date_confirmation).format('YYYY/MM/DD');
        return moment(this.confirmation.date_confirmation).format('YYYY/MM/DD');
    },
    hideModal() {
        this.$refs.myModalRef.hide()
    },
    modalPaymentProductDetails(id) {
        this.confirmation.product_detail_sale_id =  id;
        axios.post(`./api/supplier_payments/get-payments/` + id).then(response => {
            this.supplierPayment = response.data
            this.supplierPaymentReduce = response.data.supplierPayments
            this.$refs.myModalRef.show();
        })
        .catch(error => {
            this.errors = error.response.data.errors
        })
    },
    getTypePayments() {
        axios.get(`./api/type_of_payments/all`).then(response => {
            this.type_of_payment = response.data
        })
        .catch(error => {
            this.errors = error.response.data.errors
        })
    },
    getProductDetailSales () {
      this.loading = true
      this.productDetailSales = []

      axios.post(`./api/confirmations/getDetails`, this.filters)
      .then(response => {
        this.productDetailSales = response.data
        this.loading = false
      })
    },
    destroy (customerId) {
      if (!this.submitingDestroy) {
        this.submitingDestroy = true
        swal({
          title: "¿Esta seguro?",
          text: "Una vez eliminado, no podrá recuperar este cliente!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete(`./api/customers/${customerId}`)
            .then(response => {
                this.$toasted.global.error('Deleted customer!')
                location.href = './customers'
            })
            .catch(error => {
                this.errors = error.response.data.errors ? error.response.data.errors:error.response.data.message
                this.$toasted.error(error.response.data.errors ? error.response.data.errors:error.response.data.message,{
                    duration: 5000
                });
            })
          }
          this.submitingDestroy = false
        })
      }
    },
  }
}
</script>
