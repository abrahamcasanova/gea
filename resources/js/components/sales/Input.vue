<template>
    <div class="col-md-12 card-body px-0">
          <div class="row">
            <div class="form-group col-md-6">
                <label>Producto</label>
                 <multiselect
                  v-model="sale.product_id"
                  :options="products"
                  openDirection="bottom"
                  track-by="id"
                  @input="getPrice"
                  :custom-label="productName"
                  :class="{'border border-danger rounded': errors.product_id}">
                </multiselect>
                <div class="invalid-feedback" v-if="errors.first_name">{{errors.first_name[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Precio</label>
                <input type="number" class="form-control" :class="{'is-invalid': errors.price}" v-model="sale.price" placeholder="">
                <div class="invalid-feedback" v-if="errors.last_name">{{errors.last_name[0]}}</div>
            </div>
            <div class="form-group col-md-6">
              <label>Fecha limite de pago</label>
              <datepicker :bootstrap-styling="true" :language="es" :format="customFormatter" v-model="sale.date_payment_limit"></datepicker>
              <div class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Fecha de pagar al proveedor</label>
                <datepicker :bootstrap-styling="true" :language="es" :format="customFormatter" v-model="sale.date_payment_supplier"></datepicker>
                <div class="invalid-feedback" v-if="errors.phone">{{errors.phone[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Fecha de anticipo</label>
                <datepicker :bootstrap-styling="true" :language="es" :format="customFormatter" v-model="sale.date_advance"></datepicker>
                <div class="invalid-feedback" v-if="errors.cellphone">{{errors.cellphone[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Recordatorio</label>
                 <multiselect
                  v-model="sale.schedule"
                  :options="schedules"
                  openDirection="bottom"
                  track-by="id"
                  label="name"
                  :class="{'border border-danger rounded': errors.product_id}">
                </multiselect>
                <div class="invalid-feedback" v-if="errors.first_name">{{errors.first_name[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Importe a cobrar</label>
                <vue-numeric class="form-control"  :class="{'is-invalid': errors.amount_receivable}" currency="$" separator="," :precision="2" v-model="sale.amount_receivable"></vue-numeric>
                <div class="invalid-feedback" v-if="errors.amount_receivable">{{errors.amount_receivable[0]}}</div>
            </div>
          </div>
          <div>
              <span class="h3 text-success text-uppercase font-weight-bold" >
                  <i class="fa-md fas fa-bed text-dark"></i> Habitaciones
                </span>
              <v-divider inset></v-divider>  
          </div>
          
          <div class="row">
            <div class="form-group col-md-6">
                <label>Sencilla</label>
                <input type="number" class="form-control" :class="{'is-invalid': errors.simple_room}" v-model="sale.simple_room" placeholder="">
                <div class="invalid-feedback" v-if="errors.simple_room">{{errors.simple_room[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Doble</label>
                <input type="number" class="form-control" :class="{'is-invalid': errors.double_room}" v-model="sale.double_room" placeholder="">
                <div class="invalid-feedback" v-if="errors.double_room">{{errors.double_room[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Triple</label>
                <input type="number" class="form-control" :class="{'is-invalid': errors.triple_room}" v-model="sale.triple_room" placeholder="">
                <div class="invalid-feedback" v-if="errors.triple_room">{{errors.triple_room[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Cuádruple</label> 
                <input type="number" class="form-control" :class="{'is-invalid': errors.quadruple_room}" v-model="sale.quadruple_room" placeholder="">
                <div class="invalid-feedback" v-if="errors.quadruple_room">{{errors.quadruple_room[0]}}</div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
                <label>Proveedor</label>
                <multiselect
                  v-model="sale.supplier_id"
                  :options="suppliers"
                  openDirection="bottom"
                  track-by="id"
                  label="name"
                  :class="{'border border-danger rounded': errors.supplier_id}">
                </multiselect>
                <div class="invalid-feedback" v-if="errors.supplier_id">{{errors.supplier_id[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Precio/Tarifa neta</label>
                <vue-numeric class="form-control"  :class="{'is-invalid': errors.rate_price}" currency="$" separator="," :precision="2" v-model="sale.rate_price"></vue-numeric>
                <div class="invalid-feedback" v-if="errors.rate_price">{{errors.rate_price[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Confirmación</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.confirmation}" v-model="sale.confirmation" placeholder="">
                <div class="invalid-feedback" v-if="errors.confirmation">{{errors.confirmation[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <a style="margin-top: 30px;" class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="createSale">
                  <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
                  <span class="ml-1 v-else">Generar <i class="fas fa-save"></i></span>
                </a>
            </div>
          </div>
    </div>
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
  data () {
    return {
      sale: {
        events:[]
      },
      initialData: [],
      suppliers:[],
      submiting:false,
      products: [],
      schedules:[
          { id: 'SEMANAL', name: 'CADA SEMANA' },
          { id: 'QUINCENAL', name: 'CADA QUINCENA' },
          { id: 'MENSUAL', name: 'CADA MES' },
      ],
      errors:{},
      es: es,
    }
  },
  props: ['quote_id','quote_sale'],
  watch:{
    'quote_id'(){
        this.$emit('update:quote_id',this.quote_id)
        this.reset();
        this.getProductsByQuote();
    },
    'quote_sale'(){
        this.$emit('update:quote_sale',this.quote_sale)
    }
  },
  mounted () {
    this.csrf = window.Laravel.csrfToken;
    this.getSuppliers();
  },
  methods: {
      productName ({ product }) {
        return `${product['name']}`
      },
      reset() {
          this.sale = {};
      },
      createSale(){
          var now = moment(this.sale.date_advance);
          var then = moment(this.sale.date_payment_limit);
          var then_compare = moment(this.sale.date_payment_limit).format('YYYY-MM-DD');
          var events = [];
          var last_date = null;
          if(!this.sale.schedule){
              swal("Favor de seleccionar recordatorio..", {
                  icon: 'error',
                  buttons: false,
                  timer: 2000,
              });
              return false;
          }

          switch(this.sale.schedule.id) {
            case 'SEMANAL':
              var week_date = then.diff(now, 'week');
              for (var i = 0; i <= week_date -1; i++) {
                 let date = now.add(1,'week').format('YYYY-MM-DD');
                 if(then_compare != date){
                    events.push({
                      i: i,
                      details: 'Fecha de abono cliente: ' + this.quote_sale.customer_order.customer.full_name,
                      date: date,
                      open: false,
                      type: 'info',
                      title: 'Abono ' + this.quote_sale.customer_order.customer.full_name
                    });
                 }
              }
              break;
            case 'QUINCENAL':
              var week_date = then.diff(now, 'week');
              week_date = parseInt(week_date/2);
              console.log(week_date)
              for (var i = 0; i <= week_date -1; i++) {
                 let date = now.add(2,'week').format('YYYY-MM-DD');
                 if(then_compare != date){
                    events.push({
                      i: i,
                      details: 'Fecha de abono cliente: ' + this.quote_sale.customer_order.customer.full_name,
                      date: date,
                      open: false,
                      type: 'info',
                      title: 'Abono ' + this.quote_sale.customer_order.customer.full_name
                    });
                 }
              }
              break;
            case 'MENSUAL':
              var week_date = then.diff(now, 'month');
              for (var i = 0; i <= week_date -1; i++) {
                 let date = now.add(1,'month').format('YYYY-MM-DD');
                 if(then_compare != date){
                    events.push({
                      i: i,
                      details: 'Fecha de abono cliente: ' + this.quote_sale.customer_order.customer.full_name,
                      date: date,
                      open: false,
                      type: 'info',
                      title: 'Abono ' + this.quote_sale.customer_order.customer.full_name
                    });
                 }
              }
              break;
          }

          events.push({
              i: events.length + 1,
              details: 'Fecha limite de pago cliente: ' + this.quote_sale.customer_order.customer.full_name 
              + ' Importe a cobrar: $ ' + this.sale.amount_receivable,
              date: then_compare,
              open: false,
              type: 'warning',
              title: 'Abono ' + this.quote_sale.customer_order.customer.full_name
          });

          events.push({
              i: events.length + 1,
              details: 'Fecha limite de pago proveedor, Folio: ' + this.quote_sale.id,
              date: moment(this.sale.date_payment_supplier).format('YYYY-MM-DD'),
              open: false,
              type: 'danger',
              title: 'Abono ' + this.quote_sale.customer_order.customer.full_name
          });

          this.sale.events = events

          axios.post(`./api/sales/store`, this.sale).then(response => {
              console.log(response.data)
          })
          .catch(error => {
              this.errors = error.response.data.errors
          })
      },
      getSuppliers() {
          axios.get(`./api/suppliers/all`)
          .then(response => {
              this.suppliers = response.data
          })
          .catch(error => {
              this.$toasted.global.error('Error!')
          })
          .then(() => {
            this.loading = false
          })
      },
      changePrice(value){
          this.sale.price = parseFLoat(value);
      },
      getPrice(value){
          this.sale.price = value.price;
      },
      customFormatter(date) {
          return moment(date).format('YYYY/MM/DD');
      },
      getProductsByQuote() {
          axios.post(`./api/products/by-quote/` + this.quote_id).then(response => {
            return this.products = response.data
          })
          .catch(error => {
              this.errors = error.response.data.errors
          })
      },      
  }
}
</script>
