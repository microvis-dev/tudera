<script setup>
import jszip from 'jszip';
import {router} from "@inertiajs/vue3";
import pdfmake from 'pdfmake';
import DataTable from 'datatables.net-vue3'
import DataTablesCore from 'datatables.net-dt';
import 'datatables.net-buttons-dt';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-responsive-dt';
import 'datatables.net-select-dt';

DataTablesCore.Buttons.jszip(jszip);
DataTablesCore.Buttons.pdfMake(pdfmake);
DataTable.use(DataTablesCore);

import {ref} from "vue";
import {route} from "ziggy-js";
import WorkspaceSetting from './WorkspaceSetting.vue';

const activeView = ref('settings');

const props = defineProps({
    workspace: Object,
    url: String,
});

const columns = [
    {data: 'name'},
    {data: 'email'},
    {data: 'roles'},
]

const options = ref({
    processing: true,
    serverSide: true,
    ajax: {
        url: props.url,
        type: 'GET',
        data: function (params) {
            return {
                workspace_id: props.workspace.id,
                draw: params.draw,
                start: params.start,
                length: params.length,
                search: params.search.value,
                order: params.order.map(order => ({
                    column: params.columns[order.column].data,
                    dir: order.dir
                }))
            }
        },
        dataSrc: function(response) {
            return response.data || []
        }
    },
    select: {
        style: 'single',
        selector: 'td:first-child',
    },
    responsive: true,
    dom: 'Bfrtip',
    buttons: [
        {
            text: 'Invite',
            action: (e, dt, node, config) => {
                alert('invite');
            }
        },
        {
            text: 'Edit',
            action: (e, dt, node, config) => {
                const selectedRow = dt.rows({ selected: true }).data()[0];
                router.get(route('workspaces.user.show', {
                    id: props.workspace.id,
                    user: selectedRow.user_id
                }), {}, {
                    preserveState: true,
                    preserveScroll: true,
                });
            }
        },
        {
            text: 'Delete',
            action: (e, dt, node, config) => {
                const selectedRow = dt.rows({ selected: true }).data()[0];
                router.delete(route('workspaces.user.destroy', {
                    id: props.workspace.id,
                    user: selectedRow.user_id
                }), {}, {
                    preserveState: true,
                    preserveScroll: true,
                });
            }
        }
    ]
})
</script>

<template>
    <div class="mt-4">
        <div class="btn-group" role="group">
            <label class="flex items-center cursor-pointer">
                <input 
                    type="radio" 
                    name="activeViewRadio" 
                    value="settings" 
                    v-model="activeView" 
                    class="hidden" />
                <span 
                    class="px-4 py-2 rounded-lg"
                    :class="{ 'bg-blue-600 text-white': activeView === 'settings', 'bg-[#2B2C30]': activeView !== 'settings' }">
                    Workspace Settings
                </span>
            </label>
            <label class="flex items-center cursor-pointer ms-2 mb-3">
                <input 
                    type="radio" 
                    name="activeViewRadio" 
                    value="users" 
                    v-model="activeView" 
                    class="hidden" />
                <span 
                    class="px-4 py-2 rounded-lg"
                    :class="{ 'bg-blue-600 text-white': activeView === 'users', 'bg-[#2B2C30] ': activeView !== 'users' }">
                    Users
                </span>
            </label>
        </div>
    </div>

    <WorkspaceSetting v-if="activeView === 'settings'" />
    <DataTable v-else :columns="columns" :options="options" class="table display">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Roles</th>
        </tr>
        </thead>
    </DataTable>
</template>

<style scoped>
@import url('bootstrap');
@import url('datatables.net-bs5');
</style>
