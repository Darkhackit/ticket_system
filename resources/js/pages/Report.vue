<script setup>
import {computed, onMounted, ref} from "vue";
import {initModals} from 'flowbite'
import Papa from 'papaparse'
import {useAuthStore} from '../store/auth'
import axios from "axios";
import {jsPDF} from 'jspdf'
import "jspdf-autotable"

const store = useAuthStore()
const token = computed(() => store.authenticated)
const pending = ref(0)
const resolved = ref(0)
const rejected = ref(0)
const total = ref(0)

const form = ref({
    s:'',
    e:'',
    model:'branch'
})
const errors = ref([])
const columns = ref([
    {   label: 'ID',
        field:'id'
    },
    {
        label:'Ticket Number',
        field:'ticket_number',

    },
    {
        label: 'Email Address',
        field:'email'
    },
    {
        label: 'Title',
        field:'title'
    },
    {
        label: 'Priority',
        field:'priority'
    },
    {
        label: 'Status',
        field:'status'
    },
    {
        label: 'Resolved By',
        field:'resolved_by'
    },
    {
        label: 'Action',
        field:'action'
    }
])
const rows = ref([])

const column = [
    {title: "ID", dataKey: "id"},
    {title: "TICKET NUMBER", dataKey: "ticket_number"},
    {title: "Email", dataKey: "email"},
    {title: "TITLE", dataKey: "title"},
    {title: "PRIORITY", dataKey: "priority"},
    {title: "STATUS", dataKey: "status"},
    {title: "RESOLVED BY", dataKey: "resolved_by"},
];
const exportPdf = () => {
    const pdf = new jsPDF("p","pt","A4")
    pdf.text(`${form.value.model.toUpperCase()} REPORT FROM ${form.value.s ?? new Date().toDateString()} TO ${form.value.e ?? new Date().toDateString() }`,30,20)
    pdf.autoTable(column,rows.value)
    pdf.save()
}

const get = async () => {
    try {
        let response = await axios.post('/api/report',form.value)
        rows.value = (await response.data.models)
        pending.value = (await response.data.pending)
        total.value = (await response.data.total)
        rejected.value = (await response.data.rejected)
        resolved.value = (await response.data.resolved)
    }catch (e) {
        console.log(e.response)
    }
}
const exportCsv = () => {
    const csv = Papa.unparse(rows.value);
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', 'table.csv');
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

onMounted(async () => {
    initModals();
    await get()
})
</script>

<template>
    <div class="grid grid-cols-4 justify-center pb-4 gap-x-2">
        <div>
            <a href="#" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-blue-900 dark:text-blue-500">{{ total }}</h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">Total Tickets </p>
            </a>
        </div>
        <div>
            <a href="#" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ resolved }}</h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">Resolved Tickets </p>
            </a>
        </div>
        <div>
            <a href="#" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ rejected }}</h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">Rejected Tickets</p>
            </a>
        </div>
        <div>
            <a href="#" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ pending }}</h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">Pending Tickets</p>
            </a>
        </div>
    </div>
    <div class="flex">
            <div class="m-2">
                <button @click.prevent="exportPdf"  type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Export to PDF</button>
            </div>
        <div class="m-2">
            <button @click.prevent="exportCsv"  type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Export to CSV</button>
        </div>

    </div>
    <div class="grid grid-cols-4 gap-x-2">
        <div>
            <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Model</label>
            <select v-model="form.model" id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="branch">Branch</option>
                <option value="category">Category</option>
                <option value="help_topic">Help Topic</option>
            </select>
        </div>
        <div>
            <label for="email"  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">From</label>
            <input type="date" v-model="form.s" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="name@company.com" >
        </div>
        <div class="mb-4">
            <label for="email"  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">To</label>
            <input v-model="form.e" type="date" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="name@company.com" >
        </div>
        <div>
            <label for="email"  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">.</label>
            <button @click.prevent="get" type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
        </div>
    </div>
    <vue-good-table
        :columns="columns"
        :rows="rows"
        :group-options="{
            enabled: true,
            collapsable: true
          }"

    >
        <template #table-header-row="props">
            <span class="my-fancy-class">
              {{ props.row.label }}
            </span>
        </template>
        <template #table-row="props">
            <div v-if="props.column.field == 'action'">

            </div>
            <div v-if="props.column.field == 'status'">
                <span v-if="props.row.status === 'pending'" class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{props.row.status}}</span>
                <span  v-if="props.row.status === 'resolved'" class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ props.row.status }}</span>
                <span  v-if="props.row.status === 'rejected'" class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-green-300">{{ props.row.status }}</span>
            </div>
            <div v-if="props.column.field == 'ticket_number'">
                <span  class="cursor-pointer text-blue-600 underline">#{{props.row.ticket_number}}</span>
            </div>
        </template>
    </vue-good-table>

</template>

<style scoped>

</style>
