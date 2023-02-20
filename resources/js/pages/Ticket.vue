<script setup>
import {computed, onMounted, ref} from "vue";
import {initModals,Modal} from 'flowbite'
import {useAuthStore} from '../store/auth'
import axios from "axios";

const store = useAuthStore()
const token = computed(() => store.authenticated)
const form = ref({
    name:'',
    show_category:false,
})
const ed_form = ref({
    name:'',
    show_category:false,
    id:''
})
const errors = ref([])
const columns = ref([
    {   label: 'ID',
        field:'id'
    },
    {
        label:'Ticket Number',
        field:'ticket_number',
        filterOptions:{
            enabled: true,
        }
    },
    {
        label: 'Email Address',
        field:'email'
    },
    {
        label: 'Branch',
        field:'branch'
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
        label: 'Action',
        field:'action'
    }
])
const rows = ref([])

const clearErrors = () => {
    errors.value = []
}

const get = async () => {
    try {
        let response = await axios.get('/api/get-ticket')
        rows.value = (await response.data)
    }catch (e) {
        console.log(e.response)
    }
}
const showEdit = async (id) => {
    try {
        let response = await axios.get(`/api/get_ticket/${id}`)
        console.log(response)
        ed_form.value.name = (await response.data.name)
        ed_form.value.show_category = (await response.data.show_category)
        ed_form.value.id = (await response.data.id)
        let el = document.getElementById('large-modal')
        let modal = new Modal(el,{
            // backdrop: 'static',
            backdropClasses: 'bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-40',
            closable: true,
        })
        modal.show()
    }catch (e) {
        console.log(e.response)
    }

}
const closeEdit = () => {
    let el = document.getElementById('large-modal')
    let modal = new Modal(el,{
        backdrop: 'static',
        backdropClasses: 'bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-40',
        closable: false,
    })
    modal.hide()
}
const deleteEdit = async (params) => {
    if (window.confirm("Are you sure")) {
        try {
            await axios.delete(`/api/get_topic/${params}`)
            await get()
        }catch (e) {
            errors.value = e.response.data.errors
        }
    }
}

onMounted(async () => {
    initModals();
    await get()
})
</script>

<template>
    <div class="flex justify-center pb-4 gap-x-2">
        <div>
            <a href="#" class="block w-56 p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-blue-900 dark:text-blue-500">Pending Tickets</h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">30 </p>
            </a>
        </div>
        <div>
            <a href="#" class="block w-64 p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Resolved Tickets</h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">Here </p>
            </a>
        </div>
        <div>
            <a href="#" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Rejected Tickets</h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">Here are the biggest enterprise technology acquisitions of 2021 so far, in reverse chronological order.</p>
            </a>
        </div>
        <div>
            <a href="#" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Noteworthy</h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">Here are the biggest enterprise technology acquisitions of 2021 so far, in reverse chronological order.</p>
            </a>
        </div>
    </div>
    <div id="large-modal"  tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-full max-w-7xl md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                        Extra Large modal
                    </h3>
                    <div>
                        <select id="large" class="block w-full px-4 py-3 text-base text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option selected>Choose a country</option>
                            <option value="US">United States</option>
                            <option value="CA">Canada</option>
                            <option value="FR">France</option>
                            <option value="DE">Germany</option>
                        </select>                    </div>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">

                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="extralarge-modal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">I accept</button>
                    <button data-modal-hide="extralarge-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Decline</button>
                </div>
            </div>
        </div>
    </div>
    <vue-good-table
        :columns="columns"
        :rows="rows"
        :pagination-options="{
            enabled: true
        }"
    >
        <template #table-row="props">
            <div v-if="props.column.field == 'action'">
                <span @click.prevent="showEdit(props.row.id)" class="cursor-pointer">view</span> |
                <span @click.prevent="deleteEdit(props.row.id)" class="cursor-pointer">delete</span>
            </div>
            <div v-if="props.column.field == 'status'">
                <span v-if="props.row.status === 'pending'" class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{props.row.status}}</span>
                <span  v-if="props.row.status === 'resolved'" class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ props.row.status }}</span>
                <span  v-if="props.row.status === 'rejected'" class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ props.row.status }}</span>
            </div>
            <div v-if="props.column.field == 'ticket_number'">
                <span @click.prevent="showEdit(props.row.id)" class="cursor-pointer text-blue-600 underline">#{{props.row.ticket_number}}</span>
            </div>
        </template>
    </vue-good-table>

</template>

<style scoped>

</style>
