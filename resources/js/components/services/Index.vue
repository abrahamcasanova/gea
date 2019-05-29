<template>
  <div class="container">
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <h4 class="float-left pt-2">Servicios</h4>
      <div class="card-header-actions mr-1">
        <a class="btn btn-sm btn-success" v-if="$can('read-services')" href="./services/create">
          Nuevo servicio
        </a>
      </div>
    </div>
    <div class="card-body px-0">
      <div class="table-responsive">
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
              :items="services"
              :search="search"
              v-bind:pagination.sync="pagination"
            >
              <template v-slot:items="props">
                <td class="text-xs-let">{{ props.item.id }}</td>
                <td class="text-xs-left">{{ props.item.name }}</td>
                <td class="text-xs-left">{{ props.item.date_payment }}</td>
                <td class="text-xs-left">{{ props.item.frequency }}</td>
                <td class="text-xs-left">{{ props.item.note }}</td>
                <td class="text-xs-center">{{ props.item.total | currency }}</td>
                <td class="justify-center layout px-0">
                  <a href="#" @click="modalPayment(props.item.id)" v-if="$can('read-services')"
                      data-toggle="tooltip" data-placement="bottom" title="Ver detalle" class="card-header-action ml-1 text-muted">
                    <i class="fa-lg fas fa-eye" style="color:#4dbd74"></i>
                  </a>
                  <a href="#" v-if="$can('add-payment-services')" @click="addPayment(props.item.id)"
                    data-toggle="tooltip" data-placement="bottom" title="Nuevo pago"
                    class="card-header-action ml-1 text-muted"> <i class="fa-lg fas fa-wallet text-primary"></i>
                  </a>
                  <a href="#" v-if="$can('update-services')" @click="editPayment(props.item.id)" class="card-header-action ml-1 text-muted"><i class="fas fa-pencil-alt fa-lg"></i></a>
                  <a  v-if="$can('delete-services')" class="card-header-action ml-1" href="#" :disabled="submitingDestroy"  @click="destroy(props.item.id)">
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

          <b-modal size="md" id="modal1" ref="myModalRef" hide-footer title="Agregar pago">
            <div class="d-block">
              <div class="col-md-12 card-body px-0">
                <div class="row">
                  <div class="form-group col-md-6">
                      <label>Pagado</label>
                      <vue-numeric class="form-control"  :class="{'is-invalid': errors.amount}" currency="$" separator="," :precision="2" v-model="payment.amount"></vue-numeric>
                      <div class="invalid-feedback" v-if="errors.amount">{{errors.amount[0]}}</div>
                  </div>
                  <div class="form-group col-md-6">
                      <label>Fecha de pago</label>
                      <datepicker :bootstrap-styling="true" :language="es" :format="customFormatterLimit" v-model="payment.date" :input-class="{'is-invalid': errors.date}"></datepicker>
                      <div class="invalid-feedback" v-if="errors.date">{{errors.date[0]}}</div>
                  </div>
                  <div class="form-group col-md-12">
                      <label>Forma de pago</label>
                      <multiselect
                        v-model="payment.type_of_payment_id"
                        :options="type_of_payment"
                        openDirection="bottom"
                        track-by="id"
                        label="name"
                        :class="{'border border-danger rounded': errors.type_of_payment}">
                      </multiselect>
                      <div class="invalid-feedback" v-if="errors.date">{{errors.date[0]}}</div>
                  </div>
                  <div class="form-group col-md-12">
                      <label>Nota</label>
                      <textarea class="form-control" rows="5" :class="{'is-invalid': errors.note}"  v-model="payment.note"></textarea>
                      <div class="invalid-feedback" v-if="errors.note">{{errors.note[0]}}</div>
                  </div>
                </div>
              </div>
            </div>
            <b-button class="mt-2" variant="outline-success" block @click="savePaymentHideModal">Guardar</b-button>
            <b-button class="mt-2" variant="outline-danger" block @click="hideModal">Cerrar</b-button>
          </b-modal>

          <b-modal size="lg" id="modal2" ref="myModalRef2" hide-footer title="Detalle">
            <div class="d-block">
              <div class="col-md-12 card-body px-0">
                <div class="row">
                <div class="col-md-12">
                    <v-card>
                      <v-data-table
                        :headers="headersPayments"
                        :items="detailPayments"
                        :search="search"
                        v-bind:pagination.sync="pagination"
                      >
                        <template v-slot:items="props">
                          <td class="text-xs-let">{{ props.item.id }}</td>
                          <td class="text-xs-left">{{ props.item.amount | currency }}</td>
                          <td class="text-xs-left">{{ props.item.type_of_payment ? props.item.type_of_payment.name:null }}</td>
                          <td class="text-xs-left">{{ props.item.date }}</td>
                          <td class="text-xs-left">{{ props.item.note }}</td>

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
                </div>
                </div>
              </div>
            </div>
            <b-button class="mt-2" variant="outline-danger" block @click="hideModalDetail">Cerrar</b-button>
          </b-modal>

        </v-app>
      </div>
    </div>
  </div>
