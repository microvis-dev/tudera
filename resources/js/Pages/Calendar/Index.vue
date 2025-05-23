<script setup>
import { watch, computed } from 'vue';
import { ScheduleXCalendar } from '@schedule-x/vue';
import { createEventsServicePlugin } from '@schedule-x/events-service';
import { createDragAndDropPlugin } from '@schedule-x/drag-and-drop'
import { createEventModalPlugin } from '@schedule-x/event-modal'
import { createCurrentTimePlugin } from '@schedule-x/current-time'
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { getDate, getWorkspaceEventDate } from '../../utils/utils';
import {
  createCalendar,
  createViewDay,
  createViewMonthAgenda,
  createViewMonthGrid,
  createViewWeek,
} from '@schedule-x/calendar';
import '@schedule-x/theme-default/dist/index.css';
import { useTuderaStore } from '../../state/state';
import CreateToDoModal from '../Dashboard/Components/CreateToDoModal.vue';

const eventsServicePlugin = createEventsServicePlugin();

const props = defineProps({
  workspace_events: Array
});

const tuderaState = useTuderaStore()
const selectedWorkspace = computed(() => tuderaState.getSelectedWorkspace())

const todos = computed(() => tuderaState.getTodos());

const workspaceEvents = computed(() => {
  return props.workspace_events.filter(event => event.workspace_id === selectedWorkspace.value.id);
});

const getEvents = () => {
  let calendarEvents = [];

  todos.value.forEach(todo => {
    let start = getDate(todo.start_date).toISOString().slice(0, 16).replace('T', ' ');
    let end = getDate(todo.end_date).toISOString().slice(0, 16).replace('T', ' ');

    calendarEvents.push({
      id: todo.id,
      title: todo.title,
      start: start,
      end: end,
      calendarId: 'personal'
    });
  });

  workspaceEvents.value.forEach(event => {
    let start = getWorkspaceEventDate(event.start_date).toISOString().slice(0, 16).replace('T', ' ');
    let end = getWorkspaceEventDate(event.end_date).toISOString().slice(0, 16).replace('T', ' ');

    calendarEvents.push({
      id: event.id,
      title: event.title,
      start: start,
      end: end,
      calendarId: 'work'
    });
  });

  return calendarEvents;
};

const calendarEvents = computed(() => getEvents());
const eventModal = createEventModalPlugin()

const calendarApp = createCalendar(
  {
    isDark: true,
    selectedDate: new Date().toISOString().split('T')[0],
    views: [createViewDay(), createViewWeek(), createViewMonthGrid(), createViewMonthAgenda(), eventModal],
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
    },
    callbacks: {
      onEventUpdate(updatedEvent) {
        if (updatedEvent.calendarId == "work") {
          const calendarForm = useForm({
            id: updatedEvent.id,
            title: updatedEvent.title,
            start_date: updatedEvent.start,
            end_date: updatedEvent.end,
          })

          calendarForm.put(route("calendar.update", {
            calendar: updatedEvent.id,
            workspace: selectedWorkspace.value.id
          }))
        } else if (updatedEvent.calendarId == "personal") {
          const todoForm = useForm({
            id: updatedEvent.id,
            title: updatedEvent.title,
            is_done: false,
            start_date: updatedEvent.start,
            end_date: updatedEvent.end
          })

          todoForm.put(route("todolist.update", {
            todolist: updatedEvent.id,
          }))
        }
      }
    }
  },
  [eventsServicePlugin, createDragAndDropPlugin(), eventModal, createCurrentTimePlugin()]
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
    <ScheduleXCalendar :calendar-app="calendarApp" class="p-3" />
    <CreateToDoModal v-if="tuderaState.getModal().value" @exit="tuderaState.changeModal()" :is-personal="false"/>
  </div>
</template>

<style scoped>
.sx-vue-calendar-wrapper {
  width: 100%;
  height: auto;
  min-height: 60vh;
  max-height: 90vh;
  overflow: auto;
}

@media (max-width: 768px) {
  .sx-vue-calendar-wrapper {
    min-height: 80vh;
  }
}
</style>