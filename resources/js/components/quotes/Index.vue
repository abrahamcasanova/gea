<template>
  <div class="container">
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <h4 class="float-left pt-2">Cotizaciones</h4>
      <div class="card-header-actions mr-1">
        <a class="btn btn-sm btn-primary" href="./quotes/track-quote"> {{ $t('Prospecting.Prospecting_track') }} <i class="nav-icon currentColor icon-target"></i> </a>
      </div>
    </div>
    <div class="card-body px-0">
      <div class="row justify-content-between">
        <div class="col-7 col-md-5">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" @click="filter">
                <i class="fas fa-search"></i>
              </span>
            </div>
            <input type="text" class="form-control" placeholder="Seach" v-model.trim="filters.search" @keyup.enter="filter">
          </div>
        </div>
        <div class="col-auto">
          <multiselect
            v-model="filters.pagination.per_page"
            :options="[25,50,100,200]"
            :searchable="false"
            :show-labels="false"
            :allow-empty="false"
            @select="changeSize"
            placeholder="Search">
          </multiselect>
        </div>
      </div>
      <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th class="">
                  <a href="#" class="text-dark" @click.prevent="sort('id')">ID</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'id' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'id' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th>
                  <a href="#" class="text-dark" @click.prevent="sort('quote.customer_order.customer.full_name')">Nombre</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'quote.customer_order.customer.full_name' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'quote.customer_order.customer.full_name' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th>
                  <a href="#" class="text-dark" @click.prevent="sort('level')">Nivel</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'level' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'level' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th>
                  <a href="#" class="text-dark" @click.prevent="sort('last_name')">Telefonos</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'last_name' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'last_name' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th>
                  <a href="#" class="text-dark" @click.prevent="sort('email')">Email</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'email' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'email' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th>
                  <a href="#" class="text-dark" @click.prevent="sort('travel_date')">Fecha de viaje</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'travel_date' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'travel_date' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                 <th>
                  <a href="#" class="text-dark" @click.prevent="sort('proposal_date')">Fecha de propuesta</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'proposal_date' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'proposal_date' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th>
                  <a href="#" class="text-dark" @click.prevent="sort('status')">Estatus</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'status' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'status' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th class="">
                  <a href="#" class="text-dark" @click.prevent="sort('created_at')">Registrado</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'created_at' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'created_at' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th class=""></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(quote, index)   in quotes">
                <td class="">{{quote.id}}</td>
                <td class="" v-if='quote.customer_order'>{{quote.customer_order.customer['full_name']}}</td>
                <td class="" v-else='quote.customer_order.customer_order'></td>
                <td class="">{{quote.customer_order ? quote.customer_order.customer.level:null}}</td>
                <td class="" v-if='quote.customer_order'>{{quote.customer_order.phone}}</td>
                <td class="" v-else></td>
                <td class="" v-if='quote.customer_order' >{{quote.customer_order.customer.email}}</td>
                <td class="" v-else='quote.customer_order' ></td>
                <td class="">{{quote.proposal_date}}</td>
                <td class="">{{quote.travel_date}}</td>
                <td class="">
                    <label v-if="quote.status == 2">Activo</label>
                    <label v-else>Inactivo</label>
                </td>
                <td class="">
                  <small>{{quote.created_at | moment("LL")}}</small> - <small class="text-muted">{{quote.created_at | moment("LT")}}</small>
                </td>
                <td class="">
                  <a v-if="$can('read-quotes')" href="#" data-toggle="tooltip" data-placement="bottom" title="Ver mas info" @click="getDetailQuote(quote.id)" class="card-header-action ml-1 text-muted">
                    <i class="fa-lg fas fa-info-circle text-primary"></i>
                  </a>
                  <a href="#"  data-toggle="tooltip" data-placement="bottom" title="Enviar correo" @click="sendMail(quote.id)" class="card-header-action ml-1 text-muted">
                    <i class="fa-lg fas fa-envelope text-dark"></i>
                  </a>
                  <a href="#"  data-toggle="tooltip" data-placement="bottom" title="Enviar whatsapp" @click="sendWhatsapp(quote.id)" class="card-header-action ml-1 text-muted">
                    <i style="color:green" class="fa-lg fab fa-whatsapp text-success"></i>
                  </a>
                  <a v-if="$can('create-sales')" href="#"  data-toggle="tooltip" data-placement="bottom" title="Generar Venta" @click="modalSale(quote.id)" class="card-header-action ml-1 text-muted">
                    <i style="color:#3f51b5" class="fa-lg fas  fa-file-invoice-dollar text-indigo"></i>
                  </a>
                  <a v-if="$can('update-quotes')" href="#" @click="editCustomer(quote.id)" class="card-header-action ml-1 text-muted"><i class="fa-lg fas fa-pencil-alt"></i></a>
                  <a v-if="$can('delete-quotes')" class="card-header-action ml-1" href="#" :disabled="submitingDestroy"  @click="destroy(quote.id)">
                      <i class="fa-lg fas fa-spinner fa-spin" v-if="submitingDestroy"></i>
                      <i class="fa-lg far fa-trash-alt" v-else></i>
                      <span class="d-md-down-none ml-1"></span>
                  </a>
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
          <div class="no-items-found text-center mt-5" v-if="!loading && !quotes.length > 0">
            <i class="icon-magnifier fa-3x text-muted"></i>
            <p class="mb-0 mt-3"><strong>No se pudo encontrar ningún artículo</strong></p>
            <p class="text-muted">Intenta cambiar los filtros o añadir uno nuevo.</p>
            <a class="btn btn-success" href="/quotes/create" role="button">
              <i class="fa fa-plus"></i>&nbsp; Nuevo
            </a>
          </div>
          <content-placeholders v-if="loading">
            <content-placeholders-text/>
          </content-placeholders>
        </div>
        <!-- Modal Component -->
        <b-modal size="lg" id="modal1" ref="myModalRef" hide-footer title="Detalle">
          <div class="d-block">
            <div class="col-md-12 card-body px-0">
              <div class="row">
                  <iframe :src="path_pdf" style="width:100%" height="420"></iframe> 
              </div>
            </div>
          </div>
          <b-button class="mt-3" variant="outline-success" block @click="hideModal">Cerrar</b-button>
        </b-modal>

        <!-- Modal Sale -->
        <b-modal size="lg" id="modal2" ref="myModalRef2" hide-footer title="Generar Venta">
          <div class="d-block">
            <sales-input :quote_id="quoteId" :quote_sale="quoteSale"></sales-input>
          </div>
          <b-button class="mt-3" variant="outline-success" block @click="saveSaleAndHideModal">Guardar</b-button>
        </b-modal>

    </div>
  </div>
