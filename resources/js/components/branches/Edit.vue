<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Edit Branch</h4>
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
                  <label>Name</label>
                  <input type="text" v-validate="'required'" name='name' class="form-control" :class="{'is-invalid': errors.name}" v-model="branch.name" placeholder="SabinaC">
                  <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
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
      branch: {},
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
    this.getBranch()
  },
  methods: {
    getBranch() {
      this.loading = true
      let str = window.location.pathname
      let res = str.split("/")
      axios.get(`/api/branches/get-branch/${res[2]}`)
      .then(response => {
          this.branch = response.data
      })
      .catch(error => {
          this.$toasted.global.error('Branch does not exist!')
          //location.href = '/branches'
      })
      .then(() => {
        this.loading = false
      })
    },
    update () {
      if (!this.submiting) {
        this.submiting = true
        axios.put(`/api/branches/update/${this.branch.id}`, this.branch)
        .then(response => {
            this.$toasted.global.error('Updated branch!')
            location.href = '/branches'
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
            text: "Once deleted, you will not be able to recover this branch!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              axios.delete(`/api/branches/${this.branch.id}`)
              .then(response => {
                  this.$toasted.global.error('Deleted branch!')
                  location.href = '/branches'
              })
              .catch(error => {
                this.errors = error.response.data.errors
              })
            }
            this.submitingDestroy = false
          })
        }
    },
    onChange() {
        this.$delete(this.branch, 'subgroup_id')
        this.$delete(this.branch, 'subgroup')

        axios.post(`/api/groups/subgroup_by_gruoup_id`, {
            id: this.branch.group.id
        })
        .then(response => {
            this.subgroups = response.data;
        })
        .catch(error => {
          this.errors = error.response.data.errors
        })
    },
    subGroup() {
        this.$delete(this.branch, 'subgroup_id')
        axios.post(`/api/groups/subgroup_by_gruoup_id`, {
            id: this.branch.group_id.id
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
