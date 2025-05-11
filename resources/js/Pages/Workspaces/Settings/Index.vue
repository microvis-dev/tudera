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
        selector: 'td:first-child'
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
    <DataTable :columns="columns" :options="options" class="table display">
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
@import url('../../../../../node_modules/datatables.net-dt');
@import url('../../../../../node_modules/datatables.net-buttons-dt');
@import url('../../../../../node_modules/datatables.net-responsive-dt');
@import url('../../../../../node_modules/datatables.net-select-dt');
</style>
