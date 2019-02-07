<template>
  <div class="card text-white bg-primary">
    <div class="card-body">
      <div class="h1 text-muted text-right mb-12">
        <i class="icon-people"></i>
      </div>

      <div class="text-value">{{items.length}}</div>
      <small class="text-muted text-uppercase font-weight-bold">{{ $t('dashboard.prospecting_created') }}</small>
      <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Nombres</th>
                <th>Telefono</th>
                <th>Celular</th>
                <th>Ramo</th>
                <th>Estatus</th>
              </tr>
            </thead>
            <tbody>
                <tr v-for="item of items" :key="item['.key']">
                  <td>{{ item.first_name }} {{ item.last_name }}</td>
                  <td>{{ item.phone }}</td>
                  <td>{{ item.cellphone }}</td>
                  <td v-if="item.branch_id === 1" >AUTOS</td>
                  <td v-else-if="item.branch_id === 2" >PERSONAS</td>
                  <td v-if="item.branch_id === 3" >DAÑOS</td>
                  <td v-if="item.status">{{ item.status }}</td>
                  <td v-else>Creado recientemente</td>
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
      items: []
    }
  },
  firebase: {
    items: db.ref('prospectings')
  },
  mounted () {
    this.getRolesCount()
  },
  methods: {
    getRolesCount () {
      axios.get(`/api/prospectings/count`)
      .then(response => {
        this.prospectings = response.data
      })
    },
  }
}
</script>
