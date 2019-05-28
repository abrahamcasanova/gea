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

                    <div v-if="show_chart == true && report.type_report == 'Reporte de pagos'" class="form-group col-md-12">
                        <label>Grafica</label>
                        <apexchart width="100%" type="bar" :options="chartOptions" :series="series"></apexchart>
                    </div>
                    <div class="form-group col-md-2">
                      <a class="btn btn-success" href="#" :disabled="submiting" @click.prevent="showChart">
                        <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
                        <span class="ml-1 v-else"> <i class="far fa-chart-bar"></i> Ver grafica </span>
                      </a>
                    </div>
                    <div class="form-group col-md-2">
                      <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="exportReport">
                        <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
                        <span class="ml-1 v-else"> <i class="fas fa-file-download"></i> Exportar </span>
                      </a>
                    </div>

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
      show_chart:false,
      errors: {},
      typeReports: [],
      image: '',
      url: null,
      submiting: false,
      chartOptions: {
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: '55%',
              endingShape: 'rounded'
            },
          },
          dataLabels: {
            enabled: false
          },
          stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
          },
          chart: {
            id: 'vuechart-example'
          },
          xaxis: {
            categories: [192,2]
          },
          fill: {
            opacity: 1

          },tooltip: {
            y: {
              formatter: function (val) {
                return "$ " + val + " thousands"
              }
            }
          },
          yaxis: {
           title: {
             text: '$ (thousands)'
           }
         },
        },
        series: [{
          name: 'Precio Total',
          data: []
        }]
    }
  },
  mounted () {
    this.csrf = window.Laravel.csrfToken;
    if(this.$can('export-pending-payments')){
      this.typeReports.push('Pagos pendientes por cobrar');
    }

    if(this.$can('export-quotes')){
      this.typeReports.push('Reporte de cotizaciones');
    }

    if(this.$can('export-sales')){
      this.typeReports.push('Reporte de ventas');
    }

    if(this.$can('export-payments')){
      this.typeReports.push('Reporte de pagos');
    }

    if(this.$can('export-confirmation-payments-resume')){
      this.typeReports.push('Reporte de pagos de confirmaciones (resumen)');
    }

    if(this.$can('export-confirmation-payments')){
      this.typeReports.push('Reporte de pagos de confirmaciones (gral)');
    }

    if(this.$can('export-cross-payments')){
      this.typeReports.push('Reporte de pagos vs pago de confirmaciones');
    }

  },
  methods: {
      showChart(){
          if (!this.submiting) {
            this.submiting = true
            this.series[0].data = [];
            let categories = [];
            axios({
              url: './api/reports/show-chart',
              method: 'POST',
              params: this.report
            })
            .then(response => {
              if(response.data != 'Sin datos'){
                //this.chartOptions.xaxis.categories = [10,2]
                let vm = this;

                response.data.map(function(value, key) {
                 categories.push(value.type_of_payment)
                 vm.series[0].data.push(parseFloat(value.total))
               });


                const newData = this.series[0].data;

                const colors = ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0']

                // Make sure to update the whole options config and not just a single property to allow the Vue watch catch the change.
                this.chartOptions = {
                  colors: [colors[Math.floor(Math.random()*colors.length)]],
                };
                // In the same way, update the series option
                this.chartOptions.xaxis = {
                  categories:categories
                };

                this.chartOptions.tooltip = {
                  y: {
                    formatter: function (val) {
                      return currencyFormat(val)
                    }
                  }
                };
                /*this.chartOptions = [{
                    xaxis: {
                      categories: categories
                    }
                }];*/

                this.series = [{
                    data: newData,
                    name: 'Precio Total'
                }];
                this.show_chart = true;
                this.submiting = false;

              }else{
                swal("Error, No existe información para mostrar", {
                    icon: 'error',
                    buttons: false,
                    timer: 3000
                });
                this.submiting = false;
              }
            })
            .catch(error => {
              this.errors = error.response.data
              this.submiting = false
            })

          }
      },
      exportReport () {
        if (!this.submiting) {
          this.submiting = true
          axios({
            url: './api/reports/export',
            method: 'POST',
            params: this.report
          })
          .then(response => {
            if(response.data != 'Sin datos'){
                window.location = './storage/' + response.data;
                this.submiting = false;
                this.$toasted.global.error('Exportado correctamente!')
                this.report.initial_date = this.report.final_date = null;
            }else{
              swal("Error, No existe información para mostrar", {
                  icon: 'error',
                  buttons: false,
                  timer: 3000
              });
              this.submiting = false;
            }
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

function currencyFormat(num) {
  return '$' + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

</script>
