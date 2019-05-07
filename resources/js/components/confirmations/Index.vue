<template>
  <div class="container">
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <div class="card-header-actions mr-1">

      </div>
    </div>
    <v-app v-cloak>
    <v-card>
      <v-toolbar-title>
        <div class="h3 text-muted text-right mb-12">
          <h4 style="margin-right:10px;margin-top:10px;" class="text-muted text-uppercase font-weight-bold">
              Confirmación de pagos
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
        :items="productDetailSales"
        :search="search"
        v-bind:pagination.sync="pagination"
      >
        <template v-slot:items="props">
          <td class="text-xs-center">{{ props.item.id }}</td>
          <td class="text-xs-left">{{ props.item.price }}</td>
          <td class="text-xs-center">{{ props.item.confirmation }}</td>
          <td class="text-xs-left">{{ props.item.supplier.name }}</td>
          <td class="text-xs-left">{{ props.item.date_payment_supplier }}</td>
          <td class="text-xs-center">
            <center>
              <span v-if="props.item.confirm == 1" class='badge badge-primary'>SI</span>
              <span v-else class='badge badge-secondary'>NO</span>
            </center>
          </td>
          <td class="text-xs-center">
            <center>
              <span v-if="props.item.confirm == 1" class='badge badge-primary'>SI</span>
              <span v-else class='badge badge-secondary'>NO</span>
            </center>
          </td>
          <td class="text-xs-right">{{ props.item.created_at }}</td>

          <td class="justify-center layout px-0">
            <a href="#" @click="modalPaymentProductDetails(props.item.id)" v-if="$can('add-payments-confirmations')" class="card-header-action ml-1 text-muted">
              <i class="fa-lg fas fa-hand-holding-usd text-primary"></i>
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
    <!--
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
          </div>
        </div>
      </div>
      <b-button class="mt-3" variant="outline-primary" block @click="saveConfirm()">Confirmar</b-button>
    </b-modal>
    -->
  </div>
</template>

<script>
let csrf_token = $('meta[name="csrf-token"]').attr('content');
export default {
  data () {
    return {
      customers: [{
          first_name: '',
          last_name: ''}
      ],
      docs: [],
      payment:[],
      errors:null,
      search:null,
      path_pdf:null,
      headers: [
        { value: 'id', align: 'left', text: 'Folio' },
        { value: 'price',text:'Precio Tarifa',sortable: true,},
        { value: 'confirmation',text:'Confirmación', sortable: true },
        { value: 'supplier.name', sortable: true,text:'Proveedor' },
        { value: 'date_payment_supplier', sortable: true,text:'Fecha pago de proveedor' },
        { value: 'invoice', sortable: true,text:'Factura' },
        { value: 'liquidation', sortable: true,text:'Liquidado' },
        { value: 'created_at', sortable: true,text:'Registrado' },
        { value: 'actions', sortable: false,text:'Acciones' },
      ],
      productDetailSales:[],
      pagination: {'sortBy': 'id', 'descending': true, rowsPerPage: 15 },
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
          target: './api/customers/upload',
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
        return this.customers.map(function(customer) {
            return customer.first_name + ' ' + customer.last_name;
        });
    }
  },
  mounted () {
    this.getProductDetailSales()
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
    hide () {
      this.$modal.hide('modal-uploader');
    },
    showFolder (id) {
      this.$modal.show('modal-folder',{});
      this.getDocuments(id)
    },
    hideFolder () {
      this.$modal.hide('modal-folder');
    },
    getProductDetailSales () {
      this.loading = true
      this.productDetailSales = []

      axios.post(`./api/confirmations/getDetails`, this.filters)
      .then(response => {
        this.productDetailSales = response.data
        this.loading = false
      })
    },
    getDocuments (id) {
        this.loading = true
        axios.post('./api/customers/docs',{
            customer_id: id
        }).then(response => {
            //console.log(response.data)
            this.docs = response.data
            this.loading = false
        });
    },
    createOrderWhatsapp(customerId){
        //Cambiar por numero de telefono seleccionado
        axios.post('./api/customers/order/whatsapp',{
            customer_id: customerId
        }).then(response => {
            swal("Espere unos segundos..", {
                icon: 'info',
                buttons: false,
            });

            axios.get(`./api/customers/` + customerId)
            .then(response => {
                let data = response.data;
                if(data.cellphone == null || data.cellphone == ''){
                    swal("Error..", {
                      text:'No se puede enviar el whatsapp porque no existe un telefono registrado.',
                      icon: 'error',
                      timer: 3000,
                      buttons: false,
                    });
                    return false;
                }else{
                    let str = window.location
                    let url_location = str.pathname;
                    let url = url_location.split('/');
                    console.log(url)
                    window.open(
                    "https://wa.me/52" + data.cellphone + "?text=Favor de llenar la siguiente solicitud: %20"+ str.origin + '/' + url[1] + '/api/customers/order/' + customerId,
                    '_blank');
                }

            })
            .catch(error => {
                this.$toasted.global.error('Cotización no encontrada!',error)
            })
            .then(() => {
              this.loading = false
            });
        });

    },
    createOrder(customerId){
        window.open(
            `./api/customers/order/${customerId}`,
            '_blank' // <- This is what makes it open in a new window.
        );
    },
    editCustomer (customerId) {
      location.href = `./customers/${customerId}/edit`
    },
    // filters
    filter() {
      this.filters.pagination.current_page = 1
      this.getcustomers()

    },
    changeSize (perPage) {
      this.filters.pagination.current_page = 1
      this.filters.pagination.per_page = perPage
      this.getcustomers()
    },
    sort (column) {
      if(column == this.filters.orderBy.column) {
        this.filters.orderBy.direction = this.filters.orderBy.direction == 'asc' ? 'desc' : 'asc'
      } else {
        this.filters.orderBy.column = column
        this.filters.orderBy.direction = 'asc'
      }

      this.getcustomers()
    },
    changePage (page) {
      this.filters.pagination.current_page = page
      this.getcustomers()
    }
    ,destroy (customerId) {
      if (!this.submitingDestroy) {
        this.submitingDestroy = true
        swal({
          title: "¿Esta seguro?",
          text: "Una vez eliminado, no podrá recuperar este cliente!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete(`./api/customers/${customerId}`)
            .then(response => {
                this.$toasted.global.error('Deleted customer!')
                location.href = './customers'
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
