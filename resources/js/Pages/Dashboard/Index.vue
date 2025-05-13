<script setup>
import { computed } from "vue";
import StatComponent from "../../Layout/Components/MainLayoutComponents/StatComponent.vue";
import MainComponent from "./Components/MainComponent.vue";
import TodoList from "./Components/TodoList.vue";
import Meetings from "./Components/Meetings.vue";
import { useTuderaStore } from "../../state/state";

const tuderaState = useTuderaStore()

const user = computed(() => tuderaState.getUser())
const selectedWorkspace = computed(() => tuderaState.getSelectedWorkspace())

const dateCheck = new Date();
dateCheck.setDate(dateCheck.getDate() - 30);

const protectedTable = computed(() => {
  return selectedWorkspace.value.tables
    .find((table) => table.protected)
})

const leadsColumn = computed(() => {
  return protectedTable.value.columns
    .find((column) => column.name == "Leads")
})

const currentLeadsValues = computed(() => {
  return leadsColumn.value.values
    .filter((value) => new Date(value.created_at) > dateCheck)
    .length
})

const leadsValueLastMonth = computed(() => {
  return leadsColumn.value.values
    .filter((value) => new Date(value.created_at) < dateCheck)
    .length
})

const completedTasks = computed(() => {
  const statusValues = protectedTable.value.columns
    .filter((column) => column.type == "status")
    .flatMap((column) => column.values)
    .filter((value) => value.value === "done")

  return statusValues
})

const currentCompletedTasks = computed(() => {
  return completedTasks.value
    .filter((value) => new Date(value.updated_at) > dateCheck)
    .length
})

const completedTasksLastMonth = computed(() => {
  return completedTasks.value
    .filter((value) => new Date(value.updated_at) < dateCheck)
    .length
})

</script>

<template>
  <div class="w-full min-h-screen flex flex-col md:flex-row overflow-auto md:overflow-hidden">
    <section class="w-full flex flex-col">
      <div class="flex flex-col md:flex-row h-full">
        <section class="w-full md:w-8/12">
          <div class="w-full h-auto md:h-4/6">
            <MainComponent />
          </div>
          <section class="flex flex-col sm:flex-row">
            <div class="h-fit w-full sm:w-1/2 p-3 md:pb-5 md:mb-5">
              <StatComponent :title="'New Leads'" :value="currentLeadsValues" :previous-value="leadsValueLastMonth"
                :color="'#63D4B7'" />
            </div>
            <div class="h-fit w-full sm:w-1/2 p-3 md:pb-5 md:mb-5">
              <StatComponent :title="'Completed Tasks'" :value="currentCompletedTasks" :previous-value="completedTasksLastMonth" :color="'#4469DE'" />
            </div>
          </section>
        </section>
        <section class="w-full md:w-4/12">
          <div class="w-full h-auto md:h-1/2 overflow-y-auto">
            <TodoList />
          </div>
          <div class="w-full h-auto md:h-1/2 overflow-y-auto">
            <Meetings :profile-image='user.profile_image' />
          </div>
        </section>
      </div>
    </section>
  </div>
</template>

<style scoped></style>
