<script setup>
import { useTuderaStore } from '@/resources/js/state/state';
import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { formatTimeAgo } from '@/resources/js/utils/utils';

const tuderaState = useTuderaStore()
const selectedWorkspace = computed(() => tuderaState.getSelectedWorkspace())

const dropdownOpen = ref(false);

const openDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value
}

const searchValue = ref("")

const results = computed(() => {
  if (searchValue.value.length < 3) return []

  const check = (name, target = searchValue.value) => {
    return name.toLowerCase().includes(target.toLowerCase())
  }

  const tables = selectedWorkspace.value.tables

  const tableResults = tables.map((table) => {
    if (check(table.name)) {
      let route = {
        title: table.name,
        url: {
          name: "table.show",
          params: {
            table: table.id
          }
        }
      }

      return route
    }
  })

  const columnResults = []
  const valueResults = []
  tables.forEach((table) => {
    table.columns.map((column) => {
      if (check(column.name)) {
        let route = {
          title: table.name + "/" + column.name,
          url: {
            name: "table.show",
            params: {
              table: column.table_id
            }
          }
        }

        columnResults.push(route)
      }

      const values = column.values
      values.forEach((value) => {
        if (check(value.value)) {
          let valueRoute = {
            title: table.name + "/" + column.name + "/" + value.value,
            url: {
              name: "table.show",
              params: {
                table: column.table_id
              }
            }
          }

          valueResults.push(valueRoute)
        }
      })
    })
  })

  const eventResults = selectedWorkspace.value.calendar_events
    .map((event) => {
      if (check(event.title)) {
        let eventRoute = {
          title: "Calendar/" + event.title,
          url: {
            name: "calendar.index",
            params: null
          }
        }

        return eventRoute
      }
    })

  return [...tableResults, ...columnResults, ...valueResults, ...eventResults].filter(Boolean)
})

const redirect = (result) => {
  searchValue.value = ""

  router.get(route(result.url.name, result.url.params))
}

const notifications = computed(() => tuderaState.getNotifications())

const deleteNotification = (notification) => {
  router.delete(route('notification.destroy', {
    notification: notification.id
  }))
}
</script>
<template>
  <section class="flex flex-row justify-between h-fit px-5 py-2">
    <div class="relative">
      <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
        <img src="../../../../assets/search.svg" class="w-5 h-5">
      </div>
      <input type="text" v-model="searchValue"
        class="pl-10 pr-3 py-2 w-96 bg-[#1C1D21] border border-gray-600 rounded-lg roboto-font-regular focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 text-gray-500 text-sm"
        placeholder="Search">
    </div>
    <div v-if="results.length > 0"
      class="absolute z-10 mt-10 w-96 bg-[#2B2C30] border border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto">
      <ul class="py-1">
        <li v-for="(result, index) in results" :key="index" @click="redirect(result)"
          class="px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 cursor-pointer">
          {{ result?.title }}
        </li>
      </ul>
    </div>
    <div @click="openDropdown">
      <div class="flex flex-row items-center bg-[#2B2C30] rounded-lg px-2 bell">
        <img src="../../../../assets/bell.svg" class="w-10 h-10">
        <p class="ms-2 text-lg">{{ notifications.length }}</p>
      </div>
      <!-- Notifications -->
      <transition name="slide-down">
        <div v-if="dropdownOpen" ref="dropdownRef"
          class="absolute right-36 min-w-[250px] min-h-[250px] flex flex-col items-center bg-[#2B2C30] text-white rounded-lg shadow-lg mt-2 me-2 z-10">
          <div class="flex justify-between w-full p-2 items-center">
            <h1 class="text-lg roboto-font-regular">Notifications</h1>
          </div>
          <div class="flex flex-col items-center">
            <div v-for="(notification, i) in notifications"
              class="flex flex-col w-full justify-center rounded-[5px] items">
              <div class="flex flex-row p-2 justify-between items-start">
                <div class="px-2">
                  <img src="../../../../assets/bell.svg" class="w-7 h-7">
                </div>
                <div class="flex flex-col px-2">
                  <p class="text-sm" v-html="notification.value"></p>
                </div>
                <div>
                  <button @click="deleteNotification(notification)">delete</button>
                </div>
                <p class="text-xs roboto-font-light text-[#B3B3B3]">{{ formatTimeAgo(notification.updated_at) }}</p>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </section>
</template>
<style scoped>
.bell img {
  width: 2rem;
  transform-origin: 50% 0;

  &:hover,
  &:focus {
    animation: bell-swing 1s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
  }
}

@keyframes bell-swing {
  0% {
    transform: rotate(0);
  }

  15% {
    transform: rotate(15deg);
  }

  30% {
    transform: rotate(-13deg);
  }

  45% {
    transform: rotate(10deg);
  }

  60% {
    transform: rotate(-7deg);
  }

  75% {
    transform: rotate(5deg);
  }

  85% {
    transform: rotate(-2deg);
  }

  100% {
    transform: rotate(0);
  }
}

.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.2s ease;
  transform-origin: top;
}

.slide-down-enter-from,
.slide-down-leave-to {
  transform: translateY(-10px);
  opacity: 0;
}

.slide-down-enter-to,
.slide-down-leave-from {
  transform: translateY(0);
  opacity: 1;
}

/* Focus state for accessibility */
button:focus::before {
  content: "";
  position: absolute;
  top: 5px;
  left: -10px;
  width: 5px;
  height: 80%;
  background-color: #2F81F7;
  border-radius: 5px;
  opacity: 1;
}

.items:hover> :not(:hover) {
  transition: 500ms;
  filter: blur(1px);
  transform: scale(0.95, 0.95);
}
</style>
