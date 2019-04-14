<template>
  <div class="container">
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <h4 class="float-left pt-2">Ultimos Pagos Registrados</h4>
      <div class="card-header-actions mr-1">
        
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
      <table class="table table-hover">
        <thead>
          <tr>
            <th class="">
              <a href="#" class="text-dark" @click.prevent="sort('id')">Clave</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'id' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'id' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('name')">Cliente</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'name' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'name' && filters.orderBy.direction == 'desc'}"></i>
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
            <th class="">
              <a href="#" class="text-dark" @click.prevent="sort('created_at')">Registrado</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'created_at' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'created_at' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th class=""></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="payment in paymentsFilter ">
            <td class="">{{payment.id}}</td>
            <td class="">{{payment.customer.full_name}}</td>
            <td class="">{{ payment.price | currency }}</td>
            <td class="">{{payment.user.name}}</td>
            <td class="">{{payment.type_of_payment}}</td>
            <td class="">
              <small>{{payment.created_at | moment("LL")}}</small> - <small class="text-muted">{{payment.created_at | moment("LT")}}</small>
            </td>
            <td class="">
              <a href="#" v-if="$can('read-payments')" data-toggle="tooltip" data-placement="bottom" title="Ver recibo" @click="getReceiptPayment(payment.id)" class="card-header-action ml-1 text-muted">
                <i class="fa-lg fas fa-info-circle text-primary"></i>
              </a>
              <a href="#" v-if="$can('update-payments')" @click="editPayment(payment.id)" class="card-header-action ml-1 text-muted"><i class="fas fa-pencil-alt fa-lg"></i></a>
              <a  v-if="$can('delete-payments')" class="card-header-action ml-1" href="#" :disabled="submitingDestroy"  @click="destroy(payment.id)">
                  <i class="fas fa-spinner fa-spin fa-lg" v-if="submitingDestroy"></i>
                  <i class="far fa-trash-alt fa-lg" v-else style="color:red"></i>
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
      <div class="no-items-found text-center mt-5" v-if="!loading && !payments.length > 0">
        <i class="icon-magnifier fa-3x text-muted"></i>
        <p class="mb-0 mt-3"><strong>No se pudo encontrar ningún artículo</strong></p>
        <p class="text-muted">Intenta cambiar los filtros o añadir uno nuevo.</p>
        <a class="btn btn-success" href="./payments/create" role="button">
          <i class="fa fa-plus"></i>&nbsp; Nuevo pago
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
  </div>
</template>

<script>
let csrf_token = $('meta[name="csrf-token"]').attr('content');
export default {
  data () {
    return {
      payments: {},
      docs: [],
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
      path_pdf:'',
      submitingDestroy: false,
      searchQuery: '',
    }
  },
  computed: {
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
  mounted () {
    if (localStorage.getItem("filtersTablePayments")) {
      this.filters = JSON.parse(localStorage.getItem("filtersTablePayments"))
    } else {
      localStorage.setItem("filtersTablePayments", this.filters);
    }
    this.getPayments()
  },
  methods: {
    show (id) {
      this.options.query.customer_id = id
      this.$modal.show('modal-uploader',{
        title: 'Information',
      });
    },
    hideModal() {
        this.$refs.myModalRef.hide()
    },
    getReceiptPayment(paymentId){
        axios.get(`./api/payments/get-payment/` + paymentId)
        .then(response => {
            this.$refs.myModalRef.show()
            console.log(response.data)
            if(response.data.path){
                this.path_pdf = './storage/app/public/pdf/payments/' + response.data.path;  
            }else{
                this.path_pdf = null;
            }
            
        })
        .catch(error => {
            this.$toasted.global.error('Pago no encontrada!')
        })
        .then(() => {
          this.loading = false
        })
    },
    getPayments () {
      this.loading = true
      this.payments = []

      localStorage.setItem("filtersTablePayments", JSON.stringify(this.filters));

      axios.post(`./api/payments/filter?page=${this.filters.pagination.current_page}`, this.filters)
      .then(response => {
        this.payments = response.data.data
        delete response.data.data
        this.filters.pagination = response.data
        this.loading = false
      })
    },
    getDocuments (id) {
        this.loading = true
        axios.post('./api/payments/docs',{
            customer_id: id
        }).then(response => {
            //console.log(response.data)
            this.docs = response.data
            this.loading = false
        });
    },
    editPayment (paymentId) {
      location.href = `./payments/${paymentId}/edit`
    },
    // filters
    filter() {
      this.filters.pagination.current_page = 1
      this.getPayments()

    },
    changeSize (perPage) {
      this.filters.pagination.current_page = 1
      this.filters.pagination.per_page = perPage
      this.getPayments()
    },
    sort (column) {
      if(column == this.filters.orderBy.column) {
        this.filters.orderBy.direction = this.filters.orderBy.direction == 'asc' ? 'desc' : 'asc'
      } else {
        this.filters.orderBy.column = column
        this.filters.orderBy.direction = 'asc'
      }

      this.getPayments()
    },
    changePage (page) {
      this.filters.pagination.current_page = page
      this.getPayments()
    }
    ,destroy (customerId) {
      if (!this.submitingDestroy) {
        this.submitingDestroy = true
        swal({
          title: "¿Esta seguro?",
          text: "Una vez eliminado, no podrá recuperar este pago!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete(`./api/payments/${customerId}`)
            .then(response => {
                this.$toasted.global.error('Deleted product!')
                location.href = './payments'
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
