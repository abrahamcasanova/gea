<template>
  <div class="container">
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <h4 class="float-left pt-2">Solicitud de Cotizaciones</h4>
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
                  <a href="#" class="text-dark" @click.prevent="sort('first_name')">Nombre</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'first_name' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'first_name' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th>
                  <a href="#" class="text-dark" @click.prevent="sort('level')">Nivel</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'level' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'level' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th>
                  <a href="#" class="text-dark">Cotizaciones/Ventas</a>
                  <i class="mr-1 fas"></i>
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
                  <a href="#" class="text-dark" @click.prevent="sort('status')">Estatus</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'status' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'status' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th class="">
                  <a href="#" class="text-dark" @click.prevent="sort('updated_at')">Registrado</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'updated_at' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'updated_at' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th class=""></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(customer_order, index)   in customers_orders">
                <td class="">{{customer_order.id}}</td>
                <td class="" v-if='customer_order.customer'>{{customer_order.customer['full_name']}}</td>
                <td class="" v-else='customer_order.customer_order'>{{customer_order.first_name}}</td>
                <td class="">{{customer_order.customer ? customer_order.customer.level:null}}</td>
                <td class="text-center">{{customer_order.quote_number ? customer_order.quote_number.total+'/'+customer_order.sale_number.total:null}}</td>
                <td class="">{{customer_order.phone}}</td>
                <td class=""  v-if='customer_order.customer' >{{customer_order.customer.email}}</td>
                <td class=""  v-else='customer_order.customer_order' ></td>
                <td class="">{{customer_order.travel_date}}</td>
                <td class="">
                    <label v-if="customer_order.status == 1">Activo</label>
                    <label v-else>Inactivo</label>
                </td>
                <td class="">
                  <small>{{customer_order.updated_at | moment("LL")}}</small> - <small class="text-muted">{{customer_order.updated_at | moment("LT")}}</small>
                </td>
                <td class="">
                  <a v-if="$can('read-customer-orders')" href="#"  data-toggle="tooltip" data-placement="bottom" title="Ver mas info" @click="getOrder(customer_order.id)" class="card-header-action ml-1 text-muted">
                    <i class="fa-lg fas fa-info-circle text-primary"></i>
                  </a>
                  <a v-if="$can('create-quotes')" href="#" data-toggle="tooltip" data-placement="bottom" title="Crear Cotización" @click="createQuote(customer_order.id)" class="card-header-action ml-1 text-muted">
                      <i class="fa-lg far fa-file-pdf text-danger"></i>
                  </a>
                  <a v-if="$can('update-customer-orders')" href="#" @click="editCustomer(customer_order.id)" class="card-header-action ml-1 text-muted"><i class="fa-lg fas fa-pencil-alt"></i></a>
                  <a v-if="$can('delete-customer-orders')" class="card-header-action ml-1" href="#" :disabled="submitingDestroy"  @click="destroy(customer_order.id)">
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
          <div class="no-items-found text-center mt-5" v-if="!loading && !customers_orders.length > 0">
            <i class="icon-magnifier fa-3x text-muted"></i>
            <p class="mb-0 mt-3"><strong>No se pudo encontrar ningún artículo</strong></p>
            <p class="text-muted">Intenta cambiar los filtros o añadir uno nuevo.</p>
            <a class="btn btn-success" href="./customers_orders/create" role="button">
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
                <div class="form-group col-md-6">
                    <label>Nombres</label>
                    <input type="text" readonly disabled class="form-control" v-model="details.customer['full_name']" placeholder="nombre">
                </div>
                <div class="form-group col-md-6">
                    <label>Email</label>
                    <input type="text" readonly disabled class="form-control" v-model="details.customer['email']" placeholder="Correo">
                </div>

                <div class="form-group col-md-4">
                    <label>Mes de viaje</label>
                    <input type="text" readonly disabled class="form-control" v-model="details.travel_month" placeholder="Mes de viaje">
                </div>
                <div class="form-group col-md-4">
                    <label>Fecha inicio de viaje</label>
                    <input type="text" readonly disabled class="form-control" v-model="details.travel_date" placeholder="Fecha de viaje">
                </div>
                <div class="form-group col-md-4">
                    <label>Fecha fin de viaje</label>
                    <input type="text" readonly disabled class="form-control" v-model="details.travel_end_date" placeholder="Fecha de viaje">
                </div>
                <div class="form-group col-md-6">
                    <label>En que horario le podemos marcar?</label>
                    <input type="text" readonly disabled class="form-control" v-model="details.call_time" placeholder="Horario de llamada">
                </div>
                <div class="form-group col-md-6">
                    <label>Ha viajado con nosotros?</label>
                    <input type="text" readonly disabled class="form-control" v-model="details.with_us" placeholder="Viajado con nostros">
                </div>
                <div class="form-group col-md-12">
                  <label>Destino del viaje</label>
                  <v-autocomplete
                  v-model="details.travel_destination"
                  :items="items"
                  :loading="isLoading"
                  chips
                  :multiple="true"
                  clearable
                  readonly
                  :disabled="true"
                  hide-details
                  hide-selected
                  item-text="name"
                  item-value="id"
                  label="Buscar destinos"
                  solo
                >
                  <template v-slot:selection="{ item, selected }">
                    <v-chip
                      :selected="true"
                      color="blue"
                      class="white--text"
                    >
                      <span v-text="item.name"></span>
                    </v-chip>
                  </template>
                </v-autocomplete>
                </div>
                <div class="form-group col-md-6">
                    <label>Cuantos adultos irían al viaje?</label>
                    <input type="text" readonly disabled class="form-control" v-model="details.number_adults" placeholder="Numero de adultos">
                </div>
                <div class="form-group col-md-12">
                    <label>Lleva menores? <span class="font-weight-bold">(especificar el numero de menores y edad) 0 a 12 años</span></label>
                    <textarea class="form-control" readonly disabled rows="5" v-model="details.number_childs"></textarea>
                </div>
                <div class="form-group col-md-12">
                    <label>Servicio requeridos</label>
                    <textarea class="form-control" readonly disabled rows="5" v-model="details.services"></textarea>
                </div>
                <div class="form-group col-md-12">
                  <label>Otros comentarios</label>
                  <textarea class="form-control" readonly disabled rows="5" v-model="details.comments"></textarea>
                </div>
              </div>
            </div>
          </div>
          <b-button class="mt-3" variant="outline-success" block @click="hideModal">Cerrar</b-button>
        </b-modal>
    </div>
  </div>
