<template>
  <div class="card card-body col-md-6 container" style="margin-top:25px;">
    <div class=" row justify-content-md-center">
      <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Registrar proveedor</h4>
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
                <label>{{ $t('tables.Name') }}</label>
                <input type="text" class="form-control" required :class="{'is-invalid': errors.name}" v-model="supplier.name" placeholder="Company">
                <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>{{ $t('tables.Address') }}</label>
                <input type="text" class="form-control" required :class="{'is-invalid': errors.address}" v-model="supplier.address" placeholder="Calle...">
                <div class="invalid-feedback" v-if="errors.address">{{errors.address[0]}}</div>
            </div>
            <div class="form-group col-md-6">
              <label>{{ $t('tables.Commission') }}</label>
              <input type="number" class="form-control" :class="{'is-invalid': errors.commission}" v-model="supplier.commission" placeholder="%">
              <div class="invalid-feedback" v-if="errors.commission">{{errors.commission[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>{{ $t('tables.Phone') }}</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.phone}" v-model="supplier.phone" placeholder="9202163">
                <div class="invalid-feedback" v-if="errors.phone">{{errors.phone[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>{{ $t('tables.Cellphone') }}</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.cellphone}" v-model="supplier.cellphone" placeholder="9999978202">
                <div class="invalid-feedback" v-if="errors.cellphone">{{errors.cellphone[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>{{ $t('tables.Email') }}</label>
                <input type="email" class="form-control" :class="{'is-invalid': errors.email}" v-model="supplier.email" placeholder="@">
                <div class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</div>
            </div>
            <div class="form-group col-md-12">
                <label>Nota (opcional)</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.note}" v-model="supplier.note" placeholder="...">
                <div class="invalid-feedback" v-if="errors.note">{{errors.note[0]}}</div>
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
      supplier: {},
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
        axios.post(`../api/suppliers/store`, this.supplier)
        .then(response => {
            this.$toasted.global.error('Created suppliers!')
            location.href = '../suppliers'
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
