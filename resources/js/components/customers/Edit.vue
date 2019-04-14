<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Editar</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="update">
                <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
                <i class="fas fa-check" v-else></i>
                <span class="ml-1">Guardar</span>
            </a>
          </div>
        </div>
        <div class="col-md-12 card-body px-0">
            <div class="row">
              <div class="form-group col-md-6">
                  <label>Nombres</label>
                  <input type="text" class="form-control" :class="{'is-invalid': errors.first_name}" v-model="customer.first_name" placeholder="Sabina">
                  <div class="invalid-feedback" v-if="errors.first_name">{{errors.first_name[0]}}</div>
              </div>
              <div class="form-group col-md-6">
                  <label>Apellidos</label>
                  <input type="text" class="form-control" :class="{'is-invalid': errors.last_name}" v-model="customer.last_name" placeholder="Casanova Ortiz">
                  <div class="invalid-feedback" v-if="errors.last_name">{{errors.last_name[0]}}</div>
              </div>
              <div class="form-group col-md-6">
                <label>Email</label>
                <input type="email" class="form-control" :class="{'is-invalid': errors.email}" v-model="customer.email" placeholder="sabina.casanova@hotmail.com">
                <div class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</div>
              </div>
              <div class="form-group col-md-6">
                <label>Rfc</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.rfc}" v-model="customer.rfc" placeholder="XAX00000000" :maxlength="13">
                <div class="invalid-feedback" v-if="errors.rfc">{{errors.rfc[0]}}</div>
              </div>
              <div class="form-group col-md-6">
                <label>Tipo</label>
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
                  <label>Telefono</label>
                  <input type="text" class="form-control" :class="{'is-invalid': errors.phone}" v-model="customer.phone" placeholder="9202163">
                  <div class="invalid-feedback" v-if="errors.phone">{{errors.phone[0]}}</div>
              </div>
              <div class="form-group col-md-6">
                  <label>Celular</label>
                  <input type="text" class="form-control" :class="{'is-invalid': errors.cellphone}" v-model="customer.cellphone" placeholder="9999978202">
                  <div class="invalid-feedback" v-if="errors.cellphone">{{errors.cellphone[0]}}</div>
              </div>
              <div class="form-group col-md-6">
                <label>Cumpleaños</label>
                <datepicker :bootstrap-styling="true" :language="es" :format="customFormatter" v-model="customer.birthdate"></datepicker>
                <div class="invalid-feedback" v-if="errors.birthdate">{{errors.birthdate[0]}}</div>
            </div>
            
            <div class="form-group col-md-6">
              <label>Nivel de cliente</label>
              <multiselect
                v-model="customer.level"
                :options="customerLevel"
                openDirection="bottom"
                track-by="id"
                label="name"
                :class="{'border border-danger rounded': errors.level}">
              </multiselect>
              <small class="form-text text-danger" v-if="errors.level">{{errors.level[0]}}</small>
            </div>
              <div class="form-group col-md-8">
                  <label>Direccion</label>
                  <input type="text" class="form-control" :class="{'is-invalid': errors.address}" v-model="customer.address" placeholder="Direccion ..">
                  <div class="invalid-feedback" v-if="errors.address">{{errors.address[0]}}</div>
              </div>
              <div class="form-group col-md-8">
                  <label>Comentario</label>
                  <input type="text" class="form-control" :class="{'is-invalid': errors.comment}" v-model="customer.comment" placeholder="Comentario ..">
                  <div class="invalid-feedback" v-if="errors.comment">{{errors.comment[0]}}</div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import {en, es} from 'vuejs-datepicker/dist/locale'
import moment from 'moment'

export default {
  components: {
      Datepicker,
  },
  data () {
    return {
      customer: {
          user: {
              profile: {}
          },
      },
      type_of_person: [
          { id: 'PROSPECTO', name: 'PROSPECTO' },
          { id: 'CLIENTE', name: 'CLIENTE' },
      ],
      type_one: { id: 'PROSPECTO', name: 'PROSPECTO' },
      type_two: { id: 'CLIENTE', name: 'CLIENTE'},
      customerLevel:[
        { id: 'BAJA', name: 'BAJA' },
        { id: 'MEDIA', name: 'MEDIA' },
        { id: 'ALTA', name: 'ALTA' },
      ],
      es: es,
      errors: {},
      loading: true,
      submiting: false,
      submitingDestroy: false
    }
  },
  mounted () {
    this.getCustomer()
  },
  methods: {
    getCustomer() {
      this.loading = true
      let str = window.location.pathname
      let res = str.split("/")
      axios.get(`../../api/customers/getCustomer/${res[3]}`)
      .then(response => {
          this.customer = response.data
          this.customer.type_of_person = response.data.type_of_person == 'PROSPECTO' ? this.type_one:this.type_two;
          this.customer.level =  {id: response.data.level, name: response.data.level};
      })
      .catch(error => {
          this.$toasted.global.error('User does not exist!')
          location.href = '../../customers'
      })
      .then(() => {
        this.loading = false
      })
    },
    update () {
      if (!this.submiting) {
        this.submiting = true
        axios.put(`../../api/customers/update/${this.customer.id}`, this.customer)
        .then(response => {
            this.$toasted.global.error('Updated customer!')
            location.href = '../../customers'
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
            title: "¿Esta seguro?",
            text: "Una vez eliminado, no podrá recuperar este cliente!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              axios.delete(`../../api/customers/${this.customer.id}`)
              .then(response => {
                  this.$toasted.global.error('Deleted customer!')
                  location.href = '../../customers'
              })
              .catch(error => {
                this.errors = error.response.data.errors
              })
            }
            this.submitingDestroy = false
          })
        }
    },
    customFormatter(date) {
      this.customer.birthdate = moment(date).format('YYYY/MM/DD');
      return moment(date).format('YYYY/MM/DD');
    },
    getGroups() {
        axios.get(`../../api/groups/all`).then(response => {
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
        axios.get(`../../api/offices/all`).then(response => {
            this.offices = response.data
        })
        .catch(error => {
            this.errors = error.response.data.errors
        })
    },
    onChange() {
        this.$delete(this.customer, 'subgroup_id')
        this.$delete(this.customer, 'subgroup')

        axios.post(`../../api/groups/subgroup_by_gruoup_id`, {
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
        axios.post(`../../api/groups/subgroup_by_gruoup_id`, {
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
