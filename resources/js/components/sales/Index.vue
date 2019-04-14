<template>
  <div class="container">
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <h4 class="float-left pt-2">Ventas</h4>
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
            <input type="text" name="query" v-model.trim="searchQuery"  class="form-control" placeholder="Buscar..">
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
                  <a href="#" class="text-dark" @click.prevent="sort('sales.quote.customer_order.customer.full_name')">Nombre</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'sales.quote.customer_order.customer.full_name' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'sales.quote.customer_order.customer.full_name' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th>
                  <a href="#" class="text-dark" @click.prevent="sort('last_name')">Celular</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'last_name' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'last_name' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th>
                  <a href="#" class="text-dark" @click.prevent="sort('email')">Precio</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'email' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'email' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th>
                  <a href="#" class="text-dark" @click.prevent="sort('travel_date')">Fecha limite</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'travel_date' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'travel_date' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th>
                  <a href="#" class="text-dark" @click.prevent="sort('proposal_date')">Fecha proveedor</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'proposal_date' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'proposal_date' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th>
                  <a href="#" class="text-dark" @click.prevent="sort('proposal_date')">Fecha Anticipo</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'proposal_date' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'proposal_date' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th>
                  <a href="#" class="text-dark" @click.prevent="sort('proposal_date')">Cobrar</a>
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
              <tr v-for="(sale, index) in salesFilter">
                <td class="">{{ sale.id }}</td>
                <td class="">{{ sale.quote ? sale.quote.customer_order.customer.full_name:null }}</td>
                <td class="">{{ sale.quote ? sale.quote.customer_order.customer.cellphone:null }}</td>
                <td class="">{{ sale.price | currency }}</td> 
                <td class="">{{ sale.date_payment_limit }}</td>
                <td class="">{{ sale.date_payment_supplier }}</td>
                <td class="">{{ sale.date_advance }}</td>
                <td class="">{{ sale.schedule}} / {{ sale.amount_receivable | currency }}</td>

                <td class="">
                    <label v-if="sale.status == 1">Activo</label>
                    <label v-else>Inactivo</label>
                </td>
                <td class="">
                  <small>{{sale.created_at | moment("LL")}}</small> - <small class="text-muted">{{sale.created_at | moment("LT")}}</small>
                </td>
                <td class="">
                  <a href="#" v-if="$can('read-sales')" data-toggle="tooltip" data-placement="bottom" title="Ver mas info" @click="getDetailSale(sale.id)" class="card-header-action ml-1 text-muted">
                    <i class="fa-lg fas fa-info-circle text-primary"></i>
                  </a>
                  <a href="#"  data-toggle="tooltip" data-placement="bottom" title="Enviar correo" @click="sendMail(sale.id)" class="card-header-action ml-1 text-muted">
                    <i class="fa-lg fas fa-envelope text-dark"></i>
                  </a>
                  <a href="#"  data-toggle="tooltip" data-placement="bottom" title="Enviar whatsapp" @click="sendWhatsapp(sale.id)" class="card-header-action ml-1 text-muted">
                    <i style="color:green" class="fa-lg fab fa-whatsapp text-success"></i>
                  </a>
                  <a href="#"  data-toggle="tooltip" data-placement="bottom" title="Imprimir cupón" @click="showModalPrint(sale.id)" class="card-header-action ml-1 text-muted">
                    <i class="fa-lg fas fa-print"></i>
                  </a>
                  <a v-if="$can('take-payment-sales')" :href="'./payments/create/' + sale.id" class="card-header-action ml-1 text-muted">
                    <i class="fa-lg fas fa-hand-holding-usd"></i>
                  </a>
                  <a v-if="$can('update-sales')" href="#" @click="editSale(sale.id)" class="card-header-action ml-1 text-muted"><i class="fa-lg fas fa-pencil-alt"></i></a>
                  <a v-if="$can('delete-sales')" class="card-header-action ml-1" href="#" :disabled="submitingDestroy"  @click="destroy(sale.id)">
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
          <div class="no-items-found text-center mt-5" v-if="!loading && !sales.length > 0">
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
            <sales-input :quote_id="quoteId" :type="type" :quote_sale="quoteSale"></sales-input>
          </div>
          <b-button class="mt-3" variant="outline-success" block @click="saveSaleAndHideModal">Guardar</b-button>
        </b-modal>

        <!-- Modal Print -->
        <b-modal size="lg" id="modal2" ref="myModalRef3" hide-footer title="Nota Adiccional">
          <div class="d-block">
            <textarea class="form-control" rows="5" v-model="notePrint"></textarea>
          </div>
          <b-button class="mt-3" variant="outline-success" block @click="print"><strong>Imprimir Cupón</strong></b-button>
        </b-modal>
    </div>
  </div>
