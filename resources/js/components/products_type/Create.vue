<template>
  <div class="card card-body col-md-6 container" style="margin-top:25px;">
    <div class=" row justify-content-md-center">
      <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Registrar un nuevo tipo</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="create">
              <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
              <span class="ml-1 v-else">Guardar <i class="far fa-save"></i></span>
            </a>
          </div>
        </div>
        <div class="col-md-12 card-body px-0">
          <div class="row">
            <div class="form-group col-md-6">
                <label>{{ $t('tables.Clabe') }}</label>
                <input type="text" class="form-control" required :class="{'is-invalid': errors.clabe}" v-model="product_type.clabe" placeholder="...">
                <div class="invalid-feedback" v-if="errors.clabe">{{errors.clabe[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>{{ $t('tables.Name') }}</label>
                <input type="text" class="form-control" required :class="{'is-invalid': errors.name}" v-model="product_type.name" placeholder="...">
                <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
var csrf_token = $('meta[name="csrf-token"]').attr('content');
export default {
  data () {
    return {
      product_type: {},
      errors: {},
      submiting: false,
    }
  },
  mounted () {
    this.csrf = window.Laravel.csrfToken;
  },
  methods: {
    create () {
      if (!this.submiting) {
        this.submiting = true
        axios.post(`../api/products_type/store`, this.product_type)
        .then(response => {
            this.$toasted.global.error('Created products_type!')
            location.href = '../products_type'
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
