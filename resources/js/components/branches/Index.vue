<template>
  <div class="container">
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <h4 class="float-left pt-2">branches</h4>
      <div class="card-header-actions mr-1">
        <a class="btn btn-sm btn-success" href="/branches/create">New branch</a>
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
              <a href="#" class="text-dark" @click.prevent="sort('name')">Name</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'name' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'name' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('status')">Status</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'status' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'status' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="branch in branches">
            <td class="">{{branch.id}}</td>
            <td class="">{{branch.name}}</td>
            <td class="d-none d-sm-table-cell">
                <label v-if="branch.status == 1">Activo</label>
                <label v-else>Inactivo</label>
            </td>
            <td class="d-none d-sm-table-cell">
              <a href="#" @click="editCustomer(branch.id)" class="card-header-action ml-1 text-muted"><i class="fas fa-pencil-alt"></i></a>
              <a class="card-header-action ml-1" href="#" :disabled="submitingDestroy"  @click="destroy(branch.id)">
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
      <div class="no-items-found text-center mt-5" v-if="!loading && !branches.length > 0">
        <i class="icon-magnifier fa-3x text-muted"></i>
        <p class="mb-0 mt-3"><strong>No se pudo encontrar ningún artículo</strong></p>
        <p class="text-muted">Intenta cambiar los filtros o añadir uno nuevo.</p>
        <a class="btn btn-success" href="/branches/create" role="button">
          <i class="fa fa-plus"></i>&nbsp; New Branch
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
      branches: [],
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
          target: '/api/branches/upload',
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
    if (localStorage.getItem("filtersTableBranches")) {
      this.filters = JSON.parse(localStorage.getItem("filtersTableBranches"))
    } else {
      localStorage.setItem("filtersTableBranches", this.filters);
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
      this.branches = []

      localStorage.setItem("filtersTableBranches", JSON.stringify(this.filters));

      axios.post(`/api/branches/filter?page=${this.filters.pagination.current_page}`, this.filters)
      .then(response => {
        this.branches = response.data.data
        delete response.data.data
        this.filters.pagination = response.data
        this.loading = false
      })
    },
    getDocuments (id) {
        this.loading = true
        axios.post('/api/branches/docs',{
            customer_id: id
        }).then(response => {
            //console.log(response.data)
            this.docs = response.data
            this.loading = false
        });
    },
    editCustomer (customerId) {
      location.href = `/branches/${customerId}/edit`
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
          text: "Once deleted, you will not be able to recover this branch!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete(`/api/branches/${customerId}`)
            .then(response => {
                this.$toasted.global.error('Deleted branch!')
                location.href = '/branches'
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
