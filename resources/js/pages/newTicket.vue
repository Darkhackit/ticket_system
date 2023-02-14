<script setup>
import {onMounted, ref} from "vue";
import axios from "axios";


const form = ref({
    value:'',
    help_topic:'',
    category:'',
    branch:'',
    priority:'',
    email:'',
    title:''
})
const error = ref([])
const showMessage = ref(false)
const showCategory = ref(false)
const branches = ref([])
const help_topics = ref([])
const categories = ref([])
const priority = ref([
    'High',
    'medium',
    'low'
])

const getBranches = async () => {
    try {
        let response = await axios.get('/api/branch')
        branches.value = response.data
    }catch (e) {
        console.log(e.response)
    }
}
const getHelpTopics = async () => {
    try {
        let response = await axios.get('/api/get_topic')
        help_topics.value = response.data
    }catch (e) {
        console.log(e.response)
    }
}
const getCategory = async () => {
    try {
        let response = await axios.get('/api/category')
        categories.value = response.data
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
        await axios.post('/api/add-ticket',form.value)
    }catch (e) {
        console.log(e.response)
        error.value = e.response.data.errors
    }
}

onMounted(async () => {
    await getCategory()
    await getBranches()
    await getHelpTopics()
})

</script>
<template>
    <div class=" h-screen">
        <div class="flex flex-col md:grid grid-cols-2 lg:grid grid-cols-2   py-32 px-10">
            <div class=" w-full md:w-4/5 lg:w-4/5 ">
            <div class=" p-6 bg-white border border-gray-200 rounded-lg shadow-md sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
                <form  class="space-y-6" @submit.prevent="submitTicket">
                    <div class="flex justify-between">
                        <div>
<!--                            <router-link to="/">Back</router-link>-->
                        </div>
                        <div>
                            <h5 class="text-xl font-medium text-gray-900 dark:text-white">Open New Tickets</h5>
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Branch</label>
                        <v-select label="name" v-model="form.branch"  :options="branches"></v-select>
                        <p class="text-red-600" v-if="error.branch" >{{error.branch[0]}}</p>
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Help Topic</label>
                        <v-select :selected="getHelp(form.help_topic)" v-model="form.help_topic" label="name" :options="help_topics"></v-select>
                        <p class="text-red-600" v-if="error.help_topic" >{{error.help_topic[0]}}</p>
                    </div>
                    <div v-if="showCategory">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Category</label>
                        <v-select label="name" v-model="form.category" :options="categories"></v-select>
                        <p class="text-red-600" v-if="error.category" >{{error.category[0]}}</p>
                    </div>
                    <div>
                        <label for="priority" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Priority</label>
                        <v-select v-model="form.priority" :options="priority"></v-select>
                        <p class="text-red-600" v-if="error.priority" >{{error.priority[0]}}</p>
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                        <input type="email" v-model="form.email"  id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="username@primeinsuranceghana.com" >
                        <p class="text-red-600" v-if="error.email" >{{error.email[0]}}</p>
                    </div>
                    <div v-if="showMessage">
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                        <input type="text" v-model="form.title"  id="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        <p class="text-red-600" v-if="error.title" >{{error.title[0]}}</p>
                    </div>
                    <div v-if="showMessage">
                        <mavon-editor language="en" v-model="form.value"/>
                        <p class="text-red-600" v-if="error.value" >{{error.value[0]}}</p>
                    </div>

                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Open Ticket</button>
                </form>
            </div>

            </div>
            <div class=" w-full md:w-4/5 lg:w-4/5 py-5 md:py-0 ">
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-md sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
                    <form  class="space-y-6" action="">
                        <div class="flex justify-between">
                            <div>
                                <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="search issues" >
                            </div>
                            <div>
                                <h5 class="text-xl font-medium text-gray-900 dark:text-white">Related Issues</h5>
                            </div>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>
</template>

<style scoped>

</style>
