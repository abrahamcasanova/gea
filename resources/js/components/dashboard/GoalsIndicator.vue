<template>
  <div class="card">
    <div class="card-body">
      <div class="h3 text-muted text-right mb-12">
        <h4 class="text-muted text-uppercase font-weight-bold">Metas
            <i class="fa-lg icon-people text-primary"></i>
        </h4>
      </div>
      <div class="card-body px-0">
        <div class="table-responsive">
          <v-data-table
            :headers="tableFields"
            :items="items"
            :search="search"
            v-bind:pagination.sync="pagination"
          >
            <template v-slot:items="props">
              <td class="text-xs-center">{{ props.item.name }}</td>
              <td class="text-xs-center">{{  props.item.sales_goal | currency}}</td>
              <td class="text-xs-center">{{ props.item.total_sale | currency }}</td>
              <td class="text-xs-center">
                  <h3>
                    <span v-if="props.item.percentage >= 81" class='badge badge-success'>{{ props.item.percentage }} %</span>
                    <span v-else-if="props.item.percentage >= 61.99 && props.item.percentage <= 80.99 " class='badge badge-warning'>{{ props.item.percentage }} %</span>
                    <span v-else class='badge badge-danger'>{{ props.item.percentage }} %</span>
                  </h3>
              </td>
            </template>
            <template v-slot:no-results>
              <v-alert :value="true" color="error" icon="warning" style="background-color:#ff5252!important;">
                Su búsqueda de "{{search}}" no encontró resultados.
              </v-alert>
            </template>
            <template v-slot:no-data>
              <v-alert :value="true" color="error" icon="warning" style="background-color:#ff5252!important;">
                Lo sentimos, no hay nada que mostrar aquí :(
              </v-alert>
            </template>
          </v-data-table>

        </div>
      </div>
    </div>
  </div>
</template>

<script>

import { db_dashbord, firebase } from '../../app';
import moment from 'moment'


export default {
  computed: {
    dashbordFilter (){
      var filterKey = this.searchQuery && this.searchQuery.toLowerCase()
      var dashbord = this.items
      if (filterKey) {
        dashbord = dashbord.filter(function (row) {
          return Object.keys(row).some(function (key) {
            return String(row[key]).toLowerCase().indexOf(filterKey) > -1
          })
        })
      }
      return dashbord;
    }
  },
  props: {
      user_id: Number ,
  },
  data () {
    return {
      prospectings: 0,
      items: [],
      selected: [],
      search:null,
      pagination: {'sortBy': 'name', 'descending': true, rowsPerPage: 15 },
      searchQuery:'',
      loading: true,
      sortBy: 'id',
      sortDesc: false,
      items: [],
      tableFields: [
        { value: 'name', align: 'center',text:'Usuario/Agente' },
        { value: 'sales_goal', align: 'center',text:'Monto de meta' },
        { value: 'total_sale', align: 'center',text:'Cantidad al día de hoy' },
        { value: 'actions', align: 'center',text:'Porcentaje' },
      ],
      pagination: {
        sortBy: 'name'
      },
      filters: {
        pagination: {
          from: 0,
          to: 0,
          total: 0,
          per_page: 25,
          current_page: 1,
          last_page: 0
        },
        orderBy: {
          column: 'id',
          direction: 'asc'
        },
        search: ''
      },
      sortKey: 'name',
      reverse: false,
    }
  },
  mounted () {
    this.getGoals(this.user_id);
  },
  methods: {
    getGoals (userId) {
      axios.post(`./api/users/goals/` + userId)
      .then(response => {
        this.items = response.data
      })
    },
    rowClass(item, type) {
      if (!item) return
      if (item.type === 'text-danger') return 'bg-danger font-weight-bold'
      if (item.type === 'text-warning') return 'bg-warning font-weight-bold'
      if (item.type === 'text-success') return 'bg-success font-weight-bold'
    },
  }
}
</script>
