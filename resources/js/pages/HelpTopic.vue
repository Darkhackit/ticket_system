<script setup>
import {AgGridVue} from 'ag-grid-vue3'
import {onMounted,ref} from "vue";
import {initModals,Modal} from 'flowbite'

import "ag-grid-community/styles/ag-grid.css"
import "ag-grid-community/styles/ag-theme-alpine.css"
import 'ag-grid-enterprise'
import axios from "axios";

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
       label:'Name',
       field:'name',
        filterOptions:{
            enabled: true,
        }
    },
    {
        label: 'Ticket Raised',
        field:'ticket_raised'
    },
    {
        label: 'Show Category',
        field:'show_category'
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

const add = async () => {
    try {
        let response = await axios.post('/api/add_topic',form.value)
        console.log(await response)
        document.getElementById('closeAdd').click()
        form.value.name = ""
        await get()
    }catch (e) {
        errors.value = e.response.data.errors
    }
}

const get = async () => {
    try {
        let response = await axios.get('/api/get_topic')
         rows.value = (await response.data)
    }catch (e) {
        console.log(e.response)
    }
}

const update = async () => {
    try {
        let response = await axios.patch(`/api/get_topic/${ed_form.value.id}`,ed_form.value)
        console.log(await response)
        closeEdit()
        await get()
    }catch (e) {
        errors.value = e.response.data.errors
    }
}
const showEdit = async (id) => {
    try {
        let response = await axios.get(`/api/get_topic/${id}`)
        console.log(response)
        ed_form.value.name = (await response.data.name)
        ed_form.value.show_category = (await response.data.show_category)
        ed_form.value.id = (await response.data.id)
        let el = document.getElementById('edit')
        let modal = new Modal(el)
        modal.show()
    }catch (e) {
        console.log(e.response)
    }

}
const closeEdit = () => {
    let el = document.getElementById('edit')
    let modal = new Modal(el)
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
    <div class="flex justify-between">
        <div>
            <button data-modal-target="authentication-modal" data-modal-toggle="authentication-modal" type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Add Help Topic</button>
        </div>
        <div></div>
    </div>
    <div data-modal-backdrop="static" id="authentication-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-full max-w-md md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" id="closeAdd" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="authentication-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="px-6 py-6 lg:px-8">
                    <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Add Help Topic</h3>
                    <form @submit.prevent="add" class="space-y-6">
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                            <input type="text" @input="clearErrors" :class="{'border-red-700':errors.name}" v-model="form.name" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Name" required>
                            <small v-if="errors.name" class="text-red-700">{{errors.name[0]}}</small>
                        </div>
                        <div class="flex items-center pl-4 border border-gray-200 rounded dark:border-gray-700">
                            <input  v-model="form.show_category" id="bordered-checkbox-2" type="checkbox" value="" name="bordered-checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="bordered-checkbox-2" class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Show Category</label>
                        </div>

                        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div data-modal-backdrop="static" id="edit" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-full max-w-md md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" @click="closeEdit" id="closeEdit" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="edit">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="px-6 py-6 lg:px-8">
                    <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Edit Help Topic</h3>
                    <form @submit.prevent="update" class="space-y-6">
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                            <input type="text" @input="clearErrors" :class="{'border-red-700':errors.name}" v-model="ed_form.name" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Name" required>
                            <small v-if="errors.name" class="text-red-700">{{errors.name[0]}}</small>
                        </div>
                        <div class="flex items-center pl-4 border border-gray-200 rounded dark:border-gray-700">
                            <input :checked="ed_form.show_category"  v-model="ed_form.show_category" id="bordered-checkbox-2" type="checkbox" value="" name="bordered-checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="bordered-checkbox-2" class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Show Category</label>
                        </div>

                        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update</button>
                    </form>
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
               <span @click.prevent="showEdit(props.row.id)" class="cursor-pointer">edit</span> |
               <span @click.prevent="deleteEdit(props.row.id)" class="cursor-pointer">delete</span>
           </div>
            <div v-if="props.column.field == 'show_category'">
                {{props.row.show_category ? 'true' : 'false'}}
            </div>
        </template>
    </vue-good-table>

</template>

<style scoped>

</style>
