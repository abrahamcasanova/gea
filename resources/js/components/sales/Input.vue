<template>
    <div class="col-md-12 card-body px-0">
          <div class="row">
            <div class="form-group col-md-12">
                <label>Destino del viaje</label>
                <multiselect
                  v-model="quote_sale.travel_destination"
                  :options="items"
                  :multiple="true"
                  openDirection="bottom"
                  track-by="id"
                  placeholder="Seleccionar..."
                  label="name"
                  :class="{'border border-danger rounded': errors.roles}">
                </multiselect>
            </div>
            <v-spacer></v-spacer>
            <div class="form-group col-md-6">
                <label>Producto</label>
                 <multiselect
                  v-model="sale.product_id"
                  :options="products"
                  openDirection="bottom"
                  track-by="id"
                  @input="getPrice"
                  :custom-label="productName"
                  :class="{'input border border-danger rounded': errors.product_id}">
                </multiselect>
                <div class="invalid-feedback" v-if="errors.product_id">{{errors.product_id[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Precio ({{ quote_sale.currency | capitalize({ onlyFirstLetter: true }) }})</label>
                <input type="number" class="form-control" :class="{'is-invalid': errors.price}" v-model="sale.price" placeholder="">
                <div class="invalid-feedback" v-if="errors.price">{{errors.price[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Confirmación</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.confirmation}" v-model="sale.confirmation" placeholder="">
                <div class="invalid-feedback" v-if="errors.confirmation">{{errors.confirmation[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Fecha de pagar al proveedor</label>
                <datepicker :bootstrap-styling="true" :language="es" :format="customFormatterSupplier" v-model="sale.date_payment_supplier" :input-class="{'is-invalid': errors.date_payment_supplier}"></datepicker>
                <div class="invalid-feedback" v-if="errors.date_payment_supplier">{{errors.date_payment_supplier[0]}}</div>
            </div>
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
            <div class="form-group col-md-12">
              <v-btn style="margin-top: 25px"
                :loading="isLoading"
                :disabled="isLoading"
                color="blue"
                @click="saveDetail()"
                class="white--text"
                >
                Agregar
                <v-icon right dark>add_circle</v-icon>
              </v-btn>
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
            <v-spacer></v-spacer>
            <div class="form-group col-md-6">
              <label>Fecha limite de pago</label>
              <datepicker :bootstrap-styling="true" :language="es" :format="customFormatterLimit" v-model="sale.date_payment_limit" :input-class="{'is-invalid': errors.date_payment_limit}"></datepicker>
              <div class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Fecha de anticipo</label>
                <datepicker :bootstrap-styling="true" :language="es" :format="customFormatterAdvance" v-model="sale.date_advance" :input-class="{'is-invalid': errors.date_payment_supplier}"></datepicker>
                <div class="invalid-feedback" v-if="errors.date_advance">{{errors.date_advance[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Recordatorio</label>
                 <multiselect
                  v-model="sale.schedule"
                  :options="schedules"
                  openDirection="bottom"
                  track-by="id"
                  placeholder="Ninguno"
                  label="name"
                  :class="{'border border-danger rounded': errors.schedule}">
                </multiselect>
                <div class="invalid-feedback" v-if="errors.schedule">{{errors.schedule[0]}}</div>
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
import { uuid } from 'vue-uuid';
import moment from 'moment';

export default {
  components: {
      Datepicker,
  },
  data () {
    return {
      sale: {
        events:[],
        travel_destination:[]
      },
      uuid: uuid.v1(),
      initialData: [],
      productsDetailSales: {
        product:{name:null},
        supplier:{name:null}
      },
      suppliers:[],
      items:[],
      submitingDestroy: false,
      isLoading: false,
      search: null,
      submiting:false,
      products: [],
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
      errors:{},
      es: es,
    }
  },
  props: ['quote_id','quote_sale','type','saleEdit'],
  watch:{
    'quote_id'(){
        this.$emit('update:quote_id',this.quote_id)
        this.reset();
        this.getProductsByQuoteSpecial();
        this.getDetails();    

    },
    'quote_sale'(){
        this.$emit('update:quote_sale',this.quote_sale)
    },
    'type'(){
        this.$emit('update:type',this.type)
    },
    'saleEdit'(){
        this.$emit('update:saleEdit',this.saleEdit)
        this.sale = this.type == "edit" ?  this.saleEdit: this.sale;
    }
  },
  mounted () {
    this.csrf = window.Laravel.csrfToken;
    let url = this.type == "edit" ?  '../../api/suppliers/all':'./api/suppliers/all';
    this.getSuppliers(url);
    this.getDestinations();
  },
  methods: {
      productName ({ product }) {
        return `${product['name']}`
      },
      reset() {
          this.sale = {};
      },
      getDestinations(){
        // Lazily load input items
        axios.get('./api/destinations/all')
        .then(response => {
            this.items = response.data
        })
        .catch(err => {
            console.log(err)
        })
        .finally(() => (this.isLoading = false))
      },
      createSale(){
          var now = moment(this.sale.date_advance);
          var advance = moment(this.sale.date_advance).format('YYYY-MM-DD');
          var then = moment(this.sale.date_payment_limit);
          var then_compare = moment(this.sale.date_payment_limit).format('YYYY-MM-DD');
          var events = [];
          var last_date = null;
          if(this.sale.schedule){
              
              switch(this.sale.schedule.id) {
              case 'SEMANAL':
                var week_date = then.diff(now, 'week');
                for (var i = 0; i <= week_date -1; i++) {
                   let date = now.add(1,'week').format('YYYY-MM-DD');
                   if(then_compare != date){
                      events.push({
                        id: uuid.v1(),
                        details: 'Fecha de abono cliente: ' + this.quote_sale.customer_order.customer.full_name +
                        ' Importe a cobrar: $ ' + this.sale.amount_receivable + '. Folio: ' + this.quote_sale.id,
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
                        id: uuid.v1(),
                        details: 'Fecha de abono cliente: ' + this.quote_sale.customer_order.customer.full_name +
                        ' Importe a cobrar: $ ' + this.sale.amount_receivable + '. Folio: ' + this.quote_sale.id,
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
                        id: uuid.v1(),
                        details: 'Fecha de abono cliente: ' + this.quote_sale.customer_order.customer.full_name +
                        ' Importe a cobrar: $ ' + this.sale.amount_receivable + '. Folio: ' + this.quote_sale.id,
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
                id: uuid.v1(),
                details: 'Fecha de anticipo cliente: ' + this.quote_sale.customer_order.customer.full_name,
                date: advance,
                open: false,
                type: 'success',
                title: 'Pago anticipo ' + this.quote_sale.customer_order.customer.full_name + ' Folio: ' + this.quote_sale.id
            });

            events.push({
                id: uuid.v1(),
                details: 'Fecha limite de pago cliente: ' + this.quote_sale.customer_order.customer.full_name 
                + ' Importe a cobrar: $ ' + this.sale.price,
                date: then_compare,
                open: false,
                type: 'warning',
                title: 'Pago Limite ' + this.quote_sale.customer_order.customer.full_name + ' Folio: ' + this.quote_sale.id
            });

            events.push({
                id: uuid.v1(),
                details: 'Fecha limite de pago proveedor, Folio: ' + this.quote_sale.id,
                date: moment(this.sale.date_payment_supplier).format('YYYY-MM-DD'),
                open: false,
                type: 'danger',
                title: 'Pago Proveedor ' + this.quote_sale.customer_order.customer.full_name + ' Folio: ' + this.quote_sale.id
            });

            this.sale.events = events
          }
          
          this.sale.quote_id = this.quote_id;
          this.sale.destinations = this.quote_sale.travel_destination ? this.quote_sale.travel_destination:null;

          swal({
            title: "¿Esta seguro?",
            text: "Una vez guardado, se necesitaran permisos especiales para modificar!",
            icon: "warning",
            buttons: true,
            dangerMode: false,
          })
          .then((willCreate) => {
            if (willCreate) {
              swal("Espere un momento porfavor..", {
                  icon: 'info',
                  buttons: false,
              });
              axios.post(`./api/sales/store`, this.sale).then(response => {
                swal("Venta generada correctamente!!", {
                  icon: 'success',
                  buttons: false,
                });
                setTimeout(function(){
                    location.href = './quotes'
                }, 1500);

              })
              .catch(error => {
                  swal("Error!", {
                    icon: 'error',
                    buttons: false,
                    timer: 1500
                  });
                  this.errors = error.response.data.errors
              })
            }
            this.submitingDestroy = false
          })
      },
      getSuppliers(url) {
          axios.get(url)
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
      getDetails() {
          axios.post('./api/product_detail_sales/get-by-quote',{
            quote_id: this.quote_id
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
      changePrice(value){
          this.sale.price = parseFLoat(value);
      },
      getPrice(value){
          this.sale.quote_detail_id = value.id;
          this.sale.price = value.price;
      },
      customFormatterSupplier(date) {
          this.sale.date_payment_supplier = moment(date).format('YYYY/MM/DD');
          return moment(date).format('YYYY/MM/DD');
      },
      customFormatterLimit(date) {
          this.sale.date_payment_limit = moment(date).format('YYYY/MM/DD');
          return moment(date).format('YYYY/MM/DD');
      },
      customFormatterAdvance(date) {
          this.sale.date_advance = moment(date).format('YYYY/MM/DD');
          return moment(date).format('YYYY/MM/DD');
      },
      saveDetail(){
          this.sale.quote_id = this.quote_id;
          let obj = this.sale
          axios.post(`./api/product_detail_sales/store`,
            obj
          ).then(response => {
              swal("Producto agregado correctamente!!", {
                  icon: 'success',
                  buttons: false,
                  timer: 3000,
              });
              this.sale.product_id = null
              this.sale.price = 0
              this.sale.confirmation = null
              this.sale.date_payment_supplier = null
              this.sale.supplier_id = null
              this.sale.rate_price = 0

              return this.productsDetailSales = response.data
            })
            .catch(error => {
                this.errors = error.response.data.errors
          })
      },
      getProductsByQuoteSpecial() {
          axios.post(`./api/products/by-quote-special/` + this.quote_id).then(response => {
            return this.products = response.data
          })
          .catch(error => {
              this.errors = error.response.data.errors
          })
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
      }     
  }
}
</script>
