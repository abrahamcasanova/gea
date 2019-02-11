<template>
  <div class="container">
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <h4 class="float-left pt-2">customers</h4>
      <div class="card-header-actions mr-1">
        <a class="btn btn-sm btn-success" href="/customers/create">New customers</a>
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
            <th class="d-none d-sm-table-cell">
              <a href="#" class="text-dark" @click.prevent="sort('id')">ID</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'id' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'id' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('first_name')">First Name</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'first_name' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'first_name' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('last_name')">Last Name</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'last_name' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'last_name' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('email')">Email</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'email' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'email' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('typer_of_person')">Type</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'typer_of_person' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'typer_of_person' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('group_id')">Group/SubGroup</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'group_id' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'group_id' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('status')">Status</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'status' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'status' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th class="d-none d-sm-table-cell">
              <a href="#" class="text-dark" @click.prevent="sort('created_at')">Registered</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'created_at' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'created_at' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th class="d-none d-sm-table-cell"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="customer in customers">
            <td class="">{{customer.id}}</td>
            <td class="">{{customer.first_name}}</td>
            <td class="">{{customer.last_name}}</td>
            <td class="">{{customer.email}}</td>
            <td class="d-none d-sm-table-cell">{{customer.type_of_person}}</td>
            <td class="d-none d-sm-table-cell">
                <label v-if="customer.group">{{customer.group ? customer.group.name:''}}</label>
                <label v-else>{{customer.group ? customer.group.name:''}}</label>
            </td>
            <td class="d-none d-sm-table-cell">
                <label v-if="customer.status == 1">Activo</label>
                <label v-else>Inactivo</label>
            </td>
            <td class="d-none d-sm-table-cell">
              <small>{{customer.created_at | moment("LL")}}</small> - <small class="text-muted">{{customer.created_at | moment("LT")}}</small>
            </td>
            <td class="d-none d-sm-table-cell">
              <a href="#" @click="editCustomer(customer.id)" class="card-header-action ml-1 text-muted"><i class="fas fa-pencil-alt"></i></a>
              <a href="#" @click="show(customer.id)" class="card-header-action ml-1 text-muted"><i class="fas fa-upload"></i></a>
              <a href="#" @click="showFolder(customer.id)" class="card-header-action ml-1 text-muted"><i class="fas fa-folder"></i></a>
              <a class="card-header-action ml-1" href="#" :disabled="submitingDestroy"  @click="destroy(customer.id)">
                  <i class="fas fa-spinner fa-spin" v-if="submitingDestroy"></i>
                  <i class="far fa-trash-alt" v-else style="color:red"></i>
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
        <a class="btn btn-success" href="/customers/create" role="button">
          <i class="fa fa-plus"></i>&nbsp; New Customer
        </a>
      </div>
      <content-placeholders v-if="loading">
        <content-placeholders-text/>
      </content-placeholders>
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
                          <th class="d-none d-sm-table-cell">
                              <a href="#" class="text-dark" @click.prevent="sort('name')">Name</a>
                              <i class="mr-1 fas" :class=""></i>
                          </th>
                          <th class="d-none d-sm-table-cell">
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
                          <td class="d-none d-sm-table-cell">
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
      customers: [],
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
          target: '/api/customers/upload',
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

      axios.post(`/api/customers/filter?page=${this.filters.pagination.current_page}`, this.filters)
      .then(response => {
        this.customers = response.data.data
        delete response.data.data
        this.filters.pagination = response.data
        this.loading = false
      })
    },
    getDocuments (id) {
        this.loading = true
        axios.post('/api/customers/docs',{
            customer_id: id
        }).then(response => {
            //console.log(response.data)
            this.docs = response.data
            this.loading = false
        });
    },
    editCustomer (customerId) {
      location.href = `/customers/${customerId}/edit`
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
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover this customer!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete(`/api/customers/${customerId}`)
            .then(response => {
                this.$toasted.global.error('Deleted customer!')
                location.href = '/customers'
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
