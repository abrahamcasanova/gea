<template>
  <v-container>
    <v-app autocomplete="off">
      <v-layout >
        <v-flex xs12>
          <div class="card card-body col-md-12 container" style="margin-top:25px;">
            <div class=" row justify-content-md-center">
              <div class="col-md-12 col-xl-12">
                <div class="card-header px-0 mt-2 bg-transparent clearfix">
                  <h4 class="float-left pt-2">Reportes</h4>
                  <div class="card-header-actions mr-1">
                    <v-select
                      :items="typeReports"
                      v-model="report.type_report"
                      label="Tipo de reporte"
                    ></v-select>
                  </div>
                </div>
                <div class="col-md-12 card-body px-0">
                  <div class="row">
                    <div class="form-group col-md-6">
                      <v-menu lazy offset-y full-width :close-on-content-click="false" v-model="menu"
                        transition="scale-transition" :nudge-right="40" max-width="290px" min-width="290px">
                        <v-text-field slot="activator" label="Fecha Inicial" required v-model="report.initial_date" prepend-icon="event" readonly :error-messages="errors.initial_date"
                        ></v-text-field>
                        <v-date-picker color="blue lighten-1" locale="es-mx" show-current class="mt-3"
                          v-model="report.initial_date">
                          <template slot-scope="{ save, cancel }"></template>
                       </v-date-picker>
                      </v-menu>
                      <small class="invalid-feedback" v-if="errors.initial_date">{{errors.initial_date[0]}}</small>
                    </div>
                    <div class="form-group col-md-6">
                        <v-menu lazy offset-y full-width :close-on-content-click="false" v-model="menu2"
                          transition="scale-transition" :nudge-right="40" max-width="290px" min-width="290px">
                          <v-text-field slot="activator" label="Fecha Final" required v-model="report.final_date" prepend-icon="event" readonly :error-messages="errors.final_date"
                          ></v-text-field>
                          <v-date-picker color="blue lighten-1" locale="es-mx" show-current class="mt-3"
                            v-model="report.final_date">
                            <template slot-scope="{ save, cancel }"></template>
                         </v-date-picker>
                        </v-menu>
                        <small class="invalid-feedback" v-if="errors.final_date">{{errors.final_date[0]}}</small>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Tabla</label>
                       
                    </div>

                    <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="exportReport">
                      <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
                      <span class="ml-1 v-else"> <i class="fas fa-file-download"></i> Exportar </span>
                    </a>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </v-flex>
      </v-layout>
    </v-app>
  </v-container>
</template>

<script>
var csrf_token = $('meta[name="csrf-token"]').attr('content');
export default {
  data () {
    return {
      report: {},
      menu:false,
      menu2:false,
      errors: {},
      typeReports: ['Pagos pendientes por cobrar'],
      image: '',
      url: null,
      submiting: false,
    }
  },
  mounted () {
    this.csrf = window.Laravel.csrfToken;
  },
  methods: {
      exportReport () {
        if (!this.submiting) {
          this.submiting = true
          axios({
            url: './api/reports/export',
            method: 'POST',
            params: this.report
          })
          .then(response => {
            window.location = './storage/excel/exports/Pagos.xls';
            this.submiting = false;
            this.$toasted.global.error('Exportado correctamente!')
            this.report.type_report = this.report.initial_date = this.report.final_date = null;
          })
          .catch(error => {
            this.errors = error.response.data.errors
            this.submiting = false
          })

        }
      },
      getProductTypes() {

          axios.get(`../api/products_type/all`).then(response => {
              this.product_types = response.data
          })
          .catch(error => {
              this.errors = error.response.data.errors
          })
      },
      onFileChange(e) {
        const file = e.target.files[0];
        this.url = URL.createObjectURL(file);
        this.product.url_image = this.url;
      }
      ,onImageChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0]);
      },
      createImage(file) {
          let reader = new FileReader();
          let vm = this;
          reader.onload = (e) => {
              vm.image = e.target.result;
              this.product.url_image = e.target.result;
          };
          reader.readAsDataURL(file);
      },
  }
}
</script>
