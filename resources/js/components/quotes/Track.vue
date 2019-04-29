<template>
  <div class="card card-body col-md-12 container" style="margin-top:25px;">
    <div class=" row justify-content-md-center">
      <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <div class="form-group col-md-12">
            <div class="row">
                <div class="form-group col-md-9">
                    <label><h4 class="float-left pt-2">Seguimiento de cotización</h4></label>
                    <multiselect
                      v-model="track.quote_id"
                      :options="quotes"
                      openDirection="bottom"
                      track-by="id"
                      @input="onChange"
                      :custom-label="fullQuote"
                      :class="{'border border-danger rounded': errors.quote_id}">
                    </multiselect>
                    <small class="form-text text-danger" v-if="errors.quote_id">{{errors.quote_id[0]}}</small>
                </div>
                <div class="form-group col-sm-12 col-md-3">
                    <div class="card-header-actions mr-1">
                      <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="create">
                        <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
                        <span class="ml-1 v-else">Guardar <i class="fas fa-plus-circle"></i></span>
                      </a>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label><h4 class="float-left pt-2">Agentes</h4></label>
                    <multiselect
                      v-model="track.user_id"
                      :options="users"
                      openDirection="bottom"
                      track-by="id"
                      label="name"
                      :class="{'border border-danger rounded': errors.user_id}">
                    </multiselect>
                    <small class="form-text text-danger" v-if="errors.user_id">{{errors.user_id[0]}}</small>
                </div>
                <div class="form-group col-md-4">
                    <label><h4 class="float-left pt-2">Estatus</h4></label>
                    <multiselect
                      v-model="track.track_status"
                      :options="options"
                      openDirection="bottom"
                      track-by="value"
                      label="name"
                      :class="{'border border-danger rounded': errors.track_status}">
                    </multiselect>
                    <small class="form-text text-danger" v-if="errors.track_status">{{errors.track_status[0]}}</small>
                </div>
                <div class="form-group col-md-3">
                    <label><h4 class="float-left pt-2">Fecha de contacto</h4></label>
                    <datepicker :bootstrap-styling="true" required :language="es" :format="customFormatter" v-model="track.contact_date"></datepicker>
                    <small class="form-text text-danger" v-if="errors.contact_date">{{errors.contact_date[0]}}</small>
                </div>
                <div class="form-group col-md-9">
                    <label><h4 class="float-left pt-2">Comentario</h4></label>
                    <v-textarea
                      outline
                      name="input-7-1"
                      v-model="track.comments"
                      label=""
                      value=""
                    ></v-textarea>
                    <small class="form-text text-danger" v-if="errors.comments">{{errors.comments[0]}}</small>
                </div>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-sm-12 card-body px-0">
          <v-timeline align-top>
                <v-timeline-item v-for="(item, i) in items" :key="i" :color="item.color" :icon="item.icon" fill-dot >
                  <v-flex xs12 sm12  >
                      <v-card :color="item.color" dark >
                        <v-card-title class="justify-end">
                          <h3 class="text-uppercase">{{item.user}} | {{item.status}} </h3>
                        </v-card-title>
                        <v-card-title class="justify-center">
                          <h4 class="white--text font-weight-light">Fecha de contacto: {{item.contact_date}}</h4>
                          <i class=""></i>
                        </v-card-title>
                        <v-card-text class="text--primary">
                          <v-layout>
                            <v-flex xs10>
                              {{item.comment}}
                            </v-flex>
                            <v-flex xs2>
                                <i class="fa-4x far fa-calendar-alt"></i>
                            </v-flex>
                          </v-layout>
                        </v-card-text>
                      </v-card>
                  </v-flex>
                </v-timeline-item>
              </v-timeline>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
var csrf_token = $('meta[name="csrf-token"]').attr('content');
import Datepicker from 'vuejs-datepicker';
import {en, es} from 'vuejs-datepicker/dist/locale'
import moment from 'moment'
import vueUrlParameters from 'vue-url-parameters';

const theme = 'red';

export default {
  components: {
      Datepicker,
  },
  data () {
    return {
      users: [],
      track: {},
      quotes: [],
      errors: {},
      timeline: [],
      searchParams: {
        q: null,
        type: 'all'
      },
      options: [
        { name: 'Llamada',      value: 'Llamada' },
        { name: 'Mensaje',      value: 'Mensaje' },
        { name: 'Enviado',      value: 'Enviado' },
        { name: 'Confirmación', value: 'Confirmación' },
        { name: 'Reservado',    value: 'Reservado' },
        { name: 'No viajo',      value: 'No viajo' },
      ],
      items: [],
      submiting: false,
      es: es,
    }
  },
  mounted () {
      this.getUsers()
      this.getQuotes()
  },
  methods: {
      fullQuote ({ id, customer_order }) {
          if(id){
             return `Folio: ${id} | ${customer_order.customer.full_name}`
          }
      },
      customFormatter(date) {
          this.track.contact_date = moment(date).format('YYYY/MM/DD');
          return moment(date).format('YYYY-MM-DD');
      },
      create () {
          if (!this.submiting) {
              this.submiting = true
              axios.post(`../api/quote-tracks/store-track`, this.track)
              .then(response => {
                  this.$toasted.global.error('Guardado con exito!')
                  this.onChange();
                  this.track.user_id  = this.track.track_status = this.track.contact_date = this.track.comments = null;

                  this.submiting = false
              })
              .catch(error => {
                  this.errors = error.response.data.errors
                  this.submiting = false
              })
          }
      },
      getUsers () {
          this.loading = true
          this.users = []
          axios.get(`../api/users/all`)
          .then(response => {
            this.users = response.data
          })
      },
      getQuotes () {
        this.loading = true
        this.quotes = []
        axios.get(`../api/quotes/all`)
        .then(response => {
          this.quotes = response.data
          let uri = window.location.search.substring(1);
          let params = new URLSearchParams(uri);
          if(params.get('id')){
              axios.get(`../api/quotes/by-id/` + params.get('id'))
              .then(response => {
                  this.track.quote_id =  response.data
                  this.onChange();
              })
              .catch(error => {
                  this.errors = error.response.data.errors
              })
          }
        })
      },
      nameWithLang ({ first_name, last_name, branches }) {
          return `${first_name} ${last_name} | ${branches.name}`
      },
      onChange(){
          this.items = []

          var id = this.track.quote_id ? this.track.quote_id.id:null;
          if(this.track.quote_id != null){
              axios.post(`../api/quote-tracks/get-track`, {
                  id: id
              })
              .then(response => {
                  this.items = response.data;
              })
              .catch(error => {
                this.errors = error.response.data.errors
              })
          }else{
              this.items = []
          }

      }
    },
}
</script>
