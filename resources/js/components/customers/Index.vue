<template>
  <div class="container">
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <h4 class="float-left pt-2">Clientes/Prospectos</h4>
      <div class="card-header-actions mr-1">
        <a class="btn btn-sm btn-success" href="./customers/create">Nuevo</a>
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
                  <a href="#" class="text-dark" @click.prevent="sort('first_name')">Nombre</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'first_name' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'first_name' && filters.orderBy.direction == 'desc'}"></i>
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
                  <a href="#" class="text-dark" @click.prevent="sort('rfc')">Rfc</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'rfc' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'rfc' && filters.orderBy.direction == 'desc'}"></i>
                </th>
                <th>
                  <a href="#" class="text-dark" @click.prevent="sort('typer_of_person')">Tipo</a>
                  <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'typer_of_person' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'typer_of_person' && filters.orderBy.direction == 'desc'}"></i>
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
              <tr v-for="(customer, index)   in customers">
                <td class="">{{customer.id}}</td>
                <td class="">{{fullName[index]}}</td>
                <td class="">{{customer.phone}}</td>
                <td class="">{{customer.email}}</td>
                <td class="">{{customer.rfc}}</td>
                <td class="">{{customer.type_of_person}}</td>
                <td class="">
                    <label v-if="customer.status == 1">Activo</label>
                    <label v-else>Inactivo</label>
                </td>
                <td class="">
                  <small>{{customer.created_at | moment("LL")}}</small> - <small class="text-muted">{{customer.created_at | moment("LT")}}</small>
                </td>
                <td class="">
                  <a href="#" data-toggle="tooltip" data-placement="bottom" title="Crear solicitud de cotizacion" @click="createOrder(customer.id)" class="card-header-action ml-1 text-muted">
                    <i class="fa-lg fas fa-plus-square text-primary"></i>
                  </a>
                  <a href="#" data-toggle="tooltip" data-placement="bottom" title="Enviar solicitud de cotizacion" @click="createOrderWhatsapp(customer.id)" class="card-header-action ml-1 text-muted">
                      <i class="fa-lg fab fa-whatsapp-square text-success"></i>
                  </a>
                  <a href="#" @click="editCustomer(customer.id)" class="card-header-action ml-1 text-muted"><i class="fa-lg fas fa-pencil-alt"></i></a>
                  <a class="card-header-action ml-1" href="#" :disabled="submitingDestroy"  @click="destroy(customer.id)">
                      <i class="fa-lg fas fa-spinner fa-spin" v-if="submitingDestroy"></i>
                      <i class="fa-lg far fa-trash-alt" v-else style="color:red"></i>
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
          <div class="no-items-found text-center mt-5" v-if="!loading && !customers.length > 0">
            <i class="icon-magnifier fa-3x text-muted"></i>
            <p class="mb-0 mt-3"><strong>No se pudo encontrar ningún artículo</strong></p>
            <p class="text-muted">Intenta cambiar los filtros o añadir uno nuevo.</p>
            <a class="btn btn-success" href="./customers/create" role="button">
              <i class="fa fa-plus"></i>&nbsp; Nuevo
            </a>
          </div>
          <content-placeholders v-if="loading">
            <content-placeholders-text/>
          </content-placeholders>
        </div>
    </div>
    <modal name="modal-uploader" :min-width="600"  height="auto" :draggable='true' transition="nice-modal-fade" :adaptive="true" :scrollable="true">
          <div class="col-md-12 card-body px-0">
              <div class="row" style="margin:auto;">
                  <div class="form-group col-md-12">
                      <uploader :options="options" class="uploader-example">
                          <uploader-unsupport></uploader-unsupport>
                          <uploader-drop>
                            <p>Drop files here to upload or</p>
                            <uploader-btn>select files</uploader-btn>
                            <uploader-btn :attrs="attrs">select images</uploader-btn>
                            <uploader-btn :directory="true">select folder</uploader-btn>
                          </uploader-drop>
                          <uploader-list></uploader-list>
                          <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                      </uploader>
                  </div>
              </div>
          </div>
    </modal>
    <modal style="z-index:10000" name="modal-folder" :min-width="600"  height="auto" :draggable='false' transition="nice-modal-fade" :adaptive="true" :scrollable="true">
          <div class="col-md-12 card-body px-0">
              <div class="row" style="margin:auto;">
                  <div class="form-group col-md-12">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th class="">
                              <a href="#" class="text-dark" @click.prevent="sort('name')">Name</a>
                              <i class="mr-1 fas" :class=""></i>
                          </th>
                          <th class="">
                              <a href="#" class="text-dark" @click.prevent="sort('created_at')">Upload Date</a>
                              <i class="mr-1 fas" :class=""></i>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="doc in docs">
                          <td class="">
                              <a :href="'/storage/' + doc.url" target="_blank">{{doc.name}}</a>
                          </td>
                          <td class="">
                            <small>{{doc.created_at | moment("LL")}}</small> - <small class="text-muted">{{doc.created_at | moment("LT")}}</small>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
              </div>
          </div>
    </modal>
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
    if (localStorage.getItem("filtersTablecustomers")) {
      this.filters = JSON.parse(localStorage.getItem("filtersTablecustomers"))
    } else {
      localStorage.setItem("filtersTablecustomers", this.filters);
    }
    this.getcustomers()
  },
  methods: {
    show (id) {
      this.options.query.customer_id = id
      this.$modal.show('modal-uploader',{
        title: 'Information',
      });
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
    getcustomers () {
      this.loading = true
      this.customers = []

      localStorage.setItem("filtersTablecustomers", JSON.stringify(this.filters));

      axios.post(`./api/customers/filter?page=${this.filters.pagination.current_page}`, this.filters)
      .then(response => {
        this.customers = response.data.data
        delete response.data.data
        this.filters.pagination = response.data
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
                    console.log(str)
                    window.open(
                    "https://wa.me/52" + data.cellphone + "?text=Favor de llenar la siguiente solicitud: %20"+ str.origin + '/api/customers/order/' + customerId,
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
