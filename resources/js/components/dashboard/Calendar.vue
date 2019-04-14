<template>
  <v-app id="dayspan" v-cloak>
    <v-layout wrap>
    <v-calendar
          :value="today"
          ref="calendar"
          v-model="today"
          :type="type"
          :end="end"
          color="primary"
          locale="es-419"
          >
          <template v-slot:day="{ date }">
            <template v-for="event in eventsMap[date]">
              <v-menu
                :key="event.id"
                v-model="event.open"
                full-width
                offset-x
              >
                <template v-slot:activator="{ on }">
                  <div
                    v-if="!event.time"
                    v-ripple
                    :class="event.type"
                    v-on="on"
                    v-html="event.title"
                  ></div>
                </template>
                <v-card
                  color="grey lighten-4"
                  min-width="350px"
                  flat
                >
                  <v-toolbar
                    :color="event.type"
                    dark
                  >
                    <v-toolbar-title v-html="event.title + ' Folio venta: ' + event.sale_id"></v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon>
                      <v-icon>attach_money</v-icon>
                    </v-btn>
                  </v-toolbar>
                  <v-card-title primary-title>
                    <span v-html="event.details"></span>
                  </v-card-title>
                  <v-card-actions>
                    <v-btn
                      flat
                      color="secondary"
                    >
                      Cancelar
                    </v-btn>
                    <v-btn round color="success" style="width: auto;" v-on:click="firebasePayment(event.id)">
                      Pagar
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-menu>
            </template>
          </template>
        </v-calendar>
         <v-flex
            sm4
            xs12
            md6
            class="text-sm-left text-xs-center"
          >
            <v-btn @click="$refs.calendar.prev()">
              <v-icon
                dark
                left
              >
                keyboard_arrow_left
              </v-icon>
              Prev
            </v-btn>
          </v-flex>
          <v-flex
            sm4
            xs12
            md6
            class="text-sm-right text-xs-center"
          >
            <v-btn @click="$refs.calendar.next()">
              Siguiente
              <v-icon
                right
                dark
              >
                keyboard_arrow_right
              </v-icon>
            </v-btn>
          </v-flex>
            </v-layout>

  </v-app>

</template>
<style lang="stylus" scoped>
    .success {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      border-radius: 2px;
      background-color: #4caf50;
      color: #ffffff;
      border: 1px solid #4caf50;
      width: 100%;
      font-size: 12px;
      padding: 3px;
      cursor: pointer;
      margin-bottom: 1px;
    }

  .info {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    border-radius: 2px;
    background-color: #1867c0;
    color: #ffffff;
    border: 1px solid #1867c0;
    width: 100%;
    font-size: 12px;
    padding: 3px;
    cursor: pointer;
    margin-bottom: 1px;
  }

  .warning {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    border-radius: 2px;
    background-color: #ffeb3b;
    color: #ffffff;
    border: 1px solid #ffeb3b;
    width: 100%;
    font-size: 12px;
    padding: 3px;
    cursor: pointer;
    margin-bottom: 1px;
  }

  .danger {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    border-radius: 2px;
    background-color: #f44336;
    color: #ffffff;
    border: 1px solid #f44336;
    width: 100%;
    font-size: 12px;
    padding: 3px;
    cursor: pointer;
    margin-bottom: 1px;
  }
</style>
<script>
import { db } from '../../app';
import { firebase } from '../../app';
import moment from 'moment';
import {en, es} from 'vuejs-datepicker/dist/locale'

export default {
  data: vm => ({
    readOnly: false,
    isReadOnly:false,
    canSave: true,
    labels: {
        save:'Guardar'
    },
    defaultEvents: [],
    today: moment().format('YYYY-MM-DD'), 
    events: [
        {
          title: 'Vacation',
          details: 'Going to the beach!',
          date: '2019-03-13',
          open: false
        }
      ],
      type: 'month',
      start: '2019-01-01',
      end: '2019-01-06',
      typeOptions: [
        { text: 'Day', value: 'day' },
        { text: '4 Day', value: '4day' },
        { text: 'Week', value: 'week' },
        { text: 'Month', value: 'month' },
        { text: 'Custom Daily', value: 'custom-daily' },
        { text: 'Custom Weekly', value: 'custom-weekly' }
      ]
  }),
  es: es,
  firebase: {
    calendarEvents: db.ref('events')
  },
  mounted()
  {
      window.app = this;
      window.app.refs = this.$refs.app;
      var userRef = db.ref('/events');
      let json = null;
      userRef.on('value', function(snapshot) {
          let data = snapshot.val()
          var arr3 = [];
          $.each( data, function( key, value ) {
              $.each( value, function( key, value ) {
                  if(value){
                    arr3.push(value);
                  }
              });
          });

          data = arr3;
          if(data){
              window.app.loadFirebase(data)
          }else{
              window.app.loadState()
          }
      }); 

      this.setLocale('es')
  },
  props: {
      user_id: Number ,
  },
  computed: {
      // convert the list of events into a map of lists keyed by date
      eventsMap () {
        const map = {}

        this.events.forEach(

          e => (map[e.date] = map[e.date] || []).push(e)
        )
        return map
      }
  },
  methods:
  {
    getCalendarTime(calendarEvent)
    {
      let sa = calendarEvent.start.format('a');
      let ea = calendarEvent.end.format('a');
      let sh = calendarEvent.start.format('h');
      let eh = calendarEvent.end.format('h');
      if (calendarEvent.start.minute !== 0)
      {
        sh += calendarEvent.start.format(':mm');
      }
      if (calendarEvent.end.minute !== 0)
      {
        eh += calendarEvent.end.format(':mm');
      }
      return (sa === ea) ? (sh + ' - ' + eh + ea) : (sh + sa + ' - ' + eh + ea);
    },
    setLocale(code)
    {
      moment.locale(code);
      //this.$refs.app.$forceUpdate();
    },
    saveState()
    {
      let state = this.calendar.toInput(true);
      let json = JSON.stringify(state);
      localStorage.setItem(this.storeKey, json);
      if(state){
        axios.post(`./api/users/calendar`,state)
        .then(response => {
            console.log(response.data)
        })
        .catch(error => {
          this.errors = error.response.data.errors
          console.log(this.errors)
        })
      }
    },
    firebasePayment(value){
        //Eliminar de firebase y actualiza Calendario
        db.ref('/events').on("child_added", function(snapshot) {
          snapshot.forEach(function(data) {
              let datas = data.val();
              if(datas.id == value){
                  console.log(snapshot.key);
                  db.ref('/events/'+ snapshot.key +'/' + data.key).remove();
              }
          });
        });
    },
    loadFirebase(data){
        let state = {};
        try
        {
          if (data){
              state = data;
              state.preferToday = false;
          }
        }
        catch (e)
        {
          console.log( e );
        }
        this.events = state
    },
    loadState()
    {
      let state = {};
      try
      {
        let savedState = JSON.parse(localStorage.getItem(this.storeKey));
        if (savedState){
            state = savedState;
            state.preferToday = false;
        }
      }
      catch (e)
      {
        console.log( e );
      }
      if (!state.events || !state.events.length)
      {
        state.events = this.defaultEvents;
      }
        this.events = [state.events]

      //this.$refs.app.setState( state );
    }
  }
}
</script>

<style>

body, html, #app, #dayspan {
  font-family: Roboto, sans-serif !important;
  width: 100%;
  height: 100%;
}

.v-btn--flat,
.v-text-field--solo .v-input__slot {
  background-color: #f5f5f5 !important;
  margin-bottom: 8px !important;
}

</style>
