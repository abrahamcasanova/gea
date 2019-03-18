<template>
  <div class="card">
    <div class="card-body">
      <div class="h3 text-muted text-right mb-12">
        <h4 class="text-muted text-uppercase font-weight-bold">ultimos seguimientos registrados {{items.length}}
            <i class="fa-lg icon-people text-primary"></i>
        </h4>
      </div>
      <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead class="thead-dark">
              <tr>
                <th># Folio</th>
                <th>Cliente</th>
                <th>Celular</th>
                <th>Agente</th>
                <th>Estatus</th>
                <th>Comentario</th>
                <th>Fecha Contacto</th>
              </tr>
            </thead>
            <tbody>
                <tr v-for="item of items" :key="item['.key']">
                  <td>{{ item.quote_id }}</td>
                  <td>{{ item.quote.customer_order.customer.full_name }}</td>
                  <td>{{ item.quote.customer_order.cellphone }}</td>
                  <td>{{ item.user.name }}</td>
                  <td>{{ item.track_status }}</td>
                  <td>{{ item.comments }}</td>                  
                  <td>{{ item.contact_date }}</td>                  
                </tr>
            </tbody>
          </table>        
      </div>
    </div>
  </div>
</template>

<script>

import { db } from '../../app';

export default {
  data () {
    return {
      prospectings: 0,
      items: [],
      selected: [],
      pagination: {
        sortBy: 'name'
      },
      headers: [
        {
          text: 'Dessert (100g serving)',
          align: 'left',
          value: 'name'
        },
        { text: 'Calories', value: 'calories' },
        { text: 'Fat (g)', value: 'fat' },
        { text: 'Carbs (g)', value: 'carbs' },
        { text: 'Protein (g)', value: 'protein' },
        { text: 'Iron (%)', value: 'iron' }
      ],
    }
  },
  firebase: {
    items: db.ref('quote_tracks')
  },
  mounted () {
    this.getCountQuoteTrack()
  },
  methods: {
    getCountQuoteTrack () {
      axios.get(`./api/quote-tracks/count`)
      .then(response => {
        this.prospectings = response.data
      })
    },
  }
}
</script>
