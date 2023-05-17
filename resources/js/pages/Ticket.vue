<script setup>
import {computed, onMounted, ref} from "vue";
import {initModals,Modal} from 'flowbite'
import {useAuthStore} from '../store/auth'
import axios from "axios";
import { useNotification } from "@kyvg/vue3-notification";

const notification = useNotification()
const store = useAuthStore()
const token = computed(() => store.authenticated);
const user = computed(() => store.user)
const pending = ref(0)
const resolved = ref(0)
const rejected = ref(0)
const total = ref(0)
const form = ref({
    name:'',
    show_category:false,
})
const ed_form = ref({
    name:'',
    show_category:false,
    id:''
})
const priorities = ref([
    'pending',
    'resolved',
    'rejected'
])
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

const ticket = ref({
    ticket_number : 0,
    email : "",
    branch: "",
    url:'',
    help_topic:"",
    category:"",
    priority:"",
    title:"",
    message:"",
    status:'',
    reply:'',
    user_id:user.value.id

})

const clearErrors = () => {
    errors.value = []
}

const get = async () => {
    try {
        let response = await axios.get('/api/get-ticket')
        rows.value = (await response.data.tickets)
        pending.value = (await response.data.pending)
        total.value = (await response.data.total)
        rejected.value = (await response.data.rejected)
        resolved.value = (await response.data.resolved)
    }catch (e) {
        console.log(e.response)
    }
}
const showEdit = async (id) => {
    try {
        let response = await axios.get(`/api/get-ticket/${id}`)
        console.log(response)
        ticket.value.title = (await response.data.title)
        ticket.value.ticket_number = (await response.data.ticket_number)
        ticket.value.email = (await response.data.email)
        ticket.value.branch = (await response.data.branch)
        ticket.value.url = (await response.data.file)
        ticket.value.help_topic = (await response.data.help_topic)
        ticket.value.category = (await response.data.category)
        ticket.value.priority = (await response.data.priority)
        ticket.value.message = (await response.data.message)
        ticket.value.status = (await response.data.status)
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
const replyTicket = async () => {
    try {
        let response = await axios.post('/api/reply_ticket',ticket.value)
        notification.notify({
            title: "Success",
            text: "Ticket Replied",
        });
        closeEdit()
    }catch (e) {
        alert(e.response)
    }
}
const closeEdit = () => {
    let el = document.getElementById('cl')
    el.click()
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
    <div class="grid grid-cols-4 justify-center pb-4 gap-x-2">
        <div id="cl">
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
    <div id="large-modal"  tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-full max-w-7xl md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                        #{{ticket.ticket_number}}
                    </h3>
                    <div>
                        <select id="large" v-model="ticket.status" class="block w-full px-4 py-3 text-base text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option v-for="priority in priorities" :value="priority" >{{priority}}</option>
                        </select>
                    </div>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6 ">

                    <form>
                        <div class="grid gap-6 mb-6 md:grid-cols-3">
                            <div>
                                <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Branch</label>
                                <input type="text" readonly v-model="ticket.branch"  id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="John" required>
                            </div>
                            <div>
                                <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Help Topic</label>
                                <input readonly type="text" id="last_name" v-model="ticket.help_topic" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Doe" required>
                            </div>
                            <div>
                                <label for="company" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Priority</label>
                                <input type="text" v-model="ticket.priority" id="company" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Flowbite" required>
                            </div>
                            <div>
                                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                                <input type="tel" readonly v-model="ticket.category" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="123-45-678" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" required>
                            </div>
                            <div>
                                <label for="website" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                                <input type="email" v-model="ticket.email" id="website" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="flowbite.com" required>
                            </div>
                        </div>

                        <div class="text-center">
                            <h1 class="mb-4 text-xl font-bold leading-none tracking-tight text-gray-900 md:text-2xl lg:text-4xl underline dark:text-white">{{ticket.title}}</h1>
                            <p class="mb-6 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48 dark:text-gray-400" v-html="ticket.message"></p>
                            <div v-if="ticket.url">
                                <h1>Attached Files</h1>
                                <a :href="ticket.url" target="_blank">File</a>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Reply</label>
                            <quill-editor contentType="html" v-model:content="ticket.reply" theme="snow"></quill-editor>
                            <p class="text-red-600 text-sm"  ></p>
                        </div>

                    </form>

                </div>
                <!-- Modal footer -->
                <div class="flex justify-end p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button @click.prevent="replyTicket" data-modal-hide="extralarge-modal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Reply</button>
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
                <span  v-if="props.row.status === 'rejected'" class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-green-300">{{ props.row.status }}</span>
            </div>
            <div v-if="props.column.field == 'ticket_number'">
                <span @click.prevent="showEdit(props.row.id)" class="cursor-pointer text-blue-600 underline">#{{props.row.ticket_number}}</span>
            </div>
        </template>
    </vue-good-table>

</template>

<style scoped>

</style>
