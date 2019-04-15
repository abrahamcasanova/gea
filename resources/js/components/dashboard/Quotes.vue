<template>
  <v-flex xs12>
    <v-card color="orange darken-2" class="white--text">
    <v-layout>
      <v-flex xs5 style="margin-top:5px;">
        <v-img class="img-responsive" src="./public/img/quotes.png" height="125px" contain ></v-img>
      </v-flex>
      <v-flex xs7>
        <v-card-title primary-title>
          <div>
            <div class="headline">{{ quotes.total }} |  Cotizaciones realizados el dia de hoy.</div>
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
      quotes: 0
    }
  },
  mounted () {
    this.getTracing()
  },
  methods: {
    getTracing () {
      axios.get(`./api/quote-tracks/count-quote`)
      .then(response => {
        this.quotes = response.data
      })
    },
  }
}
</script>
