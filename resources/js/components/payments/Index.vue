<template>
  <div class="container">
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <div class="card-header-actions mr-1">

      </div>
    </div>
    <!--
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
              <a href="#" class="text-dark">Autorización</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'sale.saleDetail' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'product_type_id' && filters.orderBy.direction == 'desc'}"></i>
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
            <td class="">
              <span v-if="payment.sale.sale_detail" v-for="(list, index) in payment.sale.sale_detail">
                <h5>
                  <span style="margin-left:5px;" class="badge badge-pill badge-info text-white"> {{list.confirmation}} </span>
                </h5>
              </span>
            </td>
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
    -->

    <div class="card">
      <div class="card-body">

        <div class="card-body px-0">
        <div class="table-responsive">
          <!--
          <b-table class="table" :items="payments" :fields="tableFields" :filter="Bfilter"
          :current-page="currentPage" :per-page="perPage" @filtered="onFiltered" striped>
            <template slot="confirm" slot-scope="row">
                <center>
                  <span v-if="row.item.confirm == 1" class='badge badge-primary'>SI</span>
                  <span v-else class='badge badge-secondary'>NO</span>
                </center>
            </template>
            <template slot="actions" slot-scope="payment">
              <a href="#" @click="modalPayment(payment.item.id)" v-if="$can('confirm-payments')" class="card-header-action ml-1 text-muted">
                <i class="fa-lg fas fa-check-double" style="color:#4dbd74"></i>
              </a>
              <a href="#" v-if="$can('read-payments')" data-toggle="tooltip" data-placement="bottom" title="Ver recibo" @click="getReceiptPayment(payment.item.id)" class="card-header-action ml-1 text-muted">
                <i class="fa-lg fas fa-info-circle text-primary"></i>
              </a>
              <a href="#" v-if="$can('send-mail-payment')" @click="sendMail(payment.item.id)" data-toggle="tooltip" data-placement="bottom" title="Enviar correo" class="card-header-action ml-1 text-muted">
                <i class="fa-lg fas fa-envelope" style="color:#ffa000"></i>
              </a>
              <a href="#" v-if="$can('update-payments')" @click="editPayment(payment.item.id)" class="card-header-action ml-1 text-muted"><i class="fas fa-pencil-alt fa-lg"></i></a>
              <a  v-if="$can('delete-payments')" class="card-header-action ml-1" href="#" :disabled="submitingDestroy"  @click="destroy(payment.item.id)">
                  <i class="fas fa-spinner fa-spin fa-lg" v-if="submitingDestroy"></i>
                  <i class="far fa-trash-alt fa-lg" v-else style="color:red"></i>
                  <span class="d-md-down-none ml-1"></span>
              </a>
            </template>
          </b-table>
          <b-row>
           <b-col md="6" class="my-1">
             <b-pagination
               v-model="currentPage"
               :total-rows="totalRows"
               :per-page="perPage"
               class="my-0"
             ></b-pagination>
           </b-col>
         </b-row>
          -->
        </div>
        </div>
      </div>
    </div>

    <v-app v-cloak>
    <v-card>
      <v-toolbar-title>
        <div class="h3 text-muted text-right mb-12">
          <h4 style="margin-right:10px;margin-top:10px;" class="text-muted text-uppercase font-weight-bold">ultimos pagos registrados
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
        :items="payments"
        :search="search"
        v-bind:pagination.sync="pagination"
      >
        <template v-slot:items="props">
          <td class="text-xs-center">{{ props.item.id }}</td>
          <td class="text-xs-left">{{ props.item.customer.full_name }}</td>
          <td class="text-xs-center">{{ props.item.price }}</td>
          <td class="text-xs-left">{{ props.item.authorization_number }}</td>
          <td class="text-xs-left">{{ props.item.deposit_date }}</td>
          <td class="text-xs-center">{{ props.item.user.name }}</td>
          <td class="text-xs-left">{{ props.item.type_of_payment }}</td>
          <td class="text-xs-center">
            <center>
              <span v-if="props.item.confirm == 1" class='badge badge-primary'>SI</span>
              <span v-else class='badge badge-secondary'>NO</span>
            </center>
          </td>
          <td class="text-xs-right">{{ props.item.created_at }}</td>

          <td class="justify-center layout px-0">
            <a href="#" @click="modalPayment(props.item.id)" v-if="$can('confirm-payments')" class="card-header-action ml-1 text-muted">
              <i class="fa-lg fas fa-check-double" style="color:#4dbd74"></i>
            </a>
            <a href="#" v-if="$can('read-payments')" data-toggle="tooltip" data-placement="bottom" title="Ver recibo" @click="getReceiptPayment(props.item.id)" class="card-header-action ml-1 text-muted">
              <i class="fa-lg fas fa-info-circle text-primary"></i>
            </a>
            <a href="#" v-if="$can('send-mail-payment')" @click="sendMail(props.item.id)" data-toggle="tooltip" data-placement="bottom" title="Enviar correo" class="card-header-action ml-1 text-muted">
              <i class="fa-lg fas fa-envelope" style="color:#ffa000"></i>
            </a>
            <a href="#" v-if="$can('update-payments')" @click="editPayment(props.item.id)" class="card-header-action ml-1 text-muted"><i class="fas fa-pencil-alt fa-lg"></i></a>
            <a  v-if="$can('delete-payments')" class="card-header-action ml-1" href="#" :disabled="submitingDestroy"  @click="destroy(props.item.id)">
                <i class="fas fa-spinner fa-spin fa-lg" v-if="submitingDestroy"></i>
                <i class="far fa-trash-alt fa-lg" v-else style="color:red"></i>
                <span class="d-md-down-none ml-1"></span>
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

    <!-- Modal Component -->
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
            <div class="form-group col-md-12">
                <v-checkbox
                  v-model="payment.confirm"
                  color="blue"
                  :label="`Confirmado`"
                ></v-checkbox>
            </div>
          </div>
        </div>
      </div>
      <b-button class="mt-3" variant="outline-primary" block @click="saveConfirm()">Confirmar</b-button>
    </b-modal>
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
      payments: [],
      payment:{
        confirm:false
      },
      docs: [],
      totalRows: 1,
      currentPage: 1,
      errors:{},
      checkbox: false,
      es:es,
      perPage: 10,
      pageOptions: [5, 10, 15],
      Bfilter: null,
      tableFields: [
        { key: 'id', label:'Folio',sortable: true },
        { key: 'customer.full_name',label:'Cliente',sortable: true,},
        { key: 'price',label:'Precio', sortable: true },
        { key: 'authorization_number', sortable: true,label:'Autorización' },
        { key: 'deposit_date', sortable: true,label:'Fecha deposito' },
        { key: 'user.name', sortable: true,label:'Agente' },
        { key: 'type_of_payment', sortable: true,label:'Forma de pago' },
        { key: 'confirm', sortable: true,label:'Confirmado' },
        { key: 'created_at', sortable: true,label:'Registrado' },
        { key: 'actions', sortable: false,label:'Acciones' },
      ],
      headers: [
        { value: 'id', align: 'left', text: 'Folio' },
        { value: 'customer.full_name',text:'Cliente',sortable: true,},
        { value: 'price',text:'Precio', sortable: true },
        { value: 'authorization_number', sortable: true,text:'Autorización' },
        { value: 'deposit_date', sortable: true,text:'Fecha deposito' },
        { value: 'user.name', sortable: true,text:'Agente' },
        { value: 'type_of_payment', sortable: true,text:'Forma de pago' },
        { value: 'confirm', sortable: true,text:'Confirmado' },
        { value: 'created_at', sortable: true,text:'Registrado' },
        { value: 'actions', sortable: false,text:'Acciones' },
      ],
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
      search: '',
      pagination: {'sortBy': 'id', 'descending': true, rowsPerPage: 15 },

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
    sendMail(paymentId){
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

              axios.post(`./api/payments/send-payment`,{
                id: paymentId
              })
              .then(response => {
                  swal("Enviado correctamente!", {
                    icon: 'success',
                    buttons: false,
                  });
              })
              .catch(error => {
                  this.$toasted.global.error('Error!' + error)
              })
              .then(() => {
                this.loading = false
              })
          }
          this.submitingDestroy = false
        })
    },
    saveConfirm() {
        axios.post('./api/payments/save-confirm',this.payment).then(response => {
            this.$refs.myModalRef2.hide();
            location.href = './payments'
        });
    },
    customFormatterLimit(date) {
        this.payment.date_received = moment(this.payment.date_received).format('YYYY/MM/DD');
        return moment(this.payment.date_received).format('YYYY/MM/DD');
    },
    onFiltered(filteredItems) {
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },
    getReceiptPayment(paymentId){
        axios.get(`./api/payments/get-payment/` + paymentId)
        .then(response => {
            this.$refs.myModalRef.show()
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
    hideModal() {
        this.$refs.myModalRef.hide()
    },
    modalPayment(paymentId){
          this.payment.id = paymentId;
          axios.get(`./api/payments/` + paymentId)
          .then(response => {
            this.payment = response.data;
          })
          this.$refs.myModalRef2.show();
    },
    getPayments () {
      this.loading = true
      this.payments = []

      localStorage.setItem("filtersTablePayments", JSON.stringify(this.filters));

      axios.post(`./api/payments/filter?page=${this.filters.pagination.current_page}`, this.filters)
      .then(response => {
        this.payments = response.data
        this.totalRows = this.payments.length
        delete response.data
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
