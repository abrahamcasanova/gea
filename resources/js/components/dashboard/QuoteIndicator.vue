<template>
  <div class="card text-white">
    <div class="card-body">
      <div class="table responsive">
        <table class="table table-hover">
            <thead>
              <tr>
                <th colspan="3">
                  INDICADOR DE SOLICITUD DE COTIZACIONES
                    <i class="fa-lg fas fa-traffic-light"></i>
                </th>
              </tr>
              <tr>
                <th>
                  ESTATUS
                </th>
                <th style="text-align:center">
                  TOTAL
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(indicator, index) in indicators">
                <td v-if="index == 'ATENCIÓN INMEDIATA'" class="bg-danger"><strong>{{index}}</strong></td>
                <td v-else-if="index == 'DAR ATENCIÓN'" class="bg-warning"><strong>{{index}}</strong></td>
                <td v-else class="bg-success"><strong>{{index}}</strong></td>
                <td style="text-align:center" v-if="index == 'ATENCIÓN INMEDIATA'" class="bg-danger"><strong>{{indicator ? indicator:null}}</strong></td>
                <td style="text-align:center" v-else-if="index == 'DAR ATENCIÓN'" class="bg-warning"><strong>{{indicator ? indicator:null}}</strong></td>
                <td style="text-align:center" v-else class="bg-success"><strong>{{indicator ? indicator:null}}</strong></td>
              </tr>
            </tbody>
          </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      indicators:{},
      start_date:null,
      end_date:null
    }
  },
  mounted () {
    this.getTracing()
  },
  methods: {
    getTracing () {
      axios.get(`./api/quote-tracks/quote-indicator`)
      .then(response => {
        this.indicators = response.data
      })
    },
  }
}
</script>
