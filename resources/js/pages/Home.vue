<script setup>
import {ref,onMounted} from "vue";
import axios from "axios";

const branch = ref(0)
const ticket = ref(0)
const help_topics = ref(0)
const category = ref(0)
const form = ref({
    model:'',
    e:'',
    s:''
})
const options = ref( {
    chart: {
        id: 'vuechart-example'
    },
    xaxis: {
        categories: []
    }
})

const series = ref([{
    name: 'series-1',
    data: []
}])

const get_branches_report = async () => {
    try {
        let response =  await axios.post('/api/get_branches_report',form.value)
        branch.value = response.data.branch
        ticket.value = response.data.ticket
        help_topics.value = response.data.help_topic
        category.value = response.data.category
        options.value = {
            xaxis: {
                categories: response.data.branch_names
            }
        }
        series.value = [
            {
                name:"Prime Tickets",
                data: response.data.count
            }
        ]
        console.log(response)
    }catch (e) {
        console.log(e.response)
    }
}

onMounted(async () => {
    await get_branches_report()
})
</script>
<template>
<div>
    <div class="grid grid-cols-4 gap-2 mb-10">
        <div class="">
            <a href="#" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-blue-900 dark:text-blue-500">{{ ticket }}</h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">Total Tickets </p>
            </a>
        </div>
        <div class="">
            <a href="#" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-blue-900 dark:text-blue-500">{{ branch }}</h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">Total Branches </p>
            </a>
        </div>
        <div class="">
            <a href="#" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-blue-900 dark:text-blue-500">{{ help_topics }}</h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">Total Help Topics </p>
            </a>
        </div>
        <div class="">
            <a href="#" class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-blue-900 dark:text-blue-500">{{ category }}</h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">Total Categories </p>
            </a>
        </div>
    </div>
    <div class="grid grid-cols-3  mb-4 rounded bg-gray-50 dark:bg-gray-800">
        <div class="col-span-2">
            <apexchart width="900" type="bar" :options="options" :series="series"></apexchart>
        </div>
       <div>
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
           <button @click.prevent="get_branches_report" type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>

       </div>
    </div>

</div>
</template>

<style scoped>

</style>
