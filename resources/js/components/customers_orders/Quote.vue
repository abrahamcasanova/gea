<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Nueva cotización</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="create">
              <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
              <i class="fas fa-check" v-else></i>
              <span class="ml-1">Guardar</span>
            </a>
          </div>
        </div>
        <div class="col-md-12 card-body px-0">
          <div class="row">
            <div class="form-group col-md-2">
                <label>Folio</label>
                <input type="text" disabled readonly class="form-control" :class="{'is-invalid': errors.id}" v-model="quote.id" placeholder="..">
            </div>
            <div class="form-group col-md-6">
                <label>Cliente</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.last_name}" v-model="quote.customer_order.customer.full_name" placeholder="Casanova Ortiz">
                <div class="invalid-feedback" v-if="errors.last_name">{{errors.last_name[0]}}</div>
            </div>
            <div class="form-group col-md-4">
                <label>Fecha de la propuesta</label>
                <datepicker :bootstrap-styling="true" :language="es" :format="customFormatter" v-model="quote.proposal_date" :input-class="{'is-invalid': errors.proposal_date}"></datepicker>
                <div class="invalid-feedback" v-if="errors.proposal_date">{{errors.proposal_date[0]}}</div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-3">
                <label>Fecha de viaje</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.rfc}" v-model="quote.travel_date" placeholder="..." >
                <div class="invalid-feedback" v-if="errors.travel_date">{{errors.travel_date[0]}}</div>
            </div>
            <div class="form-group col-md-3">
                <label>Adultos</label>
                <input type="number" class="form-control" :class="{'is-invalid': errors.number_adults}" v-model="quote.number_adults" placeholder="...">
            </div>
            <div class="form-group col-md-3">
                <label>Menores</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.number_childs}" v-model="quote.number_childs" placeholder="...">
            </div>
            <div class="form-group col-md-3">
                <label>Tipo de moneda</label>
                 <multiselect
                  v-model="quote.currency"
                  :options="currencies"
                  openDirection="bottom"
                  track-by="id"
                  placeholder="Moneda"
                  label="name"
                  :class="{'border border-danger rounded': errors.currency}">
                </multiselect>
                <div class="invalid-feedback" v-if="errors.currency">{{errors.currency[0]}}</div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-3">
                <label>Utilidad</label>
                <vue-numeric class="form-control" :class="{'is-invalid': errors.markup}"
                currency="$" separator="," :precision="2" v-model="quote.markup"></vue-numeric>
            </div>
          </div>
          <div class="card">
              <div class="card-body">
                  <h5 class="card-title">Producto</h5>
                  <div class="row">
                    <div class="form-group col-md-3">
                        <label>Nombre</label>
                        <multiselect
                          v-model="quote_detail_add.product_id"
                          :options="products"
                          openDirection="bottom"
                          track-by="id"
                          label="name"
                          :class="{'border border-danger rounded': errors.product_id}">
                        </multiselect>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Descripción</label>
                        <input type="text" class="form-control" :class="{'is-invalid': errors.last_name}" v-model="quote_detail_add.description" placeholder="descripción">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Precio</label>
                        <vue-numeric class="form-control"  :class="{'is-invalid': errors.price}" currency="$" separator="," :precision="2" v-model="quote_detail_add.price"></vue-numeric>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Proveedor</label>
                        <multiselect
                          v-model="quote_detail_add.supplier_id"
                          :options="suppliers"
                          openDirection="bottom"
                          track-by="id"
                          label="name"
                          :class="{'border border-danger rounded': errors.supplier_id}">
                        </multiselect>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group offset-md-10 col-md-2">
                        <label>&nbsp;</label><br>
                        <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="addProduct">
                          <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
                          <i class="fas fa-check" v-else></i>
                          <span class="ml-1">Agregar</span>
                        </a>
                    </div>
                  </div>
                  <div class="row">
                      <div class="table-responsive">
                        <table class="table">
                          <thead>
                            <tr>
                              <th class="">
                                <a href="#" class="text-dark" @click.prevent="sort('product_id')">Nombre</a>
                                <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'product_id' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'product_id' && filters.orderBy.direction == 'desc'}"></i>
                              </th>
                              <th>
                                <a href="#" class="text-dark" @click.prevent="sort('category')">Categoria</a>
                                <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'category' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'category' && filters.orderBy.direction == 'desc'}"></i>
                              </th>
                              <th>
                                <a href="#" class="text-dark" @click.prevent="sort('price')">Precio</a>
                                <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'price' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'price' && filters.orderBy.direction == 'desc'}"></i>
                              </th>
                              <th>
                                <a href="#" class="text-dark" @click.prevent="sort('description')">Nota</a>
                                <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'description' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'description' && filters.orderBy.direction == 'desc'}"></i>
                              </th>
                              <th>
                                <a href="#" class="text-dark" @click.prevent="sort('url_image')">Foto</a>
                                <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'url_image' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'url_image' && filters.orderBy.direction == 'desc'}"></i>
                              </th>
                              <th>
                                <a href="#" class="text-dark">Acciones</a>
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                              <tr v-for="(quote_detail, index) in quote_details">
                                  <td class="">
                                    <div class="media-body">
                                      <div>{{quote_detail.product.name}}</div>
                                      <div class="medium text-muted">
                                        {{quote_detail.description}}
                                      </div>
                                    </div>
                                  </td>
                                  <td class="">{{quote_detail.product.category}}</td>
                                  <td class="">{{ quote_detail.price | currency }}</td>
                                  <td class="">{{quote_detail.product.description}}</td>
                                  <td>
                                    <div class="media">
                                      <div class="avatar_product float-left mr-3">
                                          <img style='width:120px' :src="'../../storage/app/public/product_img/' + quote_detail.product.url_image">
                                      </div>
                                    </div>
                                  </td>
                                  <td class="">
                                    <a class="card-header-action ml-1" href="#" :disabled="submitingDestroy"  @click="destroy(quote_detail.id)">
                                        <i class="fas fa-spinner fa-spin" v-if="submitingDestroy"></i>
                                        <i class="far fa-trash-alt" v-else style="color:red"></i>
                                        <span class="d-md-down-none ml-1"></span>
                                    </a>
                                  </td>
                              </tr>
                          </tbody>
                        </table>
                        <content-placeholders v-if="loading">
                          <content-placeholders-text/>
                        </content-placeholders>
                      </div>
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="form-group col-md-12">
                  <label><span class="font-weight-bold">PAQUETE INCLUYE:</span></label>
                  <textarea class="form-control" rows="5" :class="{'is-invalid': errors.include}" v-model="includeMessage"></textarea>
                  <div class="invalid-feedback" v-if="errors.include">{{errors.include[0]}}</div>
              </div>
          </div>
          <div class="row">
              <div class="form-group col-md-12">
                  <label><span class="font-weight-bold">POLITICAS DE RESERVACIÓN:</span></label>
                  <textarea class="form-control" rows="5" :class="{'is-invalid': errors.policy}"  v-model="quote.policy"></textarea>
                  <div class="invalid-feedback" v-if="errors.policy">{{errors.policy[0]}}</div>
              </div>
          </div>
          <div class="row">
              <div class="form-group col-md-12">
                  <label><span class="font-weight-bold">FORMAS DE PAGO:</span></label>
                  <textarea class="form-control" rows="5" :class="{'is-invalid': errors.payment}"  v-model="quote.payment"></textarea>
                  <div class="invalid-feedback" v-if="errors.payment">{{errors.payment[0]}}</div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
