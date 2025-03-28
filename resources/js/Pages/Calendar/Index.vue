<script setup>
import { ScheduleXCalendar } from '@schedule-x/vue'
import {
  createCalendar,
  createViewDay,
  createViewMonthAgenda,
  createViewMonthGrid,
  createViewWeek,
} from '@schedule-x/calendar'
import '@schedule-x/theme-default/dist/index.css'
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
 
// Do not use a ref here, as the calendar instance is not reactive, and doing so might cause issues
// For updating events, use the events service plugin

const page = usePage()
const user = computed(() => {
  return page.props.user
})

const todos = computed(() => {
  return user.value.todos
})

const syncEventsFromTodos = () => {
  let todoEvents = []

  todos.value.forEach((todo, index) => {
    let start = new Date(todo.start_date).toISOString().slice(0, 16).replace('T', ' ')
    let end = new Date(todo.end_date).toISOString().slice(0, 16).replace('T', ' ')

    let newTodoObj = {
        id: todo.id,
        title: todo.title,
        start: start,
        end: end
    }
    todoEvents.push(newTodoObj)
  })

  return todoEvents
}

const todoEvents = syncEventsFromTodos()
console.log(todoEvents)

const calendarApp = createCalendar({
  selectedDate: '2025-03-22',
  views: [
    createViewDay(),
    createViewWeek(),
    createViewMonthGrid(),
    createViewMonthAgenda(),
  ],
  events: todoEvents,
})
</script>
 
<template>
  <div>
    <ScheduleXCalendar :calendar-app="calendarApp" class="overflow-y-auto"/>
  </div>
</template>