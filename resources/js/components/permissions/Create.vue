<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-9 col-xl-7">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Nuevo permiso</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="create">
              <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
              <i class="fas fa-check" v-else></i>
              <span class="ml-1">Guardar</span>
            </a>
          </div>
        </div>
        <div class="card-header px-0 bg-transparent">
          <strong>General</strong><br>
          <small class="text-muted">Create a new permission.</small>
        </div>
        <div class="card-body px-0">
          <div class="form-group">
            <label>Permission name</label>
            <input type="text" class="form-control" :class="{'is-invalid': errors.display_name}" v-model="permission.display_name" placeholder="Admin" autofocus>
            <div class="invalid-feedback" v-if="errors.display_name">{{errors.display_name[0]}}</div>
          </div>
          <div class="form-group">
            <label>Permission slug</label>
            <input type="text" class="form-control" :class="{'is-invalid': errors.name}" v-model="permission.name" placeholder="admin" readonly>
            <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
          </div>
          <div class="form-group">
            <label>Modulo</label>
            <multiselect
              v-model="permission.modules"
              :options="modules"
              :multiple="false"
              openDirection="top"
              track-by="id"
              label="display_name"
              :class="{'border border-danger rounded': errors.modules}">
            </multiselect>
            <small class="form-text text-danger" v-if="errors.modules">{{errors.modules[0]}}</small>
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
      permission: {
          modules:[]
      },
      modules: [],
      errors: {},
      loading: true,
      submiting: false
    }
  },
  mounted() {
    this.getModulesPermissions()
  },
  methods: {
    create () {
      if (!this.submiting) {
        this.submiting = true
        axios.post('../api/permissions/store', this.permission)
        .then(response => {
          this.$toasted.global.error('Permiso Creado!')
          setTimeout(location.href = '../permissions', 2000)
        })
        .catch(error => {
          this.errors = error.response.data.errors
          this.submiting = false
        })
      }
    },
    getModulesPermissions () {
      this.loading = true
      axios.get('../api/modules/getModulesPermissions')
      .then(response => {
        this.permission.modulesPermissions = response.data
        this.loading = false
        this.modules = response.data
      })
    },
  },
  watch: {
    'permission.display_name': function (val) {
      this.permission.name = val.toString().toLowerCase()
        .replace(/\s+/g, '-')           // Replace spaces with -
        .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
        .replace(/\-\-+/g, '-')         // Replace multiple - with single -
        .replace(/^-+/, '')             // Trim - from start of text
        .replace(/-+$/, '');
    }
  }
}
</script>