var csrf_token = $('meta[name="csrf-token"]').attr('content');
import Datepicker from 'vuejs-datepicker';
import {en, es} from 'vuejs-datepicker/dist/locale'
import moment from 'moment';
import VueNumeric from 'vue-numeric';

export default {
  components: {
      Datepicker,
      VueNumeric,
  },
  data () {
    return {
      customer: {},
      quote_details:{},
      errors: {},
      quote_detail_add:{
          product:{
              category:null
          }
      },
      products:[],
      customers:[],
      currencies:[
          { id: 'NACIONAL', name: 'NACIONAL' },
          { id: 'DOLARES', name: 'DOLARES' },
          { id: 'EUROS', name: 'EUROS' },
      ],
      quote:{
          customer_order:{
            customer:{
                full_name:null
            }
          }
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
      es: es,
      suppliers:[],
      menu: false,
      loading: true,
      submiting: false,
      submitingDestroy: false,
      type_of_person: [
          { id: 'PROSPECTO', name: 'PROSPECTO' },
          { id: 'CLIENTE', name: 'CLIENTE' },
      ],
      customerLevel:[
        { id: 'BAJA', name: 'BAJA' },
        { id: 'MEDIA', name: 'MEDIA' },
        { id: 'ALTA', name: 'ALTA' },
      ],
    }
  },
  computed: {
     includeMessage: {
        get: function() {
            return this.quote.include = '*Precio total por ' +this.quote.number_adults + ' Adulto(s) y ' + this.quote.number_childs + ' \n(# Habitación por)\n*Plan de alimentos especificados en cada hotel.';
        },
        set: function(includeMessage) {
            this.quote.include = includeMessage;
        }
    }

  },
  mounted () {
    this.csrf = window.Laravel.csrfToken;
    this.getQuote();
    this.getProducts();
    this.getSuppliers();
  },
  methods: {
    create () {
      if (!this.submiting) {
        this.submiting = true
        this.quote.status = 2;
        axios.post(`../../api/quotes/store`, this.quote)
        .then(response => {
            Vue.toasted.success('Cotización creada correctamente!')
            setTimeout(location.href = '../../quotes', 2000);
        })
        .catch(error => {
          this.errors = error.response.data.errors
          this.submiting = false
        })
      }
    },
    customFormatter(date) {
        this.quote.proposal_date = moment(date).format('YYYY/MM/DD');
        return moment(date).format('YYYY/MM/DD');
    },
    destroy (quoteDetail) {
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
            axios.delete(`../../api/quote_details/${quoteDetail}`)
            .then(response => {
                this.$toasted.global.error('producto eliminado!')
                this.getQuoteDetails(this.quote.id)
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
    getProducts() {
        axios.get(`../../api/products/all`).then(response => {
          this.products = response.data
        })
        .catch(error => {
            this.errors = error.response.data.errors
        })
    },
    addProduct(){
        if (!this.submiting) {
          this.submiting = true
          this.quote_detail_add.quote_id = this.quote.id
          axios.post(`../../api/quote_details/store`, this.quote_detail_add)
          .then(response => {
              this.$toasted.global.error('Agregado correctamente!')
              this.getQuoteDetails(this.quote.id)
              this.submiting = false
              this.quote_detail_add = {};
          })
          .catch(error => {
            this.errors = error.response.data.errors
            this.submiting = false
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
    getQuoteDetails (QuoteId) {
        axios.post(`../../api/quote_details/by-quote-id`,{
          quote_id: QuoteId
        })
        .then(response => {
            this.quote_details = response.data
        })
        .catch(error => {
            this.$toasted.global.error('User does not exist!')
        })
        .then(() => {
          this.loading = false
        })
    },
    getQuote () {
        let str = window.location.pathname
        let res = str.split("/")
        axios.get(`../../api/quotes/` + res[3])
        .then(response => {
            this.quote = response.data
            this.quote.policy = '* Tarifa sujeta a cambios sin previo aviso\n* Se reserva con un anticipo del 15% y se paga poco a poco\n(Dependiendo de las políticas de reservación que tenga el hotel)\n* Se liquida aproximadamente 25 días antes de su fecha de viaje\n(Puede variar dependiendo del hotel)';

            this.quote.payment = '* Deposito (oxxo, 7eleven o en el banco)\n* Tarjeta de credito (Comisión bancaria)\n* Transferencias';
            this.quote.travel_date = response.data.customer_order.travel_date + " Al " + response.data.customer_order.travel_end_date;
            this.quote.number_adults = response.data.customer_order.number_adults

            this.quote.number_childs = response.data.customer_order.number_childs

            this.getQuoteDetails(response.data['id'])

        })
        .catch(error => {
            this.$toasted.global.error('User does not exist!')
            location.href = '../../customers'
        })
        .then(() => {
          this.loading = false
        })
    },
    isNumeric: function (n) {
      return !isNaN(parseFloat(n)) && isFinite(n);
    },
  }
}
</script>
