<template>
  <v-app autocomplete="off">
  <div class="card card-body col-md-6 container" style="margin-top:25px;">
    <div class=" row justify-content-md-center">
      <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Registrar servicio</h4>
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
                <input type="text" class="form-control" required :class="{'is-invalid': errors.name}" v-model="service.name" placeholder="Nombre servicio">
                <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Dias de pago</label>
                <input type="number" class="form-control" required :class="{'is-invalid': errors.address}" v-model="service.date_payment" placeholder="...">
                <div class="invalid-feedback" v-if="errors.address">{{errors.address[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <v-select
                  :items="frequency"
                  v-model="service.frequency"
                  label="Frecuencia"
                ></v-select>
                <div class="invalid-feedback" v-if="errors.address">{{errors.address[0]}}</div>
            </div>
            <div class="form-group col-md-12">
                <label>Nota (opcional)</label>
                <textarea class="form-control" rows="5" :class="{'is-invalid': errors.note}"  v-model="service.note"></textarea>
                <div class="invalid-feedback" v-if="errors.note">{{errors.note[0]}}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </v-app>
</template>

<script>
var csrf_token = $('meta[name="csrf-token"]').attr('content');
export default {
  data () {
    return {
      service: {},
      errors: {},
      submiting: false,
      frequency:[ 'SEMANAL', 'QUINCENAL', 'MENSUAL', 'BIMESTRAL', 'TRIMESTRAL', 'SEMESTRAL', 'ANUAL' ],
    }
  },
  mounted () {
    this.csrf = window.Laravel.csrfToken;
  },
  methods: {
    create () {
      if (!this.submiting) {
        this.submiting = true
        axios.post(`../api/services/store`, this.service)
        .then(response => {
            this.$toasted.global.error('Servicio creado!')
            location.href = '../services'
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
