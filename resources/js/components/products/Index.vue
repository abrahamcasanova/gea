<template>
  <div class="container">
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <h4 class="float-left pt-2">productos</h4>
      <div class="card-header-actions mr-1">
        <a class="btn btn-sm btn-success" v-if="$can('create-products')" href="./products/create">{{ $t('Product.New_Product') }}</a>
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
              <a href="#" class="text-dark" @click.prevent="sort('product_type_id')">Tipo</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'product_type_id' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'product_type_id' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('url_image')">Imagen</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'url_image' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'url_image' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('location')">Ubicación</a>
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
          <tr v-for="product in products">
            <td class="">{{product.id}}</td>
            <td class="">{{product.name}}</td>
            <td class="">{{product.product_types.name}}</td>
            <td class="">
              <div class="media">
                <div class="avatar_product float-left mr-3">
                  <img class="img-avatar" :src="'./storage/app/public/product_img/'+product.url_image">
                </div>
                <div class="media-body">
                  <a  target="_blank" v-bind:href="'./storage/app/public/product_img/'+product.url_image">{{ product.url_image }}
                  </a>
                  <div class="small text-muted">
                    <star-rating read-only :show-rating='false' v-if="product.type == ['Estrellas']" v-model="product.category" :value="product.category" :item-size="20"></star-rating>
                    <image-rating read-only :show-rating='false' v-if="product.type == ['Diamantes']" v-model="product.category" :value="product.category" :item-size="30" src="./public/img/diamante.png"></image-rating>
                  </div>
                </div>
              </div>
            </td>
            <td class="">{{product.location}}</td>
            <td class="">
              <small>{{product.created_at | moment("LL")}}</small> - <small class="text-muted">{{product.created_at | moment("LT")}}</small>
            </td>
            <td class="">
              <a href="#" @click="exportWord(product.id,product.name)" data-toggle="tooltip" title="Exportar a word" class="card-header-action ml-1 text-muted"><i class="far fa-file-word text-primary fa-lg"></i></a>
              <a href="#" v-if="$can('update-products')" @click="editCustomer(product.id)" class="card-header-action ml-1 text-muted"><i class="fas fa-pencil-alt fa-lg"></i></a>
              <a v-if="$can('delete-products')" class="card-header-action ml-1" href="#" :disabled="submitingDestroy"  @click="destroy(product.id)">
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
      <div class="no-items-found text-center mt-5" v-if="!loading && !products.length > 0">
        <i class="icon-magnifier fa-3x text-muted"></i>
        <p class="mb-0 mt-3"><strong>No se pudo encontrar ningún artículo</strong></p>
        <p class="text-muted">Intenta cambiar los filtros o añadir uno nuevo.</p>
        <a class="btn btn-success" href="./products/create" role="button">
          <i class="fa fa-plus"></i>&nbsp; {{ $t('Product.New_Product') }}
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
      products: [],
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
          target: './api/products/upload',
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
    if (localStorage.getItem("filtersTableproducts")) {
      this.filters = JSON.parse(localStorage.getItem("filtersTableproducts"))
    } else {
      localStorage.setItem("filtersTableproducts", this.filters);
    }
    this.getProduct()
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
    exportWord(productId,name){
        axios({
          url: `./api/products/export-word/` +  productId,
          method: 'POST',
          responseType: 'blob', // important
        }).then(response => {
           const url = window.URL.createObjectURL(new Blob([response.data]));
           const link = document.createElement('a');
           link.href = url;
           link.setAttribute('download', name + '.docx'); //or any other extension
           document.body.appendChild(link);
           link.click();
        })
        .catch(error => {
          this.errors = error.response.data.errors
          this.submiting = false
        })
    },
    getProduct () {
      this.loading = true
      this.products = []

      localStorage.setItem("filtersTableproducts", JSON.stringify(this.filters));

      axios.post(`./api/products/filter?page=${this.filters.pagination.current_page}`, this.filters)
      .then(response => {
        this.products = response.data.data
        delete response.data.data
        this.filters.pagination = response.data
        this.loading = false
      })
    },
    getDocuments (id) {
        this.loading = true
        axios.post('./api/products/docs',{
            customer_id: id
        }).then(response => {
            //console.log(response.data)
            this.docs = response.data
            this.loading = false
        });
    },
    editCustomer (customerId) {
      location.href = `./products/${customerId}/edit`
    },
    // filters
    filter() {
      this.filters.pagination.current_page = 1
      this.getProduct()

    },
    changeSize (perPage) {
      this.filters.pagination.current_page = 1
      this.filters.pagination.per_page = perPage
      this.getProduct()
    },
    sort (column) {
      if(column == this.filters.orderBy.column) {
        this.filters.orderBy.direction = this.filters.orderBy.direction == 'asc' ? 'desc' : 'asc'
      } else {
        this.filters.orderBy.column = column
        this.filters.orderBy.direction = 'asc'
      }

      this.getProduct()
    },
    changePage (page) {
      this.filters.pagination.current_page = page
      this.getProduct()
    }
    ,destroy (customerId) {
      if (!this.submitingDestroy) {
        this.submitingDestroy = true
        swal({
          title: "¿Esta seguro?",
          text: "Una vez eliminado, no podrá recuperar este producto!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete(`./api/products/${customerId}`)
            .then(response => {
                this.$toasted.global.error('Deleted product!')
                location.href = './products'
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
