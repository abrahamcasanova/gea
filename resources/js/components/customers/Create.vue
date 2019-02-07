<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">New Customer</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="create">
              <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
              <i class="fas fa-check" v-else></i>
              <span class="ml-1">Save</span>
            </a>
          </div>
        </div>
        <div class="col-md-12 card-body px-0">
          <div class="row">
              <div class="form-group col-md-6">
                  <label>Username</label>
                  <input type="text" v-validate="'required'" class="form-control" :class="{'is-invalid': errors.name}" v-model="customer.name" placeholder="SabinaC">
                  <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
              </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
                <label>First Name</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.first_name}" v-model="customer.first_name" placeholder="Sabina">
                <div class="invalid-feedback" v-if="errors.first_name">{{errors.first_name[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Last Name</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.last_name}" v-model="customer.last_name" placeholder="Casanova Ortiz">
                <div class="invalid-feedback" v-if="errors.last_name">{{errors.last_name[0]}}</div>
            </div>
            <div class="form-group col-md-6">
              <label>Email</label>
              <input type="email" class="form-control" :class="{'is-invalid': errors.email}" v-model="customer.email" placeholder="sabina.casanova@hotmail.com">
              <div class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</div>
            </div>
            <div class="form-group col-md-6">
              <label>Type of person</label>
              <multiselect
                v-model="customer.type_of_person"
                :options="type_of_person"
                openDirection="bottom"
                track-by="id"
                label="name"
                :class="{'border border-danger rounded': errors.type_of_person}">
              </multiselect>
              <small class="form-text text-danger" v-if="errors.type_of_person">{{errors.type_of_person[0]}}</small>
            </div>
            <div class="form-group col-md-6">
              <label>Group</label>
              <multiselect
                v-model="customer.group_id"
                :options="groups"
                openDirection="bottom"
                @input="onChange"
                track-by="id"
                label="name"
                :class="{'border border-danger rounded': errors.group_id}">
              </multiselect>
              <small class="form-text text-danger" v-if="errors.group_id">{{errors.group_id[0]}}</small>
            </div>
            <div class="form-group col-md-3">
                <label>Subgroup</label>
                <multiselect
                  v-model="customer.subgroup_id"
                  :options="subgroups"
                  openDirection="bottom"
                  track-by="id"
                  label="name"
                  :class="{'border border-danger rounded': errors.subgroup_id}">
                </multiselect>
                <small class="form-text text-danger" v-if="errors.subgroup_id">{{errors.subgroup_id[0]}}</small>
            </div>
            <div class="form-group col-md-3">
                <label>Office</label>
                <multiselect
                  v-model="customer.office_id"
                  :options="offices"
                  openDirection="bottom"
                  track-by="id"
                  label="name"
                  :class="{'border border-danger rounded': errors.office_id}">
                </multiselect>
                <small class="form-text text-danger" v-if="errors.office_id">{{errors.office_id[0]}}</small>
            </div>
            <div class="form-group col-md-6">
                <label>Phone number</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.phone}" v-model="customer.phone" placeholder="9202163">
                <div class="invalid-feedback" v-if="errors.phone">{{errors.phone[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Cellphone number</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.cellphone}" v-model="customer.cellphone" placeholder="9999978202">
                <div class="invalid-feedback" v-if="errors.cellphone">{{errors.cellphone[0]}}</div>
            </div>
            <div class="form-group col-md-9">
                <label>Adreess</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.address}" v-model="customer.address" placeholder="Street ..">
                <div class="invalid-feedback" v-if="errors.address">{{errors.address[0]}}</div>
            </div>
            <div class="form-group col-md-3">
                <label>Location</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.location}" v-model="customer.location" placeholder="Merida, Yuc.">
                <div class="invalid-feedback" v-if="errors.location">{{errors.location[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Password</label>
                <input type="password" v-validate="'required'" ref="passwordR" name="password" class="form-control" :class="{'is-invalid': errors.password}" v-model="customer.password" placeholder="password">
                <div class="invalid-feedback" v-if="errors.password">{{errors.password[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Confirm password</label>
                <input v-model='customer.password_confirmation' v-validate="'required|confirmed:passwordR'" name="password_confirmation" type="password" class="form-control" placeholder="Password, Again">
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
      customer: {},
      groups: [],
      offices: [],
      subgroups: [],
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
    this.getGroups()
    this.getOffices()
    this.csrf = window.Laravel.csrfToken;
  },
  methods: {
    create () {
      if (!this.submiting) {
        this.submiting = true
        axios.post(`/api/customers/store`, this.customer)
        .then(response => {
            this.$toasted.global.error('Created customer!')
            location.href = '/customers'
        })
        .catch(error => {
          this.errors = error.response.data.errors
          this.submiting = false
        })
      }
    },
    getGroups() {
        axios.get(`/api/groups/all`).then(response => {
            this.groups = response.data
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
