
export default {
  promptLabels: {
    actionRemove:       '¿Estás seguro de que quieres eliminar este evento?',
    actionExclude:      '¿Está seguro de que desea eliminar este evento?',
    actionCancel:       '¿Estas seguro que quieres cancelar este evento?',
    actionUncancel:     '¿Estás seguro de que quieres cancelar este evento?',
    actionSetStart:     '¿Está seguro de que desea establecer esta aparición como la primera?',
    actionSetEnd:       '¿Está seguro de que desea establecer esta aparición como la última?',
    actionMove:         '¿Estás seguro de que quieres mover este evento?',
    actionInclude:      '¿Estás seguro de que quieres agregar un evento?',
    move:               '¿Estás seguro de que quieres mover este evento?',
    toggleAllDay:       '¿Está seguro de que desea cambiar si este evento ocurre todo el día?',
    removeExistingTime: '¿Está seguro de que desea eliminar todas las incidencias de eventos en este momento?'
  },
  placeholder: {
    noTitle: '(Sin título)'
  },
  patterns: {
    lastDay:        (dia) => 'Último día del mes',
    lastDayOfMonth: (dia) => 'Último día de ' + dia.format('MMMM'),
    lastWeekday:    (dia) => 'Último ' + dia.format('dddd') + ' en ' + dia.format('MMMM')
  },
  colors: [
    { text: 'Rojo' },
    { text: 'Rosado' },
    { text: 'Morado' },
    { text: 'Morado oscuro' },
    { text: 'Indigo' },
    { text: 'Azul' },
    { text: 'Glue' },
    { text: 'Azul claro' },
    { text: 'Cyan' },
    { text: 'Teal' },
    { text: 'Verde' },
    { text: 'Verde claro' },
    { text: 'Lima' },
    { text: 'Amarillo' },
    { text: 'Amber' },
    { text: 'Naranja' },
    { text: 'Naranja oscuro' },
    { text: 'Cafe' },
    { text: 'Gris azulado' },
    { text: 'Gris' },
    { text: 'Negro' }
  ],
  icons: [
    { text: 'Alarma' },
    { text: 'Estrella' },
    { text: 'Corazón' },
    { text: 'Acción' },
    { text: 'Asignación' },
    { text: 'Advertencia' },
    { text: 'Dinero' },
    { text: 'Cargar' },
    { text: 'Casa' },
    { text: 'Jugar' },
    { text: 'Correo' },
    { text: 'Telefono' },
    { text: 'Gráfico' },
    { text: 'Bicicleta' },
    { text: 'Viaje' }
  ],
  defaults: {
    dsDay: {
      formats: {
        month:  'MMM'
      }
    },
    dsCalendarApp: {
      types: [
        { label: 'Día' },
        { label: 'Semana' },
        { label: 'Mes' },
        { label: 'Año' },
        { label: 'Programar' },
        { label: '4 dias' }
      ],
      formats: {
        today: 'dddd, MMMM D',
        xs: 'MMM'
      },
      labels: {
        next: (type) => type ? 'Siguiente ' + type.label.toLowerCase() : 'Siguiente',
        prev: (type) => type ? 'Previo ' + type.label.toLowerCase() : 'Previo',
        moveCancel: 'Cancelar movimiento',
        moveSingleEvent: 'Mover evento',
        moveOccurrence: 'Mueve solo este evento',
        moveAll: 'Mover todas las ocurrencias de eventos',
        moveDuplicate: 'Añadir evento',
        promptConfirm: 'Si',
        promptCancel: 'No',
        today: 'Hoy'
      }
    },
    dsAgendaEvent: {
      formats: {
        firstLine:  'ddd',
        secondLine: 'MMM Do',
        start:      'dddd, MMMM D',
        time:       'h:mm a'
      },
      labels: {
        allDay:   'Todo el dia',
        options:  'Opciones',
        close:    'Cerrar',
        dia:      ['dia', 'dias'],
        dias:     ['dia', 'dias'],
        minuto:   ['minuto', 'minutos'],
        minutos:  ['minuto', 'minutos'],
        hora:     ['hora', 'horas'],
        horas:    ['hora', 'horas'],
        semana:     ['semana', 'semanas'],
        semanas:    ['semana', 'semanas'],
        segundo:   ['segundo', 'segundos'],
        segundos:  ['segundo', 'segundos'],
        busy:     'Ocupado',
        free:     'Libre'
      }
    },
    dsCalendarEventChip: {
      formats: {
        fullDay:          'ddd MMM Do YYYY',
        timed:            'ddd MMM Do YYYY'
      }
    },
    dsCalendarEventPopover: {
      formats: {
        start:    'dddd, MMMM D',
        time:     'h:mm a'
      },
      labels: {
        allDay:   'Todo el dia',
        options:  'Opciones',
        close:    'Cerrar',
        dia:      ['dia', 'dias'],
        dias:     ['dia', 'dias'],
        minuto:   ['minuto', 'minutos'],
        minutos:  ['minuto', 'minutos'],
        hora:     ['hora', 'horas'],
        horas:    ['hora', 'horas'],
        semana:     ['semana', 'semanas'],
        semanas:    ['semana', 'semanas'],
        segundo:   ['segundo', 'segundos'],
        segundos:  ['segundo', 'segundos'],
        busy:     'Ocupado',
        free:     'Libre'
      }
    },
    dsCalendarEventCreatePopover: {
      formats: {
        start:    'dddd, MMMM D',
        time:     'h:mm a'
      },
      labels: {
        title:    'Agregar titulo',
        allDay:   'Todo el dia',
        close:    'Cerrar',
        save:     'Guardar',
        dia:      ['dia', 'dias'],
        dias:     ['dia', 'dias'],
        minuto:   ['minuto', 'minutos'],
        minutos:  ['minuto', 'minutos'],
        hora:     ['hora', 'horas'],
        horas:    ['hora', 'horas'],
        semana:     ['semana', 'semanas'],
        semanas:    ['semana', 'semanas'],
        segundo:   ['segundo', 'segundos'],
        segundos:  ['segundo', 'segundos'],
        busy:     'Ocupado',
        free:     'Libre',
        location: 'Agregar una ubicación',
        description: 'Agregar descripción',
        calendar: 'Calendario',
      },
      busyOptions: [
        {text: 'Ocupado'},
        {text: 'Libre'}
      ]
    },
    dsSchedule: {
      labels: {
        editCustom:   'Editar'
      }
    },
    dsEvent: {
      labels: {
        moreActions:  'Mas acciones...',
        cancel:       'Cancelar cambios de evento',
        save:         'Guardar',
        title:        'Titulo',
          exclusions:   'Estos son eventos o períodos de tiempo en los que un evento que ocurre normalmente se excluyó de la programación. Los eventos se excluyen aquí si se mueve un evento.',
        inclusions:   'Estos son eventos o períodos de tiempo en los que los eventos se agregaron fuera de la programación normal. Los eventos se agregan aquí si se mueve un evento.',
        cancelled:    'Estos son eventos o tramos de tiempo donde los eventos fueron cancelados.',
        edit:         'Editar evento',
        add:          'Agrega evento',
        location:     'Agregar una ubicación',
        description:  'Agregar descripción',
        calendar:     'Calendario',
        tabs: {
          details:    'Detalles del evento',
          forecast:   'Pronóstico',
          removed:    'Removido',
          added:      'Agregado',
          cancelled:  'Cancelado'
        }
      },
      busyOptions: [
        {text: 'Ocupado'},
        {text: 'Libre'}
      ]
    },
    dsScheduleActions: {
      labels: {
        remove:     'Eliminar este evento',
        exclude:    'Eliminar esta ocurrencia',
        cancel:     'Cancelar esta ocurrencia',
        uncancel:   'Deshacer cancelación',
        move:       'Mueve esta ocurrencia',
        include:    'Añadir nueva ocurrencia',
        setStart:   'Establecer como primera aparición',
        setEnd:     'Establecer como última aparición',
        pickerOk:   'OK',
        pickerCancel:'Cancelar'
      }
    },
    dsScheduleForecast: {
      labels: {
        prefix:     'El pronóstico muestra anterior y siguiente.',
        suffix:     'eventos ocurridos dentro de un año de tiempo.'
      }
    },
    dsScheduleFrequencyDay: {
      labels: {
        type: 'Dias'
      },
      options: [
        { text: 'Cualquier día' },
        { text: 'En los días siguientes ...' },
        { text: 'Cada _ días comenzando en _' }
      ],
      types: [
        { text: 'Día del mes' },
        { text: 'Último día del mes' },
        { text: 'Dia del año' }
      ]
    },
    dsScheduleFrequencyDayOfWeek: {
      weekdays: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'],
      labels: {
        type: 'Días de la semana'
      },
      options: [
        { text: 'Cualquier dia de la semana' },
        { text: 'En los siguientes días de la semana ...' },
        { text: 'Cada _ día de la semana empezando en _' },
        { text: 'Fines de semana' },
        { text: 'Entre semana' }
      ]
    },
    dsScheduleFrequencyMonth: {
      labels: {
        type: 'Meses'
      },
      months: [
        'Enero',
        'Febrero',
        'Marzo',
        'Abril',
        'Mayo',
        'Junio',
        'Julio',
        'Agosto',
        'Septiembre',
        'Octubre',
        'Noviembre',
        'Diciembre'
      ],
      options: [
        { text: 'Cualquier mes' },
        { text: 'En los meses siguientes ...' },
        { text: 'Cada _ meses comenzando en _' }
      ]
    },

    dsScheduleFrequencyWeek: {
      labels: {
        type: 'Semanas'
      },
      options: [
        { text: 'Cualquier semana' },
        { text: 'En las siguientes semanas ...' },
        { text: 'Cada _ semanas empezando en _' }
      ],
      types: [
        { text: 'Semana del mes (la primera semana tiene jueves)' },
        { text: 'Período de la semana del mes (comienza el primer día del mes)' },
        { text: 'Semana completa del mes (0th = la semana anterior si la hubiera)' },
        { text: 'Último intervalo de la semana del mes (comienza el último día del mes)' },
        { text: 'Última semana completa del mes (0th = la semana después de haberla)' },
        { text: 'Semana del año (la primera semana tiene jueves)' },
        { text: 'Período de la semana del año (comienza el primer día del año)' },
        { text: 'Semana completa del año (0º = la semana anterior, si corresponde)' },
        { text: 'Último intervalo de la semana del año (comienza el último día del año)' },
        { text: 'Última semana completa del año (0th = la semana después de haberla)' }
      ]
    },

    dsScheduleFrequencyYear: {
      labels: {
        type: 'Años'
      },
      options: [
        { text: 'Cualquier año' },
        { text: 'En los años siguientes ...' },
        { text: 'Cada _ años comenzando en _' }
      ]
    },

    dsScheduleSpan: {
      labels: {
        startless:  'Principio de los tiempos',
        endless:    'Fin del tiempo'
      },
      formats: {
        start:      'MMMM Do, YYYY',
        end:        'MMMM Do, YYYY'
      }
    },

    dsScheduleTime: {
      labels: {
        remove:     'Quitar tiempo',
        add:        'Agregar tiempo'
      }
    },

    dsScheduleTimes: {
      labels: {
        all:        'Todo el dia',
        minuto:     'minuto',
        minutos:    'minutos',
        hora:       'hora',
        horas:      'horas',
        dia:        'día',
        dias:       'días',
        semana:     'semana',
        semanas:    'semanas',
        month:      'mes',
        months:     'meses',
        segundo:    'secundo',
        segundos:   'secundos'
      }
    },

    dsScheduleType: {
      formats: {
        date:       'LL'
      }
    },
 
    dsScheduleTypeCustomDialog: {
      labels: {
        save:     'Guardar',
        cancel:   'Cancelar'
      }
    },

    dsWeekDayHeader: {
      formats: {
        weekday:    'ddd'
      }
    },

    dsWeeksView: {
      weekdays: ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom']
    },

    dsDaysView: {
      horas: [
        '    ', '1am', '2am', '3am', '4am', '5am', '6am', '7am', '8am', '9am', '10am', '11am',
        '12pm', '1pm', '2pm', '3pm', '4pm', '5pm', '6pm', '7pm', '8pm', '9pm', '10pm', '11pm'
      ]
    },

    dsDayPicker: {
      weekdays: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'],
      labels: {
        prevMonth: 'Mes previo',
        nextMonth: 'Mes siguiente'
      }
    }
  }
}
