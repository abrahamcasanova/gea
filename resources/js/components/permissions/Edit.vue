<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-12 col-xl-9">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Edit Permission</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="update">
              <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
              <i class="fas fa-check" v-else></i>
              <span class="ml-1">Save</span>
            </a>
            <a class="card-header-action ml-1" href="#" :disabled="submitingDestroy" @click.prevent="destroy">
              <i class="fas fa-spinner fa-spin" v-if="submitingDestroy"></i>
              <i class="far fa-trash-alt" v-else></i>
              <span class="d-md-down-none ml-1">Delete</span>
            </a>
          </div>
        </div>
        <div class="card-header px-0 bg-transparent">
          <strong>General</strong><br>
          <small class="text-muted">Update name and permissions of permission.</small>
        </div>
        <div class="card-body px-0">
          <div class="row" v-if="!loading">
            <div class="form-group col-sm-9">
              <label>Permission name</label>
              <input type="text" class="form-control" :class="{'is-invalid': errors.display_name}" v-model="permission.display_name" placeholder="Admin" autofocus>
              <div class="invalid-feedback" v-if="errors.display_name">{{errors.display_name[0]}}</div>
            </div>
            <div class="form-group col-sm-3">
              <label>Permission ID</label>
              <input type="text" class="form-control" v-model="permission.id" readonly>
            </div>
            <div class="form-group col-sm-12">
              <label>Permission slug</label>
              <input type="text" class="form-control" :class="{'is-invalid': errors.name}" v-model="permission.name" placeholder="admin" readonly>
              <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
            </div>
            <div class="form-group col-sm-12">
              <label>Modulo</label>
              <multiselect
                  v-model="permission.module_id"
                  :options="modules"
                  :multiple="false"
                  track-by="id"
                  label="display_name"
                  :class="{'border border-danger rounded': errors.modules}">
              </multiselect>
              <small class="form-text text-danger" v-if="errors.modules">{{errors.modules[0]}}</small>
            </div>
          </div>
          <content-placeholders v-else>
            <content-placeholders-heading/>
          </content-placeholders>
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
        permissions: [],
        users: [],
        modules: [],
        module_id: {},
      },
      permissionsCount: 0,
      errors: {},
      modules: [],
      module_id: {},
      loading: true,
      submiting: false,
      submitingDestroy: false,
      value: { name: 'Vue.js' },
      options: [
        { name: 'Vue.js', language: 'JavaScript' },
        { name: 'Rails', language: 'Ruby' },
        { name: 'Sinatra', language: 'Ruby' },
        { name: 'Laravel', language: 'PHP' },
        { name: 'Phoenix', language: 'Elixir' }
      ]
    }
  },
  mounted () {
    this.getPermissionsCount()
    this.getPermission()
    this.getModulesPermissions()
  },
  methods: {
    getPermission() {
      this.loading = true
      let str = window.location.pathname
      let res = str.split("/")
      axios.get(`/api/permissions/getPermissionModulesPermissions/${res[2]}`)
      .then(response => {
          this.permission = response.data[0]
          this.permission.module_id = response.data[1]
      })
      .catch(error => {
        this.$toasted.global.error('Permission does not exist!')
        location.href = '/permissions'
      })
      .then(() => {
        this.loading = false
      })
    },
    getModulesPermissions () {
      this.loading = true
      axios.get('/api/permissions/getModules')
      .then(response => {
        this.permission.modulesPermissions = response.data
        this.loading = false
        this.modules = response.data
      })
    },
    update () {
      if (!this.submiting) {
        this.submiting = true
        axios.put(`/api/permissions/update/${this.permission.id}`, this.permission)
        .then(response => {
          this.$toasted.global.error('Updated permission!')
          location.href = '/permissions'
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
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover this permission!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete(`/api/permissions/${this.permission.id}`)
            .then(response => {
              this.$toasted.global.error('Deleted permission!')
              location.href = '/permissions'
            })
            .catch(error => {
              this.errors = error.response.data.errors
            })
          }
          this.submitingDestroy = false
        })
      }
    },
    getPermissionsCount() {
      axios.get(`/api/permissions/count`)
      .then(response => {
        this.permissionsCount = response.data
      })
    }
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
