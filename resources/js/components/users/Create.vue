<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-9 col-xl-7">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2"> {{ $t('user.New_User') }}</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="create">
              <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
              <i class="fas fa-check" v-else></i>
              <span class="ml-1">{{ $t('user.Save') }}</span>
            </a>
          </div>
        </div>
        <div class="card-body px-0">
          <div class="form-group">
            <label>{{ $t('user.full_name') }}</label>
            <input type="text" class="form-control" :class="{'is-invalid': errors.name}" v-model="user.name" placeholder="John Doe">
            <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" :class="{'is-invalid': errors.email}" v-model="user.email" placeholder="john@modulr.io">
            <div class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</div>
          </div>
          <div class="form-group">
            <label>Telefono</label>
            <input type="text" class="form-control" :class="{'is-invalid': errors.phone}" v-model="user.phone" placeholder="9202163">
            <div class="invalid-feedback" v-if="errors.phone">{{errors.phone[0]}}</div>
          </div>
          <div class="form-group">
            <label>Celular</label>
            <input type="text" class="form-control" :class="{'is-invalid': errors.cellphone}" v-model="user.cellphone" placeholder="9994450668">
            <div class="invalid-feedback" v-if="errors.cellphone">{{errors.cellphone[0]}}</div>
          </div>
          <div class="form-group">
            <label>{{ $t('user.password') }}</label>
            <input type="password" class="form-control" :class="{'is-invalid': errors.password}" v-model="user.password">
            <div class="invalid-feedback" v-if="errors.password">{{errors.password[0]}}</div>
          </div>
          <div class="form-group">
            <label>Roles</label>
            <multiselect
              v-model="user.roles"
              :options="roles"
              :multiple="true"
              openDirection="top"
              track-by="id"
              placeholder="Seleccionar..."
              label="display_name"
              :class="{'border border-danger rounded': errors.roles}">
            </multiselect>
            <small class="form-text text-danger" v-if="errors.roles">{{errors.roles[0]}}</small>
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
      user: {
        roles: []
      },
      roles: [],
      errors: {},
      submiting: false
    }
  },
  mounted () {
    this.getRoles()
  },
  methods: {
    create () {
      if (!this.submiting) {
        this.submiting = true
        axios.post(`../api/users/store`, this.user)
        .then(response => {
          this.$toasted.global.error('Created user!')
          location.href = '../users'
        })
        .catch(error => {
          this.errors = error.response.data.errors
          this.submiting = false
        })
      }
    },
    getRoles () {
      axios.get(`../api/roles/all`)
      .then(response => {
        this.roles = response.data
      })
      .catch(error => {
        this.errors = error.response.data.errors
      })
    }
  }
}
</script>
