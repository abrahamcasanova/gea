<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">{{ $t('Products-Type.Edit_Product') }}</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="update">
                <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
                <i class="fas fa-check" v-else></i>
                <span class="ml-1">{{ $t('Products-Type.Save') }}</span>
            </a>
          </div>
        </div>
        <div class="col-md-12 card-body px-0">
          <div class="row">
            <div class="form-group col-md-6">
                <label>{{ $t('tables.Clabe') }}</label>
                <input type="text" class="form-control" required :class="{'is-invalid': errors.clabe}" v-model="product_type.clabe" placeholder="ABC">
                <div class="invalid-feedback" v-if="errors.clabe">{{errors.clabe[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>{{ $t('tables.Name') }}</label>
                <input type="text" class="form-control" required :class="{'is-invalid': errors.name}" v-model="product_type.name" placeholder="Nombre..">
                <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      product_type: {
          user: {
              profile: {}
          },
      },
      branches: [],
      errors: {},
      loading: true,
      submiting: false,
      submitingDestroy: false
    }
  },
  mounted () {
    this.getProspecting()
  },
  methods: {
    getProspecting() {
      this.loading = true
      let str = window.location.pathname
      let res = str.split("/")
      axios.get(`/api/products_type/get-product_type/${res[2]}`)
      .then(response => {
          this.product_type = response.data
      })
      .catch(error => {
          this.$toasted.global.error('Type product does not exist!')
          location.href = '/products_type'
      })
      .then(() => {
        this.loading = false
      })
    },
    update () {
      if (!this.submiting) {
        this.submiting = true
        axios.put(`/api/products_type/update/${this.product_type.id}`, this.product_type)
        .then(response => {
            this.$toasted.global.error('Updated product_type!')
            location.href = '/products_type'
        })
        .catch(error => {
          this.errors = error.response.data.errors
          this.submiting = false
        })
      }
    }
  }
}
</script>
