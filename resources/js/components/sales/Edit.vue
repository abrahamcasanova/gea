<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Editando Venta</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="update">
              <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
              <i class="fas fa-check" v-else></i>
              <span class="ml-1">Actualizar</span>
            </a>
          </div>
        </div>
        <div class="col-md-12 card-body px-0">
          <div class="row">
            <div style="display: none;" class="form-group col-md-6">
                <label>Producto</label>
                 <multiselect
                  :options="products"
                  openDirection="bottom"
                  track-by="id"
                  label="name"
                  @input="getPrice"
                  :class="{'border border-danger rounded': errors.product_id}">
                </multiselect>
                <div class="invalid-feedback" v-if="errors.first_name">{{errors.first_name[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Precio MXN</label>
                <input type="number" class="form-control" :class="{'is-invalid': errors.price}" v-model="sale.price" placeholder="">
                <div class="invalid-feedback" v-if="errors.last_name">{{errors.last_name[0]}}</div>
            </div>
            <div class="form-group col-md-6">
              <label>Fecha limite de pago</label>
              <datepicker :bootstrap-styling="true" :language="es" :format="customFormatterLimit" v-model="sale.date_payment_limit" :input-class="{'is-invalid': errors.date_payment_limit}"></datepicker>
              <small class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</small>
            </div>
            <div class="form-group col-md-6">
                <label>Fecha de pagar al proveedor</label>
                <datepicker :bootstrap-styling="true" :language="es" :format="customFormatterSupplier" v-model="sale.date_payment_supplier" :input-class="{'is-invalid': errors.date_payment_supplier}"></datepicker>
                <small class="invalid-feedback" v-if="errors.date_payment_supplier">{{errors.date_payment_supplier[0]}}</small>
            </div>
            <div class="form-group col-md-6">
                <label>Fecha de anticipo</label>
                <datepicker :bootstrap-styling="true" :language="es" :format="customFormatterAdvance" :input-class="{'is-invalid': errors.date_advance}" v-model="sale.date_advance"></datepicker>
                <small class="invalid-feedback" v-if="errors.cellphone">{{errors.cellphone[0]}}</small>
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
            <div class="form-group col-md-6">
                <label>Tipo de moneda</label>
                 <multiselect
                  v-model="sale.quote.currency"
                  :options="currencies"
                  openDirection="bottom"
                  track-by="id"
                  placeholder="Moneda"
                  label="name"
                  :class="{'border border-danger rounded': errors.currency}">
                </multiselect>
                <div class="invalid-feedback" v-if="errors.currency">{{errors.currency[0]}}</div>
            </div>
            <div class="form-group col-md-12">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th class="">
                      <a href="#" class="text-dark">PRODUCTO</a>
                      <i class="mr-1 fas"></i>
                    </th>
                    <th>
                      <a href="#" class="text-dark">PRECIO</a>
                      <i class="mr-1 fas"></i>
                    </th>
                    <th>
                      <a href="#" class="text-dark">PRECIO TARIFA</a>
                      <i class="mr-1 fas"></i>
                    </th>
                    <th>
                      <a href="#" class="text-dark">CONFIRMACIÓN</a>
                      <i class="mr-1 fas" ></i>
                    </th>
                    <th>
                      <a href="#" class="text-dark">PROVEEDOR</a>
                      <i class="mr-1 fas"></i>
                    </th>
                    <th>
                      <a href="#" class="text-dark">FECHA PAGO PROVEEDOR</a>
                      <i class="mr-1 fas"></i>
                    </th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="productDetailSale in productsDetailSales">
                    <td class="">{{productDetailSale.product ? productDetailSale.product.name:null}}</td>
                    <td class="">{{ productDetailSale.price | currency  }}</td>
                    <td class="">{{ productDetailSale.rate_price | currency  }}</td>
                    <td class="">{{productDetailSale.confirmation}}</td>
                    <td class="">{{productDetailSale.supplier ? productDetailSale.supplier.name:null}}</td>
                    <td class="">{{productDetailSale.date_payment_supplier}}</td>
                    <td class="">
                      <a class="card-header-action ml-1" href="#" :disabled="submitingDestroy"  @click="destroyProductDetailSale(productDetailSale.id)">
                          <i class="fas fa-spinner fa-spin" v-if="submitingDestroy"></i>
                          <i class="far fa-trash-alt" v-else style="color:red"></i>
                          <span class="d-md-down-none ml-1"></span>
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="form-group col-md-12">
                <label>Destino del viaje</label>
                <multiselect
                  v-model="sale.travel_destination"
                  :options="items"
                  :multiple="true"
                  openDirection="bottom"
                  track-by="id"
                  placeholder="Seleccionar..."
                  label="name"
                  :class="{'border border-danger rounded': errors.roles}">
                </multiselect>
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
                  openDirection="top"
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
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
let csrf_token = $('meta[name="csrf-token"]').attr('content');
import Datepicker from 'vuejs-datepicker';
import {en, es} from 'vuejs-datepicker/dist/locale'
import { uuid } from 'vue-uuid';
import moment from 'moment';

export default {
  components: {
      Datepicker,
  },
  data () {
    return {
      details: {
          customer: {
            full_name:null
          }
      },
      docs: [],
      saleEdit: {},
      sale:{
          events:[],
          quote:{
            currency:null
          }
      },
      events:[],
      schedules:[
          { id: 'SEMANAL', name: 'CADA SEMANA' },
          { id: 'QUINCENAL', name: 'CADA QUINCENA' },
          { id: 'MENSUAL', name: 'CADA MES' },
      ],
      currencies:[
          { id: 'NACIONAL', name: 'NACIONAL' },
          { id: 'DOLARES', name: 'DOLARES' },
          { id: 'EUROS', name: 'EUROS' },
      ],
      productsDetailSales: {
        product:{name:null},
        supplier:{name:null}
      },
      errors:{},
      es: es,      quoteId: null,
      type: "edit",
      quoteSale: [],
      items:[],
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
      path_pdf:'',
      loading: true,
      submiting: false,
      submitingDestroy: false,
      products:[],
      suppliers:[],
    }
  },
  computed: {

  },
  mounted () {
    this.getSale();
    this.getDestinations();
    this.getSuppliers();
  },
  methods: {
    getDestinations(){
        // Lazily load input items
        axios.get('../../api/destinations/all')
        .then(response => {
            this.items = response.data
        })
        .catch(err => {
            console.log(err)
        })
        .finally(() => (this.isLoading = false))
    },
    getDetails() {
        console.log(this.sale.quote_id)
        axios.post('../../api/product_detail_sales/get-by-quote',{
          quote_id: this.sale.quote_id
        })
        .then(response => {
            this.productsDetailSales = response.data
        })
        .catch(error => {
            this.$toasted.global.error('Error!')
        })
        .then(() => {
          this.loading = false
        })
    },
    update () {
      if (!this.submiting) {
          var now = moment(this.sale.date_advance);
          var advance = moment(this.sale.date_advance).format('YYYY-MM-DD');
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

          switch(this.sale.schedule[0].id || this.sale.schedule.id) {
            case 'SEMANAL':
              var week_date = then.diff(now, 'week');
              for (var i = 0; i <= week_date -1; i++) {
                 let date = now.add(1,'week').format('YYYY-MM-DD');
                 if(then_compare != date){
                    events.push({
                      id: uuid.v1(),
                      details: 'Fecha de abono cliente: ' + this.sale.quote.customer_order.customer.full_name +
                      ' Importe a cobrar: $ ' + this.sale.amount_receivable + '. Folio: ' + this.sale.quote.id,
                      date: date,
                      open: false,
                      type: 'info',
                      title: 'Abono ' + this.sale.quote.customer_order.customer.full_name
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
                      id: uuid.v1(),
                      details: 'Fecha de abono cliente: ' + this.sale.quote.customer_order.customer.full_name +
                      ' Importe a cobrar: $ ' + this.sale.amount_receivable + '. Folio: ' + this.sale.quote.id,
                      date: date,
                      open: false,
                      type: 'info',
                      title: 'Abono ' + this.sale.quote.customer_order.customer.full_name
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
                      id: uuid.v1(),
                      details: 'Fecha de abono cliente: ' + this.sale.quote.customer_order.customer.full_name +
                      ' Importe a cobrar: $ ' + this.sale.amount_receivable + '. Folio: ' + this.sale.quote.id,
                      date: date,
                      open: false,
                      type: 'info',
                      title: 'Abono ' + this.sale.quote.customer_order.customer.full_name
                    });
                 }
              }
              break;
          }

          events.push({
              id: uuid.v1(),
              details: 'Fecha de anticipo cliente: ' + this.sale.quote.customer_order.customer.full_name,
              date: advance,
              open: false,
              type: 'success',
              title: 'Pago anticipo ' + this.sale.quote.customer_order.customer.full_name + ' Folio: ' + this.sale.quote.id
          });

          events.push({
              id: uuid.v1(),
              details: 'Fecha limite de pago cliente: ' + this.sale.quote.customer_order.customer.full_name 
              + ' Importe a cobrar: $ ' + this.sale.price,
              date: then_compare,
              open: false,
              type: 'warning',
              title: 'Pago Limite ' + this.sale.quote.customer_order.customer.full_name + ' Folio: ' + this.sale.quote.id
          });

          if(this.productsDetailSales.length > 0 ){
            for (var i = this.productsDetailSales.length - 1; i >= 0; i--) {
              events.push({
                id: uuid.v1(),
                details: 'Fecha limite de pago proveedor, Folio: ' + this.sale.quote.id,
                date: moment(this.productsDetailSales[i].date_payment_supplier).format('YYYY-MM-DD'),
                open: false,
                type: 'danger',
                title: 'Pago Proveedor ' + this.sale.quote.customer_order.customer.full_name + ' Folio: ' + this.sale.quote.id
              });
            }
          }

          this.sale.quote_id = this.quote_id;
          this.sale.events = events
          
          this.submiting = true
          axios.put(`../../api/sales/update/${this.sale.id}`, this.sale)
          .then(response => {
              this.$toasted.global.error('Venta actualizada!')
              location.href = '../../sales'
          })
          .catch(error => {
            this.errors = error.response.data.errors
            this.submiting = false
          })
      }
    },
    getPrice(value){
          this.sale.quote_detail_id = value.id;
          this.sale.price = value.price;
    },
    customFormatterSupplier(date) {
          this.sale.date_payment_supplier = moment(this.sale.date_payment_supplier).format('YYYY/MM/DD');
          return moment(this.sale.date_payment_supplier).format('YYYY/MM/DD');
    },
    customFormatterLimit(date) {
          this.sale.date_payment_limit = moment(this.sale.date_payment_limit).format('YYYY/MM/DD');
          return moment(this.sale.date_payment_limit).format('YYYY/MM/DD');
    },
    customFormatterAdvance(date) {
          this.sale.date_advance = moment(this.sale.date_advance).format('YYYY/MM/DD');
          return moment(this.sale.date_advance).format('YYYY/MM/DD');
    },
    destroyProductDetailSale(productDetailSale){
      if (!this.submitingDestroy) {
        this.submitingDestroy = true
        swal({
          title: "¿Esta seguro?",
          text: "Una vez eliminado, no podrá recuperar este producto!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete(`./api/product_detail_sales/${productDetailSale}`)
            .then(response => {
                this.$toasted.global.error('producto eliminado!')
                this.getDetails();
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
    getSuppliers() {
          axios.get('../../api/suppliers/all')
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
    sendMail(saleId){
        swal({
          title: "¿Estás seguro?",
          text: "Desea enviar el correo",
          icon: "warning",
          buttons: ['Cancelar','Enviar'],
          dangerMode: false,
        })
        .then((willSented) => {
          if (willSented) {
              swal("Enviando..", {
                  icon: 'info',
                  buttons: false,
              });

              axios.post(`./api/sales/send-sale`,{
                id: saleId
              })
              .then(response => {
                  //si es error avisar que no tiene confirmacion
                  swal("Enviado correctamente!", {
                    icon: 'success',
                    buttons: false,
                  });
              })
              .catch(error => {
                  this.$toasted.global.error('Cotización no encontrada!')
              })
              .then(() => {
                this.loading = false
              })
          }
          this.submitingDestroy = false
        })
    },
    getSale () {
      this.loading = true
      let str = window.location.pathname
      let res = str.split("/")
      
      axios.get(`../../api/sales/get-sale/` + res[3])
        .then(response => {
            this.sale = response.data;
            if(response.data.products){
              this.products = this.sale.product_id = [response.data.product];
            }
            this.sale.schedule = [{'id': response.data.schedule, 'name': response.data.schedule}];
            if(response.data.supplier){
              this.sale.supplier_id = [response.data.supplier];  
            }
            
            this.sale.quote.currency = [{'id': response.data.quote.currency, 'name': response.data.quote.currency}];

            this.getDetails();
        })
        .catch(error => {
            this.$toasted.global.error('Venta no encontrada!')
            location.href = '../../sales'
        })
        .then(() => {
          this.loading = false
        })
    },
    editSale (saleId) {
        location.href = `./sales/${saleId}/edit`
    },
    createQuote(orderId){
        axios.post(`./api/quotes/store`, {
          customer_order_id : orderId
        })
        .then(response => {
            Vue.toasted.success('Creado correctamente!')
            location.href = './quotes/' + response.data['id'] + '/quote'
        })
        .catch(error => {
          this.errors = error.response.data.errors
          this.submiting = false
        })
    }
  }
}
</script>
