<template>
  <div class="card text-white">
    <div class="card-body">
      <div class="table responsive">
        <table class="table table-hover">
            <thead>
              <tr>
                <th colspan="3">
                    MONTO MAXIMO POR GASTAR: {{general_config.maximum_expenses | currency}}
                    <i class="fa-lg fas fa-wallet text-primary"></i>
                </th>
                <th colspan="3">
                    <label v-if="payments.current_payment < general_config.maximum_expenses" class="text-primary"> GASTOS DE OFICINA {{ payments.current_payment | currency}} </label>
                    <label v-else class="text-danger"> GASTOS DE OFICINA {{ payments.current_payment | currency}} </label>
                    <i class="fa-lg fas fa-wallet text-primary"></i>
                </th>
              </tr>
              <tr>
                <th colspan="3" style="text-align:left">
                  SERVICIO
                </th>
                <th colspan="3" style="text-align:left">
                  PAGADO
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(payment, index) in payments">
                <td colspan="3" class="bg-success"><strong>{{ payment.service ? payment.service.name:null }}</strong></td>
                <td colspan="3" style="text-align:left" class="bg-success"><strong>{{ payment.amount | currency}}</strong></td>
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
      general_config:{},
      payments:{},
      start_date:null,
      end_date:null
    }
  },
  mounted () {
    this.getGeneralConfig()
  },
  methods: {
    getGeneralConfig () {
      axios.get(`./api/general_config/all`)
      .then(response => {
        this.general_config = response.data
        this.getCurrentPayments()
      })
    },
    getCurrentPayments () {
      axios.get(`./api/services-payment/all`)
      .then(response => {
        this.payments = response.data
        this.payments.current_payment = this.payments.reduce((acc, item) => acc + item.amount, 0);
      })
    },
  }
}
</script>
