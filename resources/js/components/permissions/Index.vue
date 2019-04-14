<template>
  <div class="container">
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <h4 class="float-left pt-2">Permisos</h4>
      <div class="card-header-actions mr-1">
        <a class="btn btn-sm btn-success" href="./permissions/create">Nuevo permiso</a>
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
              <a href="#" class="text-dark" @click.prevent="sort('id')">ID</a>
              <i class="ml-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'id' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'id' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th class="">
              <a href="#" class="text-dark" @click.prevent="sort('name')">Name</a>
              <i class="ml-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'name' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'name' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('display_name')">Permission</a>
              <i class="ml-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'display_name' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'display_name' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th class="">
              <a href="#" class="text-dark" @click.prevent="sort('created_at')">Created</a>
              <i class="ml-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'created_at' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'created_at' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th class=""></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="permission in permissions" >
            <td class="">{{permission.id}}</td>
            <td>{{permission.display_name}}</td>
            <td class="">{{permission.name}}</td>
            <td class="">
              <small v-if="permission.created_at">{{permission.created_at | moment("LL")}}</small> - <small class="text-muted">{{permission.created_at | moment("LT")}}</small>
            </td>
            <td class="">
              <a href="#" @click="editPermission(permission.id)" class="text-muted"><i class="fas fa-pencil-alt"></i></a>
              <a class="card-header-action ml-1" href="#" :disabled="submitingDestroy"  @click="destroy(permission.id)">
                <i class="fas fa-spinner fa-spin" v-if="submitingDestroy"></i>
                <i class="far fa-trash-alt" v-else></i>
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
                <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page -  1)"><i class="fas fa-angle-left"></i></a>
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
      <div class="no-items-found text-center mt-5" v-if="!loading && !permissions.length > 0">
        <i class="icon-magnifier fa-3x text-muted"></i>
        <p class="mb-0 mt-3"><strong>No se pudo encontrar ningún artículo</strong></p>
        <p class="text-muted">Intenta cambiar los filtros o añadir uno nuevo.</p>
        <a class="btn btn-success" href="/users/create" permission="button">
          <i class="fa fa-plus"></i>&nbsp; Nuevo Permiso
        </a>
      </div>
      <content-placeholders v-if="loading">
        <content-placeholders-text/>
      </content-placeholders>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      permissions: [],
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
      permissionsCount: 0,
      loading: true,
      submitingDestroy: false
    }
  },
  mounted () {
    if (localStorage.getItem("filtersTablePermissions")) {
      this.filters = JSON.parse(localStorage.getItem("filtersTablePermissions"))
    } else {
      localStorage.setItem("filtersTablePermissions", this.filters);
    }
    this.getPermissionsCount()
    this.getPermissions()
  },
  methods: {
    getPermissions () {
      this.loading = true
      this.permissions = []
      localStorage.setItem("filtersTablePermissions", JSON.stringify(this.filters));
      axios.post(`./api/permissions/filter?page=${this.filters.pagination.current_page}`, this.filters)
      .then(response => {
        this.permissions = response.data.data
        delete response.data.data
        this.filters.pagination = response.data
        this.loading = false
      })
    },
    editPermission(permisionId) {
      location.href = `./permissions/${permisionId}/edit`
    },
    getPermissionsCount() {
      axios.get(`./api/permissions/count`)
      .then(response => {
        this.permissionsCount = response.data
      })
    },
    // Filters
    filter() {
      this.filters.pagination.current_page = 1
      this.getPermissions()
    },
    changeSize (perPage) {
      this.filters.pagination.current_page = 1
      this.filters.pagination.per_page = perPage
      this.getPermissions()
    },
    sort (column) {
      if(column == this.filters.orderBy.column) {
        this.filters.orderBy.direction = this.filters.orderBy.direction == 'asc' ? 'desc' : 'asc'
      } else {
        this.filters.orderBy.column = column
        this.filters.orderBy.direction = 'asc'
      }

      this.getPermissions()
    },
    changePage (page) {
      this.filters.pagination.current_page = page
      this.getPermissions()
    },destroy (permisionId) {
      if (!this.submitingDestroy) {
        this.submitingDestroy = true
        swal({
          title: "¿Esta seguro?",
          text: "Una vez eliminado, no podrá recuperar este permiso!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete(`./api/permissions/${permisionId}`)
            .then(response => {
              this.$toasted.global.error('Permiso eliminado!')
              location.href = './permissions'
            })
            .catch(error => {
              this.errors = error.response.data.errors
            })
          }
          this.submitingDestroy = false
        })
      }
    },
  }
}
</script>
