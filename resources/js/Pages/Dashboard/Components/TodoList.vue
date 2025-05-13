<script setup>
import { ref, reactive, onUnmounted, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { getDate } from '@/resources/js/utils/utils';
import CreateToDoModal from '@/resources/js/Pages/Dashboard/Components/CreateToDoModal.vue';
import { useTuderaStore, useTuderaViewStore } from '@/resources/js/state/state';
import MeetingDropdown from './MeetingDropdown.vue';

const tuderaState = useTuderaStore()
const tuderaViewState = useTuderaViewStore()
const todos = computed(() => tuderaState.getTodos())

const viewState = reactive({
    addTodoModal: false
})

const showAddTodoModal = () => {
    tuderaViewState.changeAddTodoModal()
    viewState.addTodoModal = true
}

const hideAddTodoModal = () => {
    viewState.addTodoModal = false
}

const formatDate = (date) => {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    const hours = String(d.getHours() - 2).padStart(2, '0');
    const minutes = String(d.getMinutes()).padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}`;
}
const currentTime = ref(new Date());
const timer = setInterval(() => {
    currentTime.value = new Date();
}, 1000);

onUnmounted(() => {
    clearInterval(timer);
});

const lateToDate = (todo) => {
    const deadline = getDate(todo.end_date);
    return deadline <= currentTime.value;
}

const updateIsDone = (todo) => {
    setTimeout(() => {
        if (todo.is_done) {
            router.delete(route('todolist.destroy', todo.id));
        }
    }, 5000);
}

const deleteCalendarEvent = (event, i) => {
    router.delete(route('calendar.destroy', {
        calendar: event.id
    }));
}

const workspaceEvents = computed(() => tuderaState.getWorkspaceEvents())
const eventCheckboxes = ref(Array(workspaceEvents.value.length).fill(false))
viewState.viewType = 'todos';
</script>
<template>
    <section class="flex flex-col p-5">
        <h1 class="text-2xl roboto-font-bold mb-2">Events</h1>
        <p class="text-sm roboto-font-medium mb-5 text-[#B3B3B3]">Track your todos & events in a simple sidebar.</p>
        <fieldset>
            <div class="flex gap-4 mb-4 justify-center">
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="viewType" value="todos" v-model="viewState.viewType" class="hidden" />
                    <span class="px-4 py-2 rounded-lg"
                        :class="{ 'bg-blue-600 text-white': viewState.viewType === 'todos', 'bg-[#2B2C30]': viewState.viewType !== 'todos' }">
                        Todos
                    </span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="viewType" value="events" v-model="viewState.viewType" class="hidden" />
                    <span class="px-4 py-2 rounded-lg"
                        :class="{ 'bg-blue-600 text-white': viewState.viewType === 'events', 'bg-[#2B2C30]': viewState.viewType !== 'events' }">
                        Events
                    </span>
                </label>
            </div>
            <div v-if="viewState.viewType === 'todos'">
                <div v-for="(todo, index) in todos" class="flex flex-row py-2 w-full items-center gap-4">
                    <div class="w-1/12">
                        <div class="inline-flex items-center">
                            <label class="flex items-center cursor-pointer relative">
                                <input type="checkbox" v-model="todo.is_done" @click="updateIsDone(todo)"
                                    class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-slate-800 checked:border-slate-800"
                                    :id="`check-${index}`" />
                                <span
                                    class="absolute text-white opacity-0 peer-checked:opacity-100 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                        fill="currentColor" stroke="currentColor" stroke-width="1">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="w-6/12">
                        <h2 class="roboto-font-bold text-md" :class="{ 'text-gray-500 line-through': todo.is_done }">{{
                            todo.title }}</h2>
                    </div>
                    <div class="w-7/12 text-center rounded-xl p-1"
                        :class="{ 'text-gray-500 line-through': todo.is_done, 'bg-red-500': lateToDate(todo) }">
                        <p>{{ formatDate(todo.end_date) }}</p>
                    </div>
                </div>
                <div class="flex justify-center mt-5">
                    <button @click="showAddTodoModal" class="w-fit p-2 px-10 rounded-xl bg-blue-600">Add new
                        todo</button>
                </div>
            </div>
            <div v-if="viewState.viewType === 'events'">
                <div v-for="(event, index) in workspaceEvents"
                    class="flex flex-row py-2 w-full items-center justify-center">
                    <MeetingDropdown :name="event.title" :end-date="formatDate(event.end_date)"
                        @delete-calendar-event="deleteCalendarEvent(event, index)" />
                </div>
            </div>
        </fieldset>
        <CreateToDoModal v-if="viewState.addTodoModal" @exit="hideAddTodoModal" :is-personal="true" />
    </section>
</template>
<style scoped></style>