</template>

<script>
let csrf_token = $('meta[name="csrf-token"]').attr('content');

export default {
  data () {
    return {
      quotes: [{
          first_name: '',
          last_name: ''}
      ],
      details: {
          customer: {
            full_name:null
          }
      },
      docs: [],
      quoteId: null,
      quoteSale: [],
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
      submitingDestroy: false,
    }
  },
  computed: {
    fullName: function() {
        return this.quotes.map(function(customer_order) {
            return customer_order.first_name + ' ' + customer_order.last_name;
        });
    }
  },
  mounted () {
    if (localStorage.getItem("filtersTableCustomerQuotes")) {
      this.filters = JSON.parse(localStorage.getItem("filtersTableCustomerQuotes"))
    } else {
      localStorage.setItem("filtersTableCustomerQuotes", this.filters);
    }
    this.getQuotes()
  },
  methods: {
    hideModal() {
        this.$refs.myModalRef.hide()
    },
    saveSaleAndHideModal(){
        this.$refs.myModalRef2.hide()
    },
    modalSale(quoteId){
        axios.get(`./api/quotes/get-quote/` + quoteId)
        .then(response => {
            this.details = response.data
            this.$refs.myModalRef2.show()
            this.quoteId = quoteId;
            this.quoteSale = response.data;
        })
        .catch(error => {
            this.$toasted.global.error('Producto no encontrada!')
        })
        .then(() => {
          this.loading = false
        })
    },
    getDetailQuote(quoteId){
        axios.get(`./api/quotes/get-quote/` + quoteId)
        .then(response => {
            this.details = response.data
            this.$refs.myModalRef.show()
            if(response.data.path){
                this.path_pdf = './storage/app/public/pdf/' + response.data.path;  
            }else{
                this.path_pdf = null;
            }
            
        })
        .catch(error => {
            this.$toasted.global.error('Cotización no encontrada!')
        })
        .then(() => {
          this.loading = false
        })
    },
    tracing(quoteId){
        location.href = `./quotes/track-quote/`;
    },
    sendMail(quoteId){
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

              axios.post(`./api/quotes/send-quote`,{
                id: quoteId
              })
              .then(response => {
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
    sendWhatsapp(quoteId){
        axios.get(`./api/quotes/get-quote/` + quoteId)
        .then(response => {
            let data = response.data;
            console.log(data)
            if(data.customer_order.customer.cellphone == null || data.customer_order.customer.cellphone == ''){
                swal("Error..", {
                  text:'No se puede enviar el whatsapp porque no existe un telefono registrado.',
                  icon: 'error',
                  timer: 3000,
                  buttons: false,
                });
                return false;
            }else{
                let str = window.location
                var travel = '';
                for (var i = 0; data.travel_destination.length > i; i++) {
                  let nameCapitalized = capitalizeFirstLetter(data.travel_destination[i].name.toLowerCase());
                  travel += nameCapitalized + ', ';
                }
                window.open("https://wa.me/52"+ data.customer_order.customer.cellphone +"?text=Estimado " + data.customer_order.customer.full_name + ", adjunto encontrará la cotización con destino a " + travel,'_blank');
            }
            
        })
        .catch(error => {
            this.$toasted.global.error('telefono no encontrada!',error)
        })
        .then(() => {
          this.loading = false
        })
        
    },
    getQuotes () {
      this.loading = true
      this.quotes = []

      localStorage.setItem("filtersTableCustomerQuotes", JSON.stringify(this.filters));

      axios.post(`./api/quotes/filter?page=${this.filters.pagination.current_page}`, this.filters)
      .then(response => {
        this.quotes = response.data.data
        delete response.data.data
        this.filters.pagination = response.data
        this.loading = false
      })
    },
    editCustomer (customerId) {
        location.href = `./quotes/${customerId}/edit`
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
    },
    filter() {
      this.filters.pagination.current_page = 1
      this.getQuotes()

    },
    changeSize (perPage) {
      this.filters.pagination.current_page = 1
      this.filters.pagination.per_page = perPage
      this.getQuotes()
    },
    sort (column) {
      if(column == this.filters.orderBy.column) {
        this.filters.orderBy.direction = this.filters.orderBy.direction == 'asc' ? 'desc' : 'asc'
      } else {
        this.filters.orderBy.column = column
        this.filters.orderBy.direction = 'asc'
      }

      this.getQuotes()
    },
    changePage (page) {
      this.filters.pagination.current_page = page
      this.getQuotes()
    }
    ,destroy (customerId) {
      if (!this.submitingDestroy) {
        this.submitingDestroy = true
        swal({
          title: "¿Estás seguro?",
          text: "Una vez eliminado, no podrá recuperar esta cotización.",

          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete(`./api/quotes/${customerId}`)
            .then(response => {
                this.$toasted.global.error('Cotización eliminada!')
                location.href = './quotes'
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

function capitalizeFirstLetter(string) {
    return string[0].toUpperCase() + string.slice(1);
}
</script>
