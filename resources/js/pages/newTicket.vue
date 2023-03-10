<script setup>
import {onMounted, ref} from "vue";
import axios from "axios";
import { useNotification } from "@kyvg/vue3-notification";

const notification = useNotification()

const form = ref({
    value:'',
    help_topic:'',
    category:'',
    branch:'',
    priority:'',
    email:'',
    title:''
})
const processing = ref(false)
const error = ref([])
const showMessage = ref(false)
const showCategory = ref(false)
const branches = ref([])
const ticket_number = ref("")
const help_topics = ref([])
const categories = ref([])
const errors = ref([])
const priority = ref([
    'High',
    'medium',
    'low'
])

const modalState = ref(false);

const toggleModal = ()=>{
    (modalState.value)?modalState.value=false:modalState.value=true;
}

const getBranches = async () => {
    try {
        processing.value = true
        let response = await axios.get('/api/branch')
        branches.value = response.data
        processing.value = false
    }catch (e) {
        processing.value = false
        console.log(e.response)
    }
}
const clearErrors = (val) => {

    delete errors.value[val]
}
const getHelpTopics = async () => {
    try {
        processing.value = true
        let response = await axios.get('/api/get_topic')
        help_topics.value = response.data
        processing.value = false
    }catch (e) {
        console.log(e.response)
        processing.value = false
    }
}
const getCategory = async () => {
    try {
        processing.value = true
        let response = await axios.get('/api/category')
        categories.value = response.data
        processing.value = false
    }catch (e) {
        console.log(e.response)
    }
}

const getHelp = (val) => {
    if(val.show_category === 1){
        showCategory.value = true
        showMessage.value = true
    }
    if (val.show_category === 0){
        showCategory.value = false
        showMessage.value = true
    }
}
const submitTicket = async () => {
    try {
        processing.value = true
        await axios.post('/api/add-ticket',form.value)
        processing.value = false
        notification.notify({
            type:'success',
            title: "Request Sent Successfully",
            text: "Ticket Opened Successfully",
        });
        form.value.value = ""
        form.value.email = ""
        form.value.category = ""
        form.value.title = ""
        form.value.help_topic = ""
        form.value.priority = ""
        form.value.branch = ""
    }catch (e) {
        error.value = e.response.data.errors
        processing.value = false
    }
}

const checkStatus = async () => {
    try {
        let response = await axios.post(`/api/check_ticket`,{ticket_number:ticket_number.value})
        console.log(response)
        if(response.data.status === 'pending') {
            notification.notify({
                title: "Processing",
                text: "Please your request is been worked on, please check again later",
            });
        }
        if(response.data.status === 'resolved') {
            notification.notify({
                type:'success',
                title: "Resolved",
                text: "Please your request has been resolved",
            });
        }
        if(response.data.status === 'rejected') {
            notification.notify({
                type:'warn',
                title: "Rejected",
                text: "Please your request has been rejected , please check your mail for more details",
            });
        }
    }catch (e) {
        errors.value = e.response.data.errors
        processing.value = false
    }
}

onMounted(async () => {
    await getCategory()
    await getBranches()
    await getHelpTopics()
})

</script>
<template>
    <div class="flex flex-row w-full justify-center py-20">
        <div class="w-4/5 flex flex-col md:flex-row border bg-white rounded-md py-10">
            <div class=" w-full md:w-4/5 lg:w-4/5 px-10">
            <div class="  bg-white  sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
                <form  class="space-y-6" @submit.prevent="submitTicket">
                    <div class="flex justify-between">
                        <div class="space-y-2 mb-3">
                            <h5 class="text-2xl font-medium text-gray-900 dark:text-white font-bold">Open A New Ticket</h5>
                            <p class="text-sm text-gray-500">Report a issue Or Make a general enquiry</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Branch</label>
                        <v-select label="name" v-model="form.branch"  :options="branches"></v-select>
                        <p class="text-red-600" v-if="error.branch" >{{error.branch[0]}}</p>
                    </div>
                    <div class="space-y-3">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Help Topic</label>
                        <v-select :selected="getHelp(form.help_topic)" v-model="form.help_topic" label="name" :options="help_topics"></v-select>
                        <p class="text-red-600 text-sm" v-if="error.help_topic" >{{error.help_topic[0]}}</p>
                    </div>
                    <div v-if="showCategory" class="space-y-3">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Category</label>
                        <v-select label="name" v-model="form.category" :options="categories"></v-select>
                        <p class="text-red-600 text-sm" v-if="error.category" >{{error.category[0]}}</p>
                    </div>
                    <div class="space-y-3">
                        <label for="priority" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Priority</label>
                        <v-select v-model="form.priority" class="capitalize" :options="priority"></v-select>
                        <p class="text-red-600 text-sm" v-if="error.priority" >{{error.priority[0]}}</p>
                    </div>
                    <div class="space-y-3">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                        <input type="email" v-model="form.email"  id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="username@primeinsuranceghana.com" >
                        <p class="text-red-600 text-sm" v-if="error.email" >{{error.email[0]}}</p>
                    </div>
                    <div v-if="showMessage" class="space-y-2">
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                        <input type="text" v-model="form.title"  id="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        <p class="text-red-600 text-sm" v-if="error.title" >{{error.title[0]}}</p>
                    </div>
                    <div v-if="showMessage" class="space-y-2">
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Message</label>
                        <quill-editor contentType="html" v-model:content="form.value" theme="snow"></quill-editor>
                        <p class="text-red-600 text-sm" v-if="error.value" >{{error.value[0]}}</p>
                    </div>

                    <button :class="{'cursor-not-allowed':processing}" :disabled="processing" type="submit" class="w-full text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Open Ticket</button>
                    <button type="button" @click="toggleModal()" class="md:hidden w-full text-white bg-slate-500 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Ticket Status</button>
                </form>
            </div>

            </div>
            <div class=" w-full block md:block md:w-2/5 px-5 py-5 md:py-0 border-l " :class="{'hidden':!modalState,'h-screen bg-white absolute top-0 left-0 md:static md:h-full':modalState}">
                <div class="p-6 bg-white sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
                    <form  class="space-y-6" action="">
                        <div class="flex justify-end"><button v-if="modalState" type="button" class="bg-red-600 text-white inline p-1 rounded-md text-sm" @click="toggleModal()">Close</button></div>
                        <div class="flex md:justify-end ">

                            <div class="space-y-3 mb-10">
                                <h5 class="text-2xl font-medium text-gray-900 dark:text-white font-bold md:text-right">Check Ticket Status</h5>
                                <p class="text-sm text-gray-500 md:text-right">Track the status of you ticket</p>
                            </div>
                        </div>

                    </form>
                    <div class="mb-4">
                        <label for="number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ticket Number</label>
                        <input type="number" v-model="ticket_number" @input="clearErrors('ticket_number')" :class="{'border-red-600':errors.ticket_number}" :readonly="processing" id="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Ticket Number (Exclude the # sign)" >
                        <p class="text-red-600" v-if="errors.ticket_number" >{{errors.ticket_number[0]}}</p>
                    </div>
                    <button @click.prevent="checkStatus" :class="{'cursor-not-allowed':processing}" :disabled="processing" type="submit" class="w-full text-white  bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Check Status</button>
                </div>


            </div>

        </div>
    </div>
</template>

<style scoped>

</style>
