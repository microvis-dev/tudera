<script setup>
import { watch, computed } from 'vue';
import { ScheduleXCalendar } from '@schedule-x/vue';
import { createEventsServicePlugin } from '@schedule-x/events-service';
import { usePage } from '@inertiajs/vue3';
import {
  createCalendar,
  createViewDay,
  createViewMonthAgenda,
  createViewMonthGrid,
  createViewWeek,
} from '@schedule-x/calendar';
import '@schedule-x/theme-default/dist/index.css';
import useSelectedWorkspace from '../../Composable/useSelectedWorkspace';

const eventsServicePlugin = createEventsServicePlugin();

const props = defineProps({
  workspace_events: Array
});

const page = usePage();
const { selectedWorkspace } = useSelectedWorkspace();

const todos = computed(() => page.props.user.todos);

const workspaceEvents = computed(() => {
  return props.workspace_events.filter(event => event.workspace_id === selectedWorkspace.value.id);
});

const getEvents = () => {
  let calendarEvents = [];

  todos.value.forEach(todo => {
    let start = new Date(todo.start_date).toISOString().slice(0, 16).replace('T', ' ');
    let end = new Date(todo.end_date).toISOString().slice(0, 16).replace('T', ' ');

    calendarEvents.push({
      id: todo.id,
      title: todo.title,
      start: start,
      end: end,
      calendarId: 'personal'
    });
  });

  workspaceEvents.value.forEach(event => {
    let start = new Date(event.start_date).toISOString().slice(0, 16).replace('T', ' ');
    let end = new Date(event.end_date).toISOString().slice(0, 16).replace('T', ' ');

    calendarEvents.push({
      id: event.id,
      title: event.title,
      start: start,
      end: end,
      calendarId: 'work' // Fixed typo
    });
  });

  return calendarEvents;
};

const calendarEvents = computed(() => getEvents());

const calendarApp = createCalendar(
  {
    isDark: true,
    selectedDate: new Date().toISOString().split('T')[0],
    views: [createViewDay(), createViewWeek(), createViewMonthGrid(), createViewMonthAgenda()],
    calendars: {
      personal: {
        colorName: 'personal',
        lightColors: {
          main: '#f9d71c',
          container: '#fff5aa',
          onContainer: '#594800'
        },
        darkColors: {
          main: '#fff5c0',
          onContainer: '#fff5de',
          container: '#a29742'
        }
      },
      work: {
        colorName: 'work',
        lightColors: {
          main: '#f91c45',
          container: '#ffd2dc',
          onContainer: '#59000d'
        },
        darkColors: {
          main: '#ffc0cc',
          onContainer: '#ffdee6',
          container: '#a24258'
        }
      }
    }
  },
  [eventsServicePlugin]
);

// Add initial events
calendarEvents.value.forEach(event => {
  calendarApp.eventsService.add({
    id: event.id,
    title: event.title,
    start: event.start,
    end: event.end,
    calendarId: event.calendarId
  });
});

watch(calendarEvents, (newEvents) => {
  calendarApp.eventsService.getAll().forEach(event => {
    calendarApp.eventsService.remove(event.id);
  });

  newEvents.forEach(event => {
    calendarApp.eventsService.add({
      id: event.id,
      title: event.title,
      start: event.start,
      end: event.end,
      calendarId: event.calendarId
    });
  });
}, { deep: true });

</script>

<template>
  <div>
    <ScheduleXCalendar :calendar-app="calendarApp" />
  </div>
</template>

<style scoped>
.sx-vue-calendar-wrapper {
  width: 1200px;
  max-width: 100vw;
  height: 800px;
  max-height: 90vh;
}
</style>
