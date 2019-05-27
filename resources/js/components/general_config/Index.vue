<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-9 col-xl-7">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Editar configuración general</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="update">
              <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
              <i class="fas fa-sync-alt" v-else></i>
              <span class="ml-1">Actualizar</span>
            </a>
            <a style="display:none;" class="card-header-action ml-1" href="#" :disabled="submitingDestroy" @click.prevent="destroy">
              <i class="fas fa-spinner fa-spin" v-if="submitingDestroy"></i>
              <i class="far fa-trash-alt" v-else></i>
              <span class="d-md-down-none ml-1">{{ $t('user.Delete') }}</span>
            </a>
          </div>
        </div>
        <div class="card-body px-0">
          <div class="row" v-if="!loading">
            <div class="form-group col-md-6">
              <label>Monto maximo para gastos de oficina</label>
              <vue-numeric v-on:change="changeMaxExpenses" class="form-control"  :class="{'is-invalid': errors.maximum_expenses}" currency="$" separator="," :precision="2" v-model="general_config.maximum_expenses"></vue-numeric>
              <div class="invalid-feedback" v-if="errors.maximum_expenses">{{errors.maximum_expenses[0]}}</div>
            </div>
            <div class="form-group col-md-5">
              <label>Fecha ultima actualización</label>
              <input class="form-control" type="text" v-model="general_config.updated_at" readonly>
            </div>
          </div>
          <div class="row" v-else>
            <div class="col-md-12">
              <content-placeholders>
                <content-placeholders-text/>
              </content-placeholders>
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
      general_config: {},
      roles: [],
      errors: {},
      loading: true,
      submiting: false,
      submitingDestroy: false
    }
  },
  mounted () {
    this.getGeneralConfig()
  },
  methods: {
    changeMaxExpenses(){
        console.log('asas')
        this.$forceUpdate();
    },
    getGeneralConfig () {
      axios.get(`./api/general_config/all`)
      .then(response => {
        if(response.data){
            this.general_config = response.data
        }else{
            this.general_config.maximum_expenses = 0;
        }

        this.loading = false;
        this.$forceUpdate();
      })
      .catch(error => {
        this.errors = error.response.data.errors
      })
    },
    update () {
      if (!this.submiting) {
        this.submiting = true
        axios.put(`./api/general_config/update`, this.general_config)
        .then(response => {
          swal("Configuracion actualizado correctamente!!", {
              icon: 'success',
              buttons: false,
              timer: 2000,
          });
          setTimeout(location.href = './dashboard', 2500);
        })
        .catch(error => {
          this.errors = error.response.data.errors
          this.submiting = false
        })
      }
    },
    destroy () {
      if (!this.submitingDestroy) {
        this.submitingDestroy = true
        swal({
          title: this.$t('user.Warning_Title'),
          text: this.$t('user.Warning_Delete'),
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete(`./api/users/${this.user.id}`)
            .then(response => {
              this.$toasted.global.error('Deleted user!')
              location.href = './users'
            })
            .catch(error => {
              this.errors = error.response.data.errors
            })
          }
          this.submitingDestroy = false
        })
      }
    }
  }
}
</script>