</template>

<script>
let csrf_token = $('meta[name="csrf-token"]').attr('content');
import Datepicker from 'vuejs-datepicker';
import {en, es} from 'vuejs-datepicker/dist/locale'
import moment from 'moment';

export default {
  components: {
      Datepicker,
  },
  data () {
    return {
      services: [],
      errors:{},
      es: es,
      payment:{},
      detailPayments: [],
      docs: [],
      type_of_payment: [],
      headers: [
        { value: 'id', align: 'left', text: 'Folio' },
        { value: 'name',text:'Servicio',sortable: true,},
        { value: 'date_payment',text:'Dia para para pagar',sortable: true,},
        { value: 'frequency',text:'Frecuencia',sortable: true,},
        { value: 'note',text:'Nota',sortable: true,},
        { value: 'total',text:'Total pagado', sortable: true },
        { value: 'actions', sortable: false,text:'Acciones' },
      ],
      headersPayments: [
        { value: 'id', align: 'left', text: 'Folio' },
        { value: 'amount',text:'Pagado',sortable: true,},
        { value: 'type_of_payment_id',text:'Forma de pago',sortable: true,},
        { value: 'date',text:'Dia de pago',sortable: true,},
        { value: 'note',text:'Nota',sortable: true,},
      ],
      search: '',
      pagination: {'sortBy': 'id', 'descending': true, rowsPerPage: 15 },
      loading: true,
      submitingDestroy: false,
    }
  },
  mounted () {
    this.getServices();
    this.getTypePayments();
  },
  methods: {
    addPayment (id) {
      this.payment.service_id = id;
      this.$refs.myModalRef.show()

    },
    getTypePayments() {
        axios.get(`./api/type_of_payments/all`).then(response => {
            this.type_of_payment = response.data
        })
        .catch(error => {
            this.errors = error.response.data.errors
        })
    },
    modalPayment (id) {
      axios.get('./api/services-payment/get-service-payment/' +  id)
      .then(response => {
        this.detailPayments = response.data
        this.$refs.myModalRef2.show()
        this.loading = false
      })
    },
    customFormatterLimit(date) {
          this.payment.date = moment(this.payment.date).format('YYYY/MM/DD');
          return moment(this.payment.date).format('YYYY/MM/DD');
    },
    savePaymentHideModal(){
      axios.post(`./api/services-payment/store`, this.payment)
      .then(response => {
          this.$toasted.global.error('Pago guardado correctamente!')
          location.href = './services'
      })
      .catch(error => {
        this.errors = error.response.data.errors
        this.submiting = false
      })
    },
    hideModal () {
      this.$refs.myModalRef.hide()
    },
    hideModalDetail () {
      this.$refs.myModalRef2.hide()
    },
    getServices () {
      this.loading = true
      this.suppliers = []

      axios.get('./api/services/all')
      .then(response => {
        this.services = response.data
        this.loading = false
      })
    },
    editPayment (serviceId) {
      location.href = `./services/${serviceId}/edit`
    },
    // filters
    filter() {
      this.filters.pagination.current_page = 1
      this.getProspectings()

    },
    changeSize (perPage) {
      this.filters.pagination.current_page = 1
      this.filters.pagination.per_page = perPage
      this.getProspectings()
    },
    sort (column) {
      if(column == this.filters.orderBy.column) {
        this.filters.orderBy.direction = this.filters.orderBy.direction == 'asc' ? 'desc' : 'asc'
      } else {
        this.filters.orderBy.column = column
        this.filters.orderBy.direction = 'asc'
      }

      this.getProspectings()
    },
    changePage (page) {
      this.filters.pagination.current_page = page
      this.getProspectings()
    }
    ,destroy (serviceId) {
      if (!this.submitingDestroy) {
        this.submitingDestroy = true
        swal({
          title: this.$t('Supplier.Warning_Title') ,
          text: 'Una vez eliminado, no podrá recuperar este servicio!',
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete(`./api/services/${serviceId}`)
            .then(response => {
                this.$toasted.global.error('Servicio eliminado!')
                location.href = './services'
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
