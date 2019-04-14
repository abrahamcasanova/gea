<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Editar Destino</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="update">
                <span class="ml-1">Guardar</span>
                <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
                <i class="fas fa-check" v-else></i>
            </a>
          </div>
        </div>
        <div class="col-md-12 card-body px-0">
          <div class="row">
              <div class="form-group col-md-6">
                  <label>Nombre</label>
                  <input type="text" class="form-control" required :class="{'is-invalid': errors.name}" v-model="product.name" placeholder="Hotel...">
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
        product: {
          url_image: 'default.jpeg',
          base64: null
        },
        product_types: [],
        errors: {},
        image: '',
        url: null,
        submiting: false,
    }
  },
  mounted () {
    this.getDestination()
  },
  methods: {
    getDestination() {
      this.loading = true
      let str = window.location.pathname
      let res = str.split("/")
      axios.get(`../../api/destinations/get-destination/${res[3]}`)
      .then(response => {
          this.product = response.data
      })
      .catch(error => {
          this.$toasted.global.error('Error en encontrar el destino!')
          //location.href = '../../destinations'
      })
      .then(() => {
        this.loading = false
      })
    },
    update () {
      if (!this.submiting) {
        this.submiting = true
        axios.put(`../../api/destinations/update/${this.product.id}`, this.product)
        .then(response => {
            this.$toasted.global.error('Destino actualizado!')
            location.href = '../../destinations'
        })
        .catch(error => {
          this.errors = error.response.data.errors
          this.submiting = false
        })
      }
    },
  }
}
</script>
