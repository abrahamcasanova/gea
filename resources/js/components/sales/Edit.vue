<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Editando cotización</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="updated">
              <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
              <i class="fas fa-check" v-else></i>
              <span class="ml-1">Actualizar</span>
            </a>
          </div>
        </div>
          <sales-input :quote_id="quoteId" :saleEdit="saleEdit" :type="type" :quote_sale="quoteSale"></sales-input>
      </div>
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
      saleEdit: {},
      quoteId: null,
      type: "edit",
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
      submiting: false,
      submitingDestroy: false,
    }
  },
  computed: {
    fullName: function() {
        return this.sales.map(function(quote) {
            //return customer_order.first_name + ' ' + customer_order.last_name;
        });
    }
  },
  mounted () {
    this.getSale();
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
    sendWhatsapp(quoteId){
        axios.get(`./api/quotes/get-quote/` + quoteId)
        .then(response => {
            let data = response.data;
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
                window.open("https://wa.me/52"+ data.customer_order.customer.cellphone +"?text=Estimado " + data.customer_order.customer.full_name + ", adjunto encontrará la cotización con destino a " + data.customer_order.travel_destination,'_blank');
            }
            
        })
        .catch(error => {
            this.$toasted.global.error('telefono no encontrada!',error)
        })
        .then(() => {
          this.loading = false
        })
        
    },
    getSale () {
      this.loading = true
      let str = window.location.pathname
      let res = str.split("/")
      
      axios.get(`../../api/sales/get-sale/` + res[3])
        .then(response => {
            this.quote = response.data;
            this.saleEdit = response.data;
            this.quote_detail_add.quote_id = response.data['id'];

        })
        .catch(error => {
            this.$toasted.global.error('User does not exist!')
            //location.href = '../../customers'
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
</script>
