<template>
  <div class="card card-body col-md-12 container" style="margin-top:25px;">
    <div class=" row justify-content-md-center">
      <div class="col-md-12 col-xl-12">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <div class="form-group col-md-12">
            <div class="row">
                <div class="form-group col-md-9">
                    <label><h4 class="float-left pt-2">Prospectings</h4></label>
                    <multiselect
                      v-model="track.prospecting_id"
                      :options="prospecting_track"
                      openDirection="bottom"
                      track-by="id"
                      @input="onChange"
                      :custom-label="nameWithLang"
                      label="first_name"
                      :class="{'border border-danger rounded': errors.prospecting_id}">
                    </multiselect>
                    <small class="form-text text-danger" v-if="errors.prospecting_id">{{errors.prospecting_id[0]}}</small>
                </div>
                <div class="form-group col-sm-12 col-md-3 d-none d-sm-block">
                    <div class="card-header-actions mr-1">
                      <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="create">
                        <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
                        <span class="ml-1 v-else">Enviar <i class="icon-paper-plane"></i></span>
                      </a>
                    </div>
                </div>
                <div class="form-group col-md-5">
                    <label><h4 class="float-left pt-2">Assistant</h4></label>
                    <multiselect
                      v-model="track.user_assistant_id"
                      :options="assistants"
                      openDirection="bottom"
                      track-by="id"
                      label="name"
                      :class="{'border border-danger rounded': errors.user_assistant_id}">
                    </multiselect>
                    <small class="form-text text-danger" v-if="errors.user_assistant_id">{{errors.user_assistant_id[0]}}</small>
                </div>
                <div class="form-group col-md-4">
                    <label><h4 class="float-left pt-2">Status</h4></label>
                    <multiselect
                      v-model="track.status_prospecting"
                      :options="options"
                      openDirection="bottom"
                      track-by="value"
                      label="name"
                      :class="{'border border-danger rounded': errors.status_prospecting}">
                    </multiselect>
                    <small class="form-text text-danger" v-if="errors.status_prospecting">{{errors.status_prospecting[0]}}</small>
                </div>
                <div class="form-group col-md-9">
                    <label><h4 class="float-left pt-2">Note</h4></label>
                    <textarea v-model="track.note" style="width: 100%" rows="4"></textarea>
                    <small class="form-text text-danger" v-if="errors.note">{{errors.note[0]}}</small>
                </div>
                <div class="form-group col-sm-12 col-md-3 d-sm-block d-md-none">
                    <div class="card-header-actions mr-1">
                      <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="create">
                        <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
                        <span class="ml-1 v-else">Enviar <i class="icon-paper-plane"></i></span>
                      </a>
                    </div>
                </div>
            </div>
          </div>
        </div>
        <div class="col-md-12 card-body px-0">
          <div class="row pt-2">
              <light-timeline :items='items'></light-timeline>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
var csrf_token = $('meta[name="csrf-token"]').attr('content');
const theme = 'red';

export default {
  data () {
    return {
      prospecting_track: [],
      track: {},
      assistants: [],
      errors: {},
      timeline: [],
      options: [
        { name: 'Creado recientemente', value: 'Creado recientemente' },
        { name: 'Contactado', value: 'Contactado' },
        { name: 'Se requiere más info', value: 'Se requiere más info' },
        { name: 'Información solicitada', value: 'Información solicitada' },
        { name: 'Cancelada', value: 'Cancelada' },
        { name: 'Enviada a emisión', value: 'Enviada a emisión' },
        { name: 'Emitida', value: 'Emitida' },
      ],
      items: [],
      submiting: false,
    }
  },
  mounted () {
      this.getProspectings()
      this.getAllAssistants()
  },
  methods: {
      create () {
          if (!this.submiting) {
              this.submiting = true
              axios.post(`/api/prospectings/store-track`, this.track)
              .then(response => {
                  this.$toasted.global.error('Track prospecting created!')
                  //location.href = '/prospectings/track-prospecting'
              })
              .catch(error => {
                  this.errors = error.response.data.errors
                  this.submiting = false
              })
          }
      },
      getProspectings () {
        this.loading = true
        this.prospecting_track = []
        axios.get(`/api/prospectings/all`)
        .then(response => {
          this.prospecting_track = response.data
        })
      },
      getAllAssistants () {
        this.loading = true
        this.assistants = []
        axios.get(`/api/prospectings/all-assistants`)
        .then(response => {
          this.assistants = response.data
        })
      },
      nameWithLang ({ first_name, last_name, branches }) {
          return `${first_name} ${last_name} | ${branches.name}`
      },
      onChange(){
          this.items = []
          var id = this.track.prospecting_id ? this.track.prospecting_id.id:null;
          if(this.track.prospecting_id != null){
              axios.post(`/api/prospectings/get-track`, {
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