</template>

<script>
let csrf_token = $('meta[name="csrf-token"]').attr('content');
export default {
  data () {
    return {
      customers_orders: [{
          first_name: '',
          last_name: ''}
      ],
      details: {
          customer: {
            full_name:null
          }
      },
      docs: [],
      items:null,
      isLoading:null,
      search: null,
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
      options: {
          target: './api/customers_orders/upload',
          testChunks: false,
          singleFile: true,
          withCredentials: true,
          headers:{
              'X-CSRF-TOKEN': csrf_token,
          },
          query: {
              upload_token: csrf_token,
              customer_id: null
          }
      },
      attrs: {

      }
    }
  },
  computed: {
    fullName: function() {
        return this.customers_orders.map(function(customer_order) {
            return customer_order.first_name + ' ' + customer_order.last_name;
        });
    }
  },
  mounted () {
    if (localStorage.getItem("filtersTableCustomerOrder")) {
      this.filters = JSON.parse(localStorage.getItem("filtersTableCustomerOrder"))
    } else {
      localStorage.setItem("filtersTableCustomerOrder", this.filters);
    }
    this.getCustomerOrders()
    this.getDestinations()
  },
  methods: {
    hideModal() {
        this.$refs.myModalRef.hide()
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
    getOrder (customerId) {
        axios.get(`./api/customers_orders/` + customerId)
        .then(response => {
            this.details = response.data
            this.$refs.myModalRef.show()
        })
        .catch(error => {
            this.$toasted.global.error('User does not exist!')
        })
        .then(() => {
          this.loading = false
        })
    },
    getCustomerOrders () {
      this.loading = true
      this.customers_orders = []

      localStorage.setItem("filtersTableCustomerOrder", JSON.stringify(this.filters));

      axios.post(`./api/customers_orders/filter?page=${this.filters.pagination.current_page}`, this.filters)
      .then(response => {
        this.customers_orders = response.data.data
        delete response.data.data
        this.filters.pagination = response.data
        this.loading = false
      })
    },
    editCustomer (customerId) {
        location.href = `./customers_orders/${customerId}/edit`
    },
    createQuote(orderId){
  
        axios.post(`./api/quotes/store`, {
          customer_order_id : orderId
        })
        .then(response => {
            Vue.toasted.success('Creado correctamente!')
            location.href = './customers_orders/' + response.data['id'] + '/quote'
        })
        .catch(error => {
          this.errors = error.response.data.errors
          this.submiting = false
        })
    },
    filter() {
      this.filters.pagination.current_page = 1
      this.getCustomerOrders()

    },
    changeSize (perPage) {
      this.filters.pagination.current_page = 1
      this.filters.pagination.per_page = perPage
      this.getCustomerOrders()
    },
    sort (column) {
      if(column == this.filters.orderBy.column) {
        this.filters.orderBy.direction = this.filters.orderBy.direction == 'asc' ? 'desc' : 'asc'
      } else {
        this.filters.orderBy.column = column
        this.filters.orderBy.direction = 'asc'
      }

      this.getCustomerOrders()
    },
    changePage (page) {
      this.filters.pagination.current_page = page
      this.getCustomerOrders()
    }
    ,destroy (customerId) {
      if (!this.submitingDestroy) {
        this.submitingDestroy = true
        swal({
          title: "¿Estás seguro?",
          text: "Una vez eliminado, no podrá recuperar esta solicitud de cliente.",

          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete(`./api/customers_orders/${customerId}`)
            .then(response => {
                this.$toasted.global.error('Deleted customer_order!')
                location.href = './customers_orders'
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
