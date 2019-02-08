<template>
  <div class="card card-body col-md-6 container" style="margin-top:25px;">
    <div class=" row justify-content-md-center">
      <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Registrar prospecto</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="create">
              <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
              <span class="ml-1 v-else">Enviar <i class="icon-paper-plane"></i></span>
            </a>
          </div>
        </div>
        <div class="col-md-12 card-body px-0">
          <div class="row">
            <div class="form-group col-md-6">
                <label>Nombre</label>
                <input type="text" class="form-control" required :class="{'is-invalid': errors.first_name}" v-model="prospecting.first_name" placeholder="Sabina">
                <div class="invalid-feedback" v-if="errors.first_name">{{errors.first_name[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Apellidos</label>
                <input type="text" class="form-control" required :class="{'is-invalid': errors.last_name}" v-model="prospecting.last_name" placeholder="Casanova Ortiz">
                <div class="invalid-feedback" v-if="errors.last_name">{{errors.last_name[0]}}</div>
            </div>
            <div class="form-group col-md-6">
              <label>Email</label>
              <input type="email" class="form-control" :class="{'is-invalid': errors.email}" v-model="prospecting.email" placeholder="sabina.casanova@hotmail.com">
              <div class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Telefono</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.phone}" v-model="prospecting.phone" placeholder="9202163">
                <div class="invalid-feedback" v-if="errors.phone">{{errors.phone[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Celular</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.cellphone}" v-model="prospecting.cellphone" placeholder="9999978202">
                <div class="invalid-feedback" v-if="errors.cellphone">{{errors.cellphone[0]}}</div>
            </div>
            <div class="form-group col-md-6">
              <label>Ramo</label>
              <multiselect
                v-model="prospecting.branches"
                :options="branches"
                openDirection="bottom"
                track-by="id"
                label="name"
                :class="{'border border-danger rounded': errors.branches}">
              </multiselect>
              <small class="form-text text-danger" v-if="errors.branches">{{errors.branches[0]}}</small>
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
      prospecting: {},
      branch: [],
      groups: [],
      offices: [],
      subgroups: [],
      branches: [],
      errors: {},
      submiting: false,
      type_of_person: [
          { id: 'FISICA', name: 'FISICA' },
          { id: 'MORAL', name: 'MORAL' },
      ],
      password: '',
      password_confirmation: '',
      options: {
          target: '/api/customers/upload',
          testChunks: false,
          withCredentials: true,
          headers:{
              'X-CSRF-TOKEN': csrf_token,
          },
          query: { upload_token: csrf_token },
      },
      attrs: {
        accept: 'image/*',
      }
    }
  },
  mounted () {
    this.getBranches()
    this.csrf = window.Laravel.csrfToken;
  },
  methods: {
    create () {
      if (!this.submiting) {
        this.submiting = true
        axios.post(`/api/prospectings/store`, this.prospecting)
        .then(response => {
            this.$toasted.global.error('Created prospectings!')
            location.href = '/prospectings'
        })
        .catch(error => {
          this.errors = error.response.data.errors
          this.submiting = false
        })
      }
    },
    getBranches() {
        axios.get(`/api/branches/all`).then(response => {
            this.branches = response.data
        })
        .catch(error => {
            this.errors = error.response.data.errors
        })
    },
    getOffices() {
        axios.get(`/api/offices/all`).then(response => {
            this.offices = response.data
        })
        .catch(error => {
            this.errors = error.response.data.errors
        })
    },
    onChange() {
        this.$delete(this.customer, 'subgroup_id')
        axios.post(`/api/groups/subgroup_by_gruoup_id`, {
            id: this.customer.group_id.id
        })
        .then(response => {
            this.subgroups = response.data;
        })
        .catch(error => {
          this.errors = error.response.data.errors
        })
    }
  }
}
</script>
