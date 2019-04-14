<template>
  <div class="container">
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <h4 class="float-left pt-2">{{ $t('Supplier.Supplier') }}</h4>
      <div class="card-header-actions mr-1">
        <a class="btn btn-sm btn-success" v-if="$can('read-suppliers')" href="./suppliers/create">{{ $t('Supplier.New_Supplier') }}</a>
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
            <input type="text" class="form-control" placeholder="Buscar.." v-model.trim="filters.search" @keyup.enter="filter">
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
            placeholder="Buscar..">
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
              <a href="#" class="text-dark" @click.prevent="sort('name')">{{ $t('tables.Name') }}</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'name' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'name' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('address')">{{ $t('tables.Address') }}</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'address' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'address' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('commission')">{{ $t('tables.Commission') }}</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'commission' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'commission' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('phone')">{{ $t('tables.Phone') }}</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'phone' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'phone' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('cellphone')">{{ $t('tables.Cellphone') }}</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'cellphone' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == '.cellphone' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('email')">{{ $t('tables.Email') }}</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'email' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == '.email' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('note')">{{ $t('tables.Note') }}</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'note' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == '.note' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('status')">{{ $t('tables.Status') }}</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'status' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'status' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th class="">
              <a href="#" class="text-dark" @click.prevent="sort('created_at')">{{ $t('tables.Registed') }}</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'created_at' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'created_at' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th class=""></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="supplier in suppliers">
            <td class="">{{supplier.id}}</td>
            <td class="">{{supplier.name}}</td>
            <td class="">{{supplier.address}}</td>
            <td class="">{{supplier.commission}}</td>
            <td class="">{{supplier.phone}}</td>
            <td class="">{{supplier.cellphone}}</td>
            <td class="">{{supplier.email}}</td>
            <td class="">{{supplier.note}}</td>
            <td class="">
                <label v-if="supplier.status == 1">Activo</label>
                <label v-else>Inactivo</label>
            </td>
            <td class="">
              <small>{{supplier.created_at | moment("LL")}}</small> - <small class="text-muted">{{supplier.created_at | moment("LT")}}</small>
            </td>
            <td class="">
              <a v-if="$can('update-suppliers')" href="#" @click="editCustomer(supplier.id)" class="card-header-action ml-1 text-muted"><i class="fas fa-pencil-alt"></i></a>
              <a v-if="$can('delete-suppliers')" class="card-header-action ml-1" href="#" :disabled="submitingDestroy"  @click="destroy(supplier.id)">
                  <i class="fas fa-spinner fa-spin" v-if="submitingDestroy"></i>
                  <i class="far fa-trash-alt" v-else style="color:red"></i>
                  <span class="d-md-down-none ml-1"></span>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
      </div>
      <div class="row" v-if='!loading && filters.pagination.total > 0'>
        <div class="col pt-2">
          {{filters.pagination.from}}-{{filters.pagination.to}} {{ $t('tables.of') }} {{filters.pagination.total}}
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
      <div class="no-items-found text-center mt-5" v-if="!loading && !suppliers.length > 0">
        <i class="icon-magnifier fa-3x text-muted"></i>
        <p class="mb-0 mt-3"><strong>No se pudo encontrar ningún artículo</strong></p>
        <p class="text-muted">Intenta cambiar los filtros o añadir uno nuevo.</p>
        <a class="btn btn-success" href="./suppliers/create" role="button">
          <i class="fa fa-plus"></i>&nbsp; {{ $t('Supplier.New_Supplier') }}
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
      suppliers: [],
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
          target: './api/suppliers/upload',
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
    if (localStorage.getItem("filtersTableprospectings")) {
      this.filters = JSON.parse(localStorage.getItem("filtersTableprospectings"))
    } else {
      localStorage.setItem("filtersTableprospectings", this.filters);
    }
    this.getProspectings()
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
    getProspectings () {
      this.loading = true
      this.suppliers = []

      localStorage.setItem("filtersTableprospectings", JSON.stringify(this.filters));

      axios.post(`./api/suppliers/filter?page=${this.filters.pagination.current_page}`, this.filters)
      .then(response => {
        this.suppliers = response.data.data
        delete response.data.data
        this.filters.pagination = response.data
        this.loading = false
      })
    },
    getDocuments (id) {
        this.loading = true
        axios.post('./api/suppliers/docs',{
            customer_id: id
        }).then(response => {
            //console.log(response.data)
            this.docs = response.data
            this.loading = false
        });
    },
    editCustomer (customerId) {
      location.href = `./suppliers/${customerId}/edit`
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
    ,destroy (customerId) {
      if (!this.submitingDestroy) {
        this.submitingDestroy = true
        swal({
          title: this.$t('Supplier.Warning_Title') ,
          text:this.$t('Supplier.Warning_Delete'),
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete(`./api/suppliers/${customerId}`)
            .then(response => {
                this.$toasted.global.error('Deleted supplier!')
                location.href = './suppliers'
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
