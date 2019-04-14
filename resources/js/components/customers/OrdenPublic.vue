<template>
  <div class="container">
    <v-app id="dayspan" v-cloak>
      <v-layout wrap>        
      <div class="row justify-content-md-center">
      <div class="card">
          <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Sun Travel Mérida "Solicitud de Cotización"</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="create">
              <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
              <i class="fas fa-check" v-else></i>
              <span class="ml-1">Guardar</span>
            </a>
          </div>
        </div>
        <div class="col-md-12 card-body px-0">
          <div class="row">
            <div class="form-group col-md-4">
                <label>Nombres</label>
                <input type="text"  class="form-control" :class="{'is-invalid': errors.first_name}" v-model="customerOrder.first_name" placeholder="Sabina">
                <div class="invalid-feedback" v-if="errors.first_name">{{errors.first_name[0]}}</div>
            </div>
            <div class="form-group col-md-4">
                <label>Apellidos</label>
                <input type="text"  class="form-control" :class="{'is-invalid': errors.last_name}" v-model="customerOrder.last_name" placeholder="Casanova Ortiz">
                <div class="invalid-feedback" v-if="errors.last_name">{{errors.last_name[0]}}</div>
            </div>
            <div class="form-group col-md-4">
              <label>Email</label>
              <input type="email"  class="form-control" :class="{'is-invalid': errors.email}" v-model="customerOrder.email" placeholder="sabina.casanova@hotmail.com">
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
                <label>Fecha inicial de viaje?</label>
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
                :disabled="isDisabled"
                openDirection="bottom"
                track-by="id"
                label="name"
                :class="{'border border-danger rounded': errors.with_us}">
              </multiselect>
              <small class="form-text text-danger" v-if="errors.with_us">{{errors.with_us[0]}}</small>
            </div>
            <div class="form-group col-md-8">
                <label>Destino del viaje</label>
                <v-autocomplete
                  v-model="customerOrder.travel_destination"
                  :items="items"
                  :loading="isLoading"
                  :search-input.sync="search"
                  chips
                  :multiple="true"
                  clearable
                  hide-details
                  hide-selected
                  item-text="name"
                  item-value="id"
                  label="Buscar destinos"
                  solo
                >
                  <template v-slot:no-data>
                    <v-list-tile>
                      <v-list-tile-title>
                        Ejemplo..
                        <strong>Riviera Maya</strong>
                      </v-list-tile-title>
                    </v-list-tile>
                  </template>
                  <template v-slot:selection="{ item, selected }">
                    <v-chip
                      :selected="selected"
                      color="blue-grey"
                      @input="remove(item)"
                      close
                      class="white--text"
                    >
                      <span v-text="item.name"></span>
                    </v-chip>
                  </template>
                  <template v-slot:item="{ item }">
                    <v-list-tile-avatar
                      color="indigo"
                      class="headline font-weight-light white--text"
                    >
                      {{ item.name.charAt(0) }}
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                      <v-list-tile-title v-text="item.name"></v-list-tile-title>
                    </v-list-tile-content>
  
                  </template>
                </v-autocomplete>
                <small class="form-text text-danger" v-if="errors.travel_destination">{{errors.travel_destination[0]}}</small>
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
    </v-layout>
  </v-app>

    
  </div>
</template>

<script>
var csrf_token = $('meta[name="csrf-token"]').attr('content');
import Datepicker from 'vuejs-datepicker';
import {en, es} from 'vuejs-datepicker/dist/locale'
import moment from 'moment'

export default {
  components: {
      Datepicker,
  },
  watch: {

  },
  data () {
    return {
      customerOrder: {},
      errors: {},
      customers:[],
      isLoading: false,
      items: [],
      model: null,
      search: null,
      es: es,
      isDisabled: false,
      menu: false,
      loading: true,
      submiting: false,
      submitingDestroy: false,
      type_of_person: [
          { id: 'PROSPECTO', name: 'PROSPECTO' },
          { id: 'CLIENTE', name: 'CLIENTE' },
      ],
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
    this.csrf = window.Laravel.csrfToken;
    this.isLoading = true

    // Lazily load input items
    axios.get('../customers/all-destinations')
    .then(response => {
        this.items = response.data
    })
    .catch(err => {
        console.log(err)
    })
    .finally(() => (this.isLoading = false))

  },
  methods: {
    create () {
      if (!this.submiting) {
        this.submiting = true
        this.customerOrder.type = 'public';
        axios.post(`../api/customers/store-order`, this.customerOrder)
        .then(response => {
         
          axios.post(`../api/customers/send-received`,{
            id: response.data.id
          })
          .then(response => {
              swal("Creado correctamente, cerrar ventana!", {
                icon: 'success',
                buttons: false,
              });

            this.submiting = true
            setTimeout(function(){ window.close(); }, 2500);
          }).catch(error => {
              this.$toasted.global.error('Ocurrio un error, intente nuevamente!')
          }).then(() => {
            this.loading = false
          });

        })
        .catch(error => {
          this.errors = error.response.data.errors
          this.submiting = false
        })
      }
    },
    remove(itemRemove) {
        const index2 = this.customerOrder.travel_destination.indexOf(itemRemove.id)
        if (index2 >= 0) this.customerOrder.travel_destination.splice(index2, 1)
    },
    customFormatter(date) {
      this.customerOrder.travel_date = moment(date).format('YYYY/MM/DD');
      return moment(date).format('YYYY-MM-DD');
    },
    customFormatterEnd(date) {
      this.customerOrder.travel_end_date = moment(date).format('YYYY/MM/DD');
      return moment(date).format('YYYY-MM-DD');
    },
  }
}

function indexWhere(array, conditionFn) {
    const item = array.find(conditionFn)
    return array.indexOf(item)
}
</script>
