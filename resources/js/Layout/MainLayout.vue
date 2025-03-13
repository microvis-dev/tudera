<script setup>
import { usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import Select from '../Components/ui/select/Select.vue';
import SelectContent from '../Components/ui/select/SelectContent.vue';
import SelectGroup from '../Components/ui/select/SelectGroup.vue';
import SelectItem from '../Components/ui/select/SelectItem.vue';
import SelectItemText from '../Components/ui/select/SelectItemText.vue';
import SelectLabel from '../Components/ui/select/SelectLabel.vue';
import SelectScrollDownButton from '../Components/ui/select/SelectScrollDownButton.vue';
import SelectScrollUpButton from '../Components/ui/select/SelectScrollUpButton.vue';
import SelectSeparator from '../Components/ui/select/SelectSeparator.vue';
import SelectTrigger from '../Components/ui/select/SelectTrigger.vue';
import SelectValue from '../Components/ui/select/SelectValue.vue';

const page = usePage()

const flashSucess = computed(() => {
  return page.props.flash.success
})

const flashErrors = computed(() => {
  return page.props.flash.error
})

const user = computed(() => {
  return page.props.user
})

const user_workspaces = computed(() => {
  return null
})

</script>

<template>
  <!--
  <div v-if="flashSucess" class="flash-success p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
    {{ flashSucess }}
  </div>
  <div v-if="flashErrors" class="flash-error p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
    {{ flashErrors }}
  </div>
  <div v-if="user" class="p-4 bg-white rounded shadow-md">
    <div class="mb-4">
      <p class="text-lg font-semibold">{{ user.name }}</p>
      <p class="text-gray-600">{{ user.email }}</p>
      <p class="text-gray-600">{{ user.phone_number }}</p>
      <p class="text-gray-600">{{ user.id }}</p>
    </div>
    <div>
      <Link :href="route('auth.destroy', user.id)" method="delete" as="button"
        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
      Logout
      </Link>
      <br><br>
      <Link :href="route('setup.workspace.create')" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
      Create your first workspace (setup)
      </Link>
      <br><br>
      <Link :href="route('workspaces.index')" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
        Workspaces
      </Link>
    </div>
  </div>
  <div v-if="user_workspaces">
    <span>Workspaces</span>

  </div>
  <slot></slot>
-->

  <div class="container w-full h-full">
    <section class="p-8 w-80 h-screen border border-[#2B2C30]">
      <aside class="flex flex-col items-center">
        <div class="w-60 mb-20">
          <img src="../../assets/tuderaLogoWhite.svg" alt="Tudera Logo">
        </div>
        <Select>
          <SelectTrigger class="w-[180px]">
            <SelectValue placeholder="Workspace 1" />
          </SelectTrigger>
          <SelectContent>
            <SelectGroup>
              <SelectLabel>Workspaces</SelectLabel>
              <SelectItem value="apple">
                XD
              </SelectItem>
            </SelectGroup>
          </SelectContent>
        </Select>
        <div class="flex flex-col">
          <div class="flex flex-row">
            <p>Logo</p>
            <h2>Dashboard</h2>
          </div>
        </div>
      </aside>
    </section>
  </div>
</template>

<style scoped></style>