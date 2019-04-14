<template>
  <div class="card card-body col-md-12 container" style="margin-top:25px;">
    <div class=" row justify-content-md-center">
      <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Registrar nueva forma de pago</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="create">
              <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
              <span class="ml-1 v-else"> Guardar</span>
            </a>
          </div>
        </div>
        <div class="col-md-12 card-body px-0">
          <div class="row">
            <div class="form-group col-md-6">
                <label>Nombre</label>
                <input type="text" class="form-control" required :class="{'is-invalid': errors.name}" v-model="type_of_payment.name" placeholder="Nombre...">
                <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <v-checkbox
                  v-model="type_of_payment.with_authorization"
                  color="green"
                  :label="`Autorización`"
                ></v-checkbox>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
                <v-checkbox
                  v-model="type_of_payment.with_percentage"
                  color="blue"
                  :label="`Con porcentaje`"
                ></v-checkbox>
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
      type_of_payment: {},
      product_types: [],
      errors: {},
      image: '',
      checkbox: false,
      url: null,
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
          axios.post(`../api/type_of_payments/store`, this.type_of_payment)
          .then(response => {
              this.$toasted.global.error('Tipo de pago creado correctamente!')
              location.href = '../type_payments'
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
