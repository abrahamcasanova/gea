<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Edit Customer</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="update">
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
                  <input type="text" v-validate="'required'" name='name' class="form-control" :class="{'is-invalid': errors.name}" v-model="customer.user.name" placeholder="SabinaC">
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
                v-model="customer.group"
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
                  v-model="customer.subgroup"
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
                  v-model="customer.office"
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
                <input type="text" class="form-control" :class="{'is-invalid': errors.phone}" v-model="customer.user['profile']['phone']" placeholder="9202163">
                <div class="invalid-feedback" v-if="errors.phone">{{errors.phone[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Cellphone number</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.cellphone}" v-model="customer.user['profile']['cellphone']" placeholder="9999978202">
                <div class="invalid-feedback" v-if="errors.cellphone">{{errors.cellphone[0]}}</div>
            </div>
            <div class="form-group col-md-9">
                <label>Adreess</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.address}" v-model="customer.user['profile']['address']" placeholder="Street ..">
                <div class="invalid-feedback" v-if="errors.address">{{errors.address[0]}}</div>
            </div>
            <div class="form-group col-md-3">
                <label>Location</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.location}" v-model="customer.user['profile']['location']" placeholder="Merida, Yuc.">
                <div class="invalid-feedback" v-if="errors.location">{{errors.location[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Password</label><br>
                <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="reset">
                    <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
                    <i class="fas fa-redo-alt" v-else></i>
                    <span class="ml-1">Reset</span>
                </a>
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
      customer: {
          user: {
              profile: {}
          },
      },
      roles: [],
      groups: [],
      offices: [],
      subgroups: [],
      type_of_person: [
          { id: 'FISICA', name: 'FISICA' },
          { id: 'MORAL', name: 'MORAL' },
      ],
      type_one: { id: 'FISICA', name: 'FISICA' },
      type_two: { id: 'MORAL', name: 'MORAL'},
      errors: {},
      loading: true,
      submiting: false,
      submitingDestroy: false
    }
  },
  mounted () {
    this.getCustomer()
    this.getOffices()
    this.getGroups()
  },
  methods: {
    getCustomer() {
      this.loading = true
      let str = window.location.pathname
      let res = str.split("/")
      axios.get(`/api/customers/getCustomer/${res[2]}`)
      .then(response => {
          this.customer = response.data
          this.customer.type_of_person = response.data.type_of_person == 'FISICA' ? this.type_one:this.type_two;
      })
      .catch(error => {
          this.$toasted.global.error('User does not exist!')
          location.href = '/customers'
      })
      .then(() => {
        this.loading = false
      })
    },
    update () {
      if (!this.submiting) {
        this.submiting = true
        axios.put(`/api/customers/update/${this.customer.id}`, this.customer)
        .then(response => {
            this.$toasted.global.error('Updated customer!')
            location.href = '/customers'
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
            text: "Once deleted, you will not be able to recover this customer!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              axios.delete(`/api/customers/${this.customer.id}`)
              .then(response => {
                  this.$toasted.global.error('Deleted customer!')
                  location.href = '/customers'
              })
              .catch(error => {
                this.errors = error.response.data.errors
              })
            }
            this.submitingDestroy = false
          })
        }
    },
    getGroups() {
        axios.get(`/api/groups/all`).then(response => {
            if(response){
                this.groups = response.data
                this.subGroup();
            }
        })
        .catch(error => {
            if(error.response){
                this.errors = error.response.data.errors
            }
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
        this.$delete(this.customer, 'subgroup')

        axios.post(`/api/groups/subgroup_by_gruoup_id`, {
            id: this.customer.group.id
        })
        .then(response => {
            this.subgroups = response.data;
        })
        .catch(error => {
          this.errors = error.response.data.errors
        })
    },
    subGroup() {
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
