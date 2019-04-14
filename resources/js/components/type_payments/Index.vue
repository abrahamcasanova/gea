<template>
  <div class="container">
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <h4 class="float-left pt-2">Formas de pago</h4>
      <div class="card-header-actions mr-1">
        <a class="btn btn-sm btn-success" v-if="$can('create-type-payments')" href="./type_payments/create">Nuevo</a>
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
              <a href="#" class="text-dark" @click.prevent="sort('name')">Nombre</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'name' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'name' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('with_authorization')">Autorización</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'with_authorization' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'with_authorization' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('percentage')">Porcentaje</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'percentage' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'percentage' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th class=""></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="type_payment in type_payments">
            <td class="">{{type_payment.id}}</td>
            <td class="">{{type_payment.name}}</td>
            <td class="">
              <label v-if="type_payment.with_authorization == 1">SI</label>
              <label v-else>No</label>
            </td>
            <td class="">
              <label v-if="type_payment.with_percentage == 1">SI</label>
              <label v-else>No</label>
            </td>
            <td class="">
              <a href="#" v-if="$can('update-type-payments')" @click="editCustomer(type_payment.id)" class="card-header-action ml-1 text-muted"><i class="fas fa-pencil-alt fa-lg"></i></a>
              <a v-if="$can('delete-type-payments')" class="card-header-action ml-1" href="#" :disabled="submitingDestroy"  @click="destroy(type_payment.id)">
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
      <div class="no-items-found text-center mt-5" v-if="!loading && !type_payments.length > 0">
        <i class="icon-magnifier fa-3x text-muted"></i>
        <p class="mb-0 mt-3"><strong>No se pudo encontrar ningún artículo</strong></p>
        <p class="text-muted">Intenta cambiar los filtros o añadir uno nuevo.</p>
        <a class="btn btn-success" href="./type_payments/create" role="button">
          <i class="fa fa-plus"></i>&nbsp; Nuevo destino
        </a>
      </div>
      <content-placeholders v-if="loading">
        <content-placeholders-text/>
      </content-placeholders>
    </div>
  </div>
</template>

<script>
let csrf_token = $('meta[name="csrf-token"]').attr('content');
export default {
  data () {
    return {
      type_payments: [],
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
    }
  },
  mounted () {
    if (localStorage.getItem("filtersTableType_payments")) {
      this.filters = JSON.parse(localStorage.getItem("filtersTableType_payments"))
    } else {
      localStorage.setItem("filtersTableType_payments", this.filters);
    }
    this.getTypeOfPayments()
  },
  methods: {
    getTypeOfPayments () {
      this.loading = true
      this.type_payments = []

      localStorage.setItem("filtersTableType_payments", JSON.stringify(this.filters));

      axios.post(`./api/type_of_payments/filter?page=${this.filters.pagination.current_page}`, this.filters)
      .then(response => {
        this.type_payments = response.data.data
        delete response.data.data
        this.filters.pagination = response.data
        this.loading = false
      })
    },
    editCustomer (customerId) {
      location.href = `./type_payments/${customerId}/edit`
    },
    // filters
    filter() {
      this.filters.pagination.current_page = 1
      this.getTypeOfPayments()

    },
    changeSize (perPage) {
      this.filters.pagination.current_page = 1
      this.filters.pagination.per_page = perPage
      this.getTypeOfPayments()
    },
    sort (column) {
      if(column == this.filters.orderBy.column) {
        this.filters.orderBy.direction = this.filters.orderBy.direction == 'asc' ? 'desc' : 'asc'
      } else {
        this.filters.orderBy.column = column
        this.filters.orderBy.direction = 'asc'
      }

      this.getTypeOfPayments()
    },
    changePage (page) {
      this.filters.pagination.current_page = page
      this.getTypeOfPayments()
    }
    ,destroy (customerId) {
      if (!this.submitingDestroy) {
        this.submitingDestroy = true
        swal({
          title: "¿Esta seguro?",
          text: "Una vez eliminado, no podrá recuperar esta forma de pago!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete(`./api/type_of_payments/${customerId}`)
            .then(response => {
                this.$toasted.global.error('Forma de pago Eliminado!')
                location.href = './type_payments'
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
