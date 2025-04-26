<script setup>
import { computed } from "vue";
import StatComponent from "../../Layout/Components/MainLayoutComponents/StatComponent.vue";
import MainComponent from "./Components/MainComponent.vue";
import TodoList from "./Components/TodoList.vue";
import Meetings from "./Components/Meetings.vue";
import { useTuderaStore } from "../../state/state";

const tuderaState = useTuderaStore()
const user = computed(() => tuderaState.getUser())
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
            <div class="h-fit w-full sm:w-1/2 p-3 md:p-5">
              <StatComponent :title="'New Projects'" :value="84" :previous-value="65" :color="'#63D4B7'" />
            </div>
            <div class="h-fit w-full sm:w-1/2 p-3 md:p-5">
              <StatComponent :title="'New Tasks'" :value="262" :previous-value="180" :color="'#4469DE'" />
            </div>
          </section>
        </section>
        <section class="w-full md:w-4/12">
          <div class="w-full h-auto md:h-1/2 overflow-y-auto">
            <TodoList />
          </div>
          <div class="w-full h-auto md:h-1/2 overflow-y-auto">
            <Meetings :profile-image='user.profile_image'/>
          </div>
        </section>
      </div>
    </section>
  </div>
</template>

<style scoped></style>
