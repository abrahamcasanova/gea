<template>
  <div class="card">
    <div class="card-body">
      <div class="h3 text-muted text-right mb-12">
        <h4 class="text-muted text-uppercase font-weight-bold">ultimos seguimientos registrados {{items.length}}
            <i class="fa-lg icon-people text-primary"></i>
        </h4>
      </div>
      <div class="card-body px-0">
        <div class="table-responsive">
        <b-table
          class="table"
          :items="items"
          :fields="tableFields"
          :sort-by.sync="sortBy"
          :sort-desc.sync="sortDesc"
          :tbody-tr-class="rowClass">
          <template slot="actions" slot-scope="row">
            <a :href="`./quotes/track-quote?id=${row.item.quote.id}`" class="btn btn-primary">
              Actualizar
            </a>
          </template>
        </b-table>
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
  data () {
    return {
      prospectings: 0,
      items: [],
      selected: [],
      searchQuery:'',
      loading: true,
      sortBy: 'id',
      sortDesc: false,
      tableFields: [
        { key: 'id', sortable: true },
        { key: 'quote.customer_order.customer.full_name',label:'Cliente',
        sortable: true, status:'awesome'},
        { key: 'quote.customer_order.customer.cellphone',label:'Celular', sortable: true },
        { key: 'quote.customer_order.customer.type_of_person', sortable: true,label:'Tipo' },
        { key: 'user.name', sortable: true,label:'Agente' },
        { key: 'track_status', sortable: true,label:'Estatus' },
        { key: 'comments', sortable: true,label:'Comentario' },
        { key: 'contact_date', sortable: true,label:'Fecha de contacto' },
        { key: 'days', sortable: true,label:'Días transcurridos' },
        { key: 'actions', sortable: true,label:'Acciones' },
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
  firebase: {
    items: db_dashbord
  },
  mounted () {
    this.getCountQuoteTrack()
    let db = firebase.database();
    let today = Date.now();
    let today_moment = moment(today);

    db_dashbord.once('value', function (snapshot) {
        snapshot.forEach(function (childSnapshot) {
            let childKey = childSnapshot.key;
            let childData = childSnapshot.val();
            let childDay = childData.contact_date;
            let days_trans = today_moment.diff(childDay,'days')
            let type =  null;
            if(days_trans <= 1){
              type = 'text-success';
            }else if(days_trans == 2 || days_trans == 3){
              type = 'text-warning';
            }
            else{
              type = 'text-danger';
            }

            db.ref("quote_tracks/" + childKey).update({ days: days_trans, type: type });
        });
    });
  },
  methods: {
    getCountQuoteTrack () {
      axios.get(`./api/quote-tracks/count`)
      .then(response => {
        this.prospectings = response.data
      })
    },
    filter() {
      this.filters.pagination.current_page = 1
    },
    rowClass(item, type) {
      if (!item) return
      if (item.type === 'text-danger') return 'bg-danger font-weight-bold'
      if (item.type === 'text-warning') return 'bg-warning font-weight-bold'
      if (item.type === 'text-success') return 'bg-success font-weight-bold'
    },
    changeSize (perPage) {
      this.filters.pagination.current_page = 1
      this.filters.pagination.per_page = perPage
    },
  }
}
</script>
