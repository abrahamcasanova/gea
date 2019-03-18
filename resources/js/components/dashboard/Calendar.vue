<template>
  <v-app id="dayspan" v-cloak>
    <v-calendar
          :now="today"
          :value="today"
          color="primary"
          locale="es-419"
        >
          <template v-slot:day="{ date }">
            <template v-for="event in eventsMap[date]">
              <v-menu
                :key="event.title"
                v-model="event.open"
                full-width
                offset-x
              >
                <template v-slot:activator="{ on }">
                  <div
                    v-if="!event.time"
                    v-ripple
                    class="my-event"
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
                    color="primary"
                    dark
                  >
                    <v-btn icon>
                      <v-icon>edit</v-icon>
                    </v-btn>
                    <v-toolbar-title v-html="event.title"></v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon>
                      <v-icon>favorite</v-icon>
                    </v-btn>
                    <v-btn icon>
                      <v-icon>more_vert</v-icon>
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
                      Cancel
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-menu>
            </template>
          </template>
        </v-calendar>

  </v-app>

</template>
<style lang="stylus" scoped>
  .my-event {
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
</style>
<style>
#dayspan {
            font-family: Roboto, sans-serif;
            width: 100%;
            height: 100%;
        }

        .v-toolbar--clipped{
            position: relative;
        }

        .v-dialog__content{
            position:absolute !important;
        }
        .v-navigation-drawer--fixed{
            z-index:5;
            position:absolute;
        }
        .ds-fullscreen{
              z-index:9999 !important;
        }
        .v-toolbar__content, .v-toolbar__extension{
            top: 0;
            position: sticky;
            width:100%;
            left: 0;
        }

        .v-content{
            position:relative;
            top: 0;
            left: 0;
        }

        .v-content__wrap{
            z-index: 3;
        }
        .v-menu__content{
            position:fixed;
        }
        .v-dialog__content{
            position:fixed !important;
        }
        .v-dialog{
            width:auto;
            border-radius: 15px;
            box-shadow: 3px -1px 43px 12px rgba(0,0,0,.5);
        }
        .application--wrap{
            height:100%;
            min-height:unset;
        }
</style>
<script>
import { Calendar, Weekday, Month } from 'dayspan';
import { db } from '../../app';
import moment from 'moment';
import {en, es} from 'vuejs-datepicker/dist/locale'
console.log(moment().format('l'))
Vue.$dayspan.setLocale('es');
localStorage.removeItem('dayspanState');

export default {
  name: 'dayspan',
  data: vm => ({
    storeKey: 'dayspanState',
    calendar: Calendar.months(),
    readOnly: false,
    isReadOnly:false,
    canSave: true,
    labels: {
        save:'Guardar'
    },
    currentLocale: vm.$dayspan.currentLocale,
    defaultEvents: [],
    today: moment().format('YYYY-MM-DD'), 
    events: [
        {
          title: 'Vacation',
          details: 'Going to the beach!',
          date: '2019-03-13',
          open: false
        }
      ]
  }),
  es: es,
  firebase: {
    calendarEvents: db.ref('user')
  },
  mounted()
  {
      window.app = this;
      window.app.refs = this.$refs.app;
      var userRef = db.ref('/user/' + this.user_id);
      let json = null;
      userRef.on('value', function(snapshot) {
          let data = snapshot.val()
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
        this.events.forEach(e => (map[e.date] = map[e.date] || []).push(e))
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
      this.$dayspan.setLocale(code);
      this.$dayspan.refreshTimes();
      //this.$refs.app.$forceUpdate();
    },
    saveState()
    {
      let state = this.calendar.toInput(true);
      let json = JSON.stringify(state);
      console.log(state)
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
        console.log(state.events,this.events)
        this.events = state.events
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
