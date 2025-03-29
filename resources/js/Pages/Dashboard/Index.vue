<script setup>
import StatComponent from "../../Layout/Components/MainLayoutComponents/StatComponent.vue";
import MainComponent from "./Components/MainComponent.vue";
import TodoList from "./Components/TodoList.vue";
import Meetings from "./Components/Meetings.vue";

</script>

<template>
  <div class="w-screen h-screen flex flex-row overflow-hidden">
    <section class="w-10/12 flex flex-col">
      <div class="flex flex-row h-full">
        <section class="w-8/12">
          <div class="w-full h-4/6">
            <MainComponent />
          </div>
          <section class="flex flex-row">
            <div class="h-fit w-1/2 p-5">
              <StatComponent :title="'New Projects'" :value="84" :previous-value="65" :color="'#63D4B7'" />
            </div>
            <div class="h-fit w-1/2 p-5">
              <StatComponent :title="'New Tasks'" :value="262" :previous-value="180" :color="'#4469DE'" />
            </div>
          </section>
        </section>
        <section class="w-4/12">
          <div class="w-full h-1/2 overflow-y-auto">
            <TodoList />
          </div>
          <div class="w-full h-1/2 overflow-y-auto">
            <Meetings />
          </div>
        </section>
      </div>
    </section>
  </div>
</template>

<style scoped></style>