</template>

<script>
let csrf_token = $('meta[name="csrf-token"]').attr('content');

export default {
  data () {
    return {
      sales: {
        quote: {
          customer_order:{
            customer:{
                cellphone:null
            }
          }
        }
      },
      details: {
          customer: {
            full_name:null
          }
      },
      docs: [],
      searchQuery:'',
      quoteId: null,
      type: "create",
      notePrint:null,
      sale_id_print:null,
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
        return this.sales.map(function(quote) {
            //return customer_order.first_name + ' ' + customer_order.last_name;
        });
    },
    salesFilter (){
      var filterKey = this.searchQuery && this.searchQuery.toLowerCase()
      var sales = this.sales
      if (filterKey) {
        sales = sales.filter(function (row) {
          return Object.keys(row).some(function (key) {
            return String(row[key]).toLowerCase().indexOf(filterKey) > -1
          })
        })
      }
      return sales;
    }
  },
  mounted () {
    if (localStorage.getItem("filtersTableSales")) {
      this.filters = JSON.parse(localStorage.getItem("filtersTableSales"))
    } else {
      localStorage.setItem("filtersTableSales", this.filters);
    }
    this.getSales()
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
    getDetailSale(saleId){
        axios.get(`./api/sales/get-sale/` + saleId)
        .then(response => {
            this.details = response.data
            this.$refs.myModalRef.show()
            if(response.data.path){
                this.path_pdf = './storage/app/public/pdf/sales/' + response.data.path;  
            }else{
                this.path_pdf = null;
            }
            
        })
        .catch(error => {
            this.$toasted.global.error('Venta no encontrada!')
        })
        .then(() => {
          this.loading = false
        })
    },
    tracing(quoteId){
        location.href = `./quotes/track-quote/`;
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
    print(){
      axios.post(`./api/sales/save-note-coupon`, {
          sale_id : this.sale_id_print,
          note: this.notePrint
      })
      .then(response => {
          this.notePrint = '';
          this.$refs.myModalRef3.hide()
          window.open("./api/sales/coupon/"+ this.sale_id_print +"",'_blank');
      })
      .catch(error => {
        this.errors = error.response.data.errors
        this.submiting = false
      })
    },
    showModalPrint(saleId){
      this.sale_id_print = saleId;
      this.$refs.myModalRef3.show()
    },
    sendWhatsapp(saleId){
        axios.get(`./api/sales/get-sale/` + saleId)
        .then(response => {
            let data = response.data;
            if(data.quote.customer_order.customer.cellphone == null || data.quote.customer_order.customer.cellphone == ''){
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
                window.open("https://wa.me/52"+ data.quote.customer_order.customer.cellphone +"?text=Estimado " + data.quote.customer_order.customer.full_name + ", adjunto encontrará la venta con destino a " + travel,'_blank');
            }
            
        })
        .catch(error => {
            this.$toasted.global.error('telefono no encontrada!',error)
        })
        .then(() => {
          this.loading = false
        })
        
    },
    getSales () {
      this.loading = true
      this.sales = []

      localStorage.setItem("filtersTableSales", JSON.stringify(this.filters));

      axios.post(`./api/sales/filter?page=${this.filters.pagination.current_page}`, this.filters)
      .then(response => {
        this.sales = response.data.data
        delete response.data.data
        this.filters.pagination = response.data
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
    },
    filter() {
      this.filters.pagination.current_page = 1
      this.getSales()

    },
    changeSize (perPage) {
      this.filters.pagination.current_page = 1
      this.filters.pagination.per_page = perPage
      this.getSales()
    },
    sort (column) {
      if(column == this.filters.orderBy.column) {
        this.filters.orderBy.direction = this.filters.orderBy.direction == 'asc' ? 'desc' : 'asc'
      } else {
        this.filters.orderBy.column = column
        this.filters.orderBy.direction = 'asc'
      }

      this.getSales()
    },
    changePage (page) {
      this.filters.pagination.current_page = page
      this.getSales()
    }
    ,destroy (customerId) {
      if (!this.submitingDestroy) {
        this.submitingDestroy = true
        swal({
          title: "¿Estás seguro?",
          text: "Una vez eliminado, no podrá recuperar esta venta.",

          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete(`./api/sales/${customerId}`)
            .then(response => {
                this.$toasted.global.error('Venta eliminada!')
                location.href = './sales'
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
