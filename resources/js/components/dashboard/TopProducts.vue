<template>
  <v-flex xs12>
    <v-card color="white darken-2" class="white--text">
    <v-layout>
      <v-flex xs3 style="margin-top:25px;">
        <v-img class="img-responsive" src="./public/img/top.png" height="100px" contain ></v-img>
      </v-flex>
      <v-flex xs9>
        <v-card-text primary-title>
          <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                  <tr>
                    <th colspan="3">
                      Del {{start_date}} al {{end_date}}
                    </th>
                  </tr>
                  <tr>
                    <th class="">
                      #
                    </th>
                    <th>
                      PRODUCTO/SERVICIO
                    </th>
                    <th>
                      TOTAL
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(top, index) in tops">
                    <td class="">{{index + 1 }}</td>
                    <td class="">{{top.product ? top.product.name:null}}</td>
                    <td class="">{{top.total}}</td>
                  </tr>
                </tbody>
              </table>
          </div>
        </v-card-text>
      </v-flex>
    </v-layout>
    <v-divider light></v-divider>
    </v-card>
  </v-flex>
</template>

<script>
export default {
  data () {
    return {
      tops:{},
      start_date:null,
      end_date:null
    }
  },
  mounted () {
    this.getTracing()
  },
  methods: {
    getTracing () {
      axios.get(`./api/quote-tracks/top-products`)
      .then(response => {
        this.start_date =  response.data[0];
        this.end_date =  response.data[1];
        this.tops = response.data[2]
      })
    },
  }
}
</script>
