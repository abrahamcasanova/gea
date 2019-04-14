<template>
  <v-flex xs12>
    <v-card color="white darken-2" class="white--black">
    <v-layout>
      <v-flex xs5 style="margin-top:5px;">
        <v-img class="img-responsive" src="./public/img/sold.png" height="125px" contain ></v-img>
      </v-flex>
      <v-flex xs7>
        <v-card-title primary-title>
          <div>
            <div class="headline">{{sold.total}} | Ventas realizadas el dia de hoy.</div>
          </div>
        </v-card-title>
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
      sold: 0
    }
  },
  mounted () {
    this.getTracing()
  },
  methods: {
    getTracing () {
      axios.get(`./api/quote-tracks/count-sold`)
      .then(response => {
        this.sold = response.data
      })
    },
  }
}
</script>
