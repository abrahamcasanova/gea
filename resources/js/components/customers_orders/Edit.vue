<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="card">
          <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Sun Travel Mérida "Solicitud de Cotización" | Editar</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="update">
              <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
              <i class="fas fa-check" v-else></i>
              <span class="ml-1">Actualizar</span>
            </a>
          </div>
        </div>
        <div class="col-md-12 card-body px-0">
          <div class="row">
            <div class="form-group col-md-4">
                <label>Nombres</label>
                <input type="text" disabled readonly class="form-control" :class="{'is-invalid': errors.first_name}" v-model="customerOrder.customer['first_name']" placeholder="Sabina">
                <div class="invalid-feedback" v-if="errors.first_name">{{errors.first_name[0]}}</div>
            </div>
            <div class="form-group col-md-4">
                <label>Apellidos</label>
                <input type="text" disabled readonly class="form-control" :class="{'is-invalid': errors.last_name}" v-model="customerOrder.customer['last_name']" placeholder="Casanova Ortiz">
                <div class="invalid-feedback" v-if="errors.last_name">{{errors.last_name[0]}}</div>
            </div>
            <div class="form-group col-md-4">
              <label>Email</label>
              <input type="email" disabled readonly class="form-control" :class="{'is-invalid': errors.email}" v-model="customerOrder.customer['email']" placeholder="sabina.casanova@hotmail.com">
              <div class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</div>
            </div>
            <div class="form-group col-md-4">
              <label>Mes del viaje</label>
              <multiselect
                v-model="customerOrder.travel_month"
                :options="month"
                required
                openDirection="bottom"
                track-by="id"
                label="name"
                :class="{'border border-danger rounded': errors.travel_month}">
              </multiselect>
              <small class="form-text text-danger" v-if="errors.travel_month">{{errors.travel_month[0]}}</small>
            </div>
            <div class="form-group col-md-4">
                <label>Fecha inicial de su viaje?</label>
                <datepicker :bootstrap-styling="true" required :language="es" :format="customFormatter" v-model="customerOrder.travel_date"></datepicker>
                <small class="form-text text-danger" v-if="errors.travel_date">{{errors.travel_date[0]}}</small>
            </div>
            <div class="form-group col-md-4">
                <label>Fecha final de su viaje?</label>
                <datepicker :bootstrap-styling="true" required :language="es" :format="customFormatterEnd" v-model="customerOrder.travel_end_date"></datepicker>
                <small class="form-text text-danger" v-if="errors.travel_end_date">{{errors.travel_end_date[0]}}</small>
            </div>
            <div class="form-group col-md-6">
                <label>Telefono</label>
                <input type="text"  class="form-control" :class="{'is-invalid': errors.phone}" v-model="customerOrder.phone" placeholder="9202163">
                <div class="invalid-feedback" v-if="errors.phone">{{errors.phone[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>Celular</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.cellphone}" v-model="customerOrder.cellphone" placeholder="9999978202">
                <div class="invalid-feedback" v-if="errors.cellphone">{{errors.cellphone[0]}}</div>
            </div>
            <div class="form-group col-md-6">
                <label>En que horario le podemos marcar?</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.call_time}" v-model="customerOrder.call_time" placeholder="Comentario ..">
                <div class="invalid-feedback" v-if="errors.call_time">{{errors.call_time[0]}}</div>
            </div>
            <div class="form-group col-md-6">
              <label>Ha viajado con nosotros?</label>
              <multiselect
                v-model="customerOrder.with_us"
                :options="TravelWithUs"
                openDirection="bottom"
                track-by="id"
                label="name"
                :class="{'border border-danger rounded': errors.with_us}">
              </multiselect>
              <small class="form-text text-danger" v-if="errors.with_us">{{errors.with_us[0]}}</small>
            </div>
            <div class="form-group col-md-8">
                <label>Destino del viaje</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.travel_destination}" v-model="customerOrder.travel_destination" placeholder="Destino ..">
                <div class="invalid-feedback" v-if="errors.travel_destination">{{errors.travel_destination[0]}}</div>
            </div>
            <div class="form-group col-md-8">
                <label>Cuantos adultos irían al viaje?</label>
                <input type="number" class="form-control" :class="{'is-invalid': errors.number_adults}" v-model="customerOrder.number_adults" placeholder="Direccion ..">
                <div class="invalid-feedback" v-if="errors.number_adults">{{errors.number_adults[0]}}</div>
            </div>
            <div class="form-group col-md-8">
                <label>Lleva menores? <span class="font-weight-bold">(especificar el numero de menores y edad) 0 a 12 años</span></label>
                <textarea class="form-control" rows="5" :class="{'is-invalid': errors.number_childs}"  v-model="customerOrder.number_childs"></textarea>
                <div class="invalid-feedback" v-if="errors.number_childs">{{errors.number_childs[0]}}</div>
            </div>
            <div class="form-group col-md-8">
                <label>Servicio requeridos<span class="font-weight-bold">(hotel, avion, tour, crucero, etc.)</span></label>
                <textarea class="form-control" rows="5" :class="{'is-invalid': errors.services}"  v-model="customerOrder.services"></textarea>
                <div class="invalid-feedback" v-if="errors.services">{{errors.services[0]}}</div>
            </div>
            <div class="form-group col-md-8">
                <label>Otros comentarios</label>
                <textarea class="form-control" rows="5" :class="{'is-invalid': errors.comments}"  v-model="customerOrder.comments"></textarea>
                <div class="invalid-feedback" v-if="errors.comments">{{errors.comment[0]}}</div>
            </div>
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
      errors: {},
      es: es,
      loading: true,
      submiting: false,
      submitingDestroy: false,
      customerOrder: {
          customer:{
            first_name:null
          }
      },
      month:[
        { id: 'ENERO', name: 'ENERO' },
        { id: 'FEBRERO', name: 'FEBRERO' },
        { id: 'MARZO', name: 'MARZO' },
        { id: 'ABRIL', name: 'ABRIL' },
        { id: 'MAYO', name: 'MAYO' },
        { id: 'JUNIO', name: 'JUNIO' },
        { id: 'JULIO', name: 'JULIO' },
        { id: 'AGOSTO', name: 'AGOSTO' },
        { id: 'SEPTIEMBRE', name: 'SEPTIEMBRE' },
        { id: 'OCTUBRE', name: 'OCTUBRE' },
        { id: 'NOVIEMBRE', name: 'NOVIEMBRE' },
        { id: 'DICIEMBRE', name: 'DICIEMBRE' },
      ],
      TravelWithUs:[
        { id: 'SI', name: 'SI' },
        { id: 'NO', name: 'NO' },
        { id: 'ME RECOMENDARON', name: 'ME RECOMENDARON' },
      ],
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
      axios.get(`../../api/customers_orders/${res[3]}`)
      .then(response => {
          this.customerOrder = response.data
          this.customerOrder.travel_month = {id: response.data.travel_month, name: response.data.travel_month};
          this.customerOrder.with_us = {id: response.data.with_us, name: response.data.with_us};

      })
      .catch(error => {
          this.$toasted.global.error('User does not exist!')
          location.href = '../../customers'
      })
      .then(() => {
        this.loading = false
      })
    },
    customFormatter(date) {
      this.customerOrder.travel_date = moment(date).format('YYYY/MM/DD');
      return moment(date).format('YYYY-MM-DD');
    },
    customFormatterEnd(date) {
      this.customerOrder.travel_end_date = moment(date).format('YYYY/MM/DD');
      return moment(date).format('YYYY-MM-DD');
    },
    update () {
      if (!this.submiting) {
        this.submiting = true
        axios.put(`../../api/customers_orders/update/${this.customerOrder.id}`, this.customerOrder)
        .then(response => {
            this.$toasted.global.error('Orden actualizada !')
            location.href = '../../customers_orders'
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
