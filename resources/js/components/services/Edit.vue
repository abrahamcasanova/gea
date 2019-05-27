<template>
  <div class="container">
    <v-app autocomplete="off">
        <div class="row justify-content-md-center">
          <div class="col-md-12 col-xl-12">
            <div class="card-header px-0 mt-2 bg-transparent clearfix">
              <h4 class="float-left pt-2">Editar servicio</h4>
              <div class="card-header-actions mr-1">
                <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="update">
                    <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
                    <i class="fas fa-check" v-else></i>
                    <span class="ml-1">{{ $t('Supplier.Update') }}</span>
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
    </v-app>
  </div>
</template>

<script>
export default {
  data () {
    return {
      service: {},
      branches: [],
      errors: {},
      loading: true,
      submiting: false,
      submitingDestroy: false,
      frequency:[ 'SEMANAL', 'QUINCENAL', 'MENSUAL', 'BIMESTRAL', 'TRIMESTRAL', 'SEMESTRAL', 'ANUAL' ],

    }
  },
  mounted () {
    this.getService()
  },
  methods: {
    getService() {
      this.loading = true
      let str = window.location.pathname
      let res = str.split("/")
      axios.get(`../../api/services/get-service/${res[3]}`)
      .then(response => {
          this.service = response.data
      })
      .catch(error => {
          this.$toasted.global.error('Servicio no encontrado!')
          location.href = '../../services'
      })
      .then(() => {
        this.loading = false
      })
    },
    update () {
      if (!this.submiting) {
        this.submiting = true
        axios.put(`../../api/services/update/${this.service.id}`, this.service)
        .then(response => {
            this.$toasted.global.error('Servicio Actualizado!')
            location.href = '../../services'
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
