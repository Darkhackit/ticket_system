<script setup>
import {ref} from "vue";
import {useRouter,RouterLink} from 'vue-router'
import axios from "axios";
import {useAuthStore} from "../../store/auth";

const router = useRouter()
const form = ref({
    email:'',
    password:''
})
const store = useAuthStore()
const processing = ref(false)
const errors = ref([])

const clearErrors = (val) => {

      delete errors.value[val]
}

const login = async () => {
    processing.value = true
    try {
       let response = await axios.post('/api/login',form.value)
        window.localStorage.setItem('ticket_auth',JSON.stringify(response.data.authorisation.token))
        window.localStorage.setItem('ticket_user',JSON.stringify(response.data.user))
        await router.push({name:'dashboard'})
    }catch (e) {
        processing.value = false
        errors.value = e.response.data.errors

    }
}


</script>
<template>
    <div class="w-full h-screen">
        <div class="flex justify-center items-center flex-row py-32">
    <div class="w-80 md:w-1/6 lg:w-1/3  bg-white border border-gray-200 rounded-lg shadow-md sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
        <form    class="space-y-6" @submit.prevent="login">
            <div class="flex justify-between">
                <div>
                    <router-link to="/">Back</router-link>
                </div>
                <div>
                    <h5 class="text-xl font-medium text-gray-900 dark:text-white">Sign in to our platform</h5>
                </div>
            </div>
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                <input type="email" @input="clearErrors('email')" :class="{'border-red-600':errors.email}" v-model="form.email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="name@company.com" >
                <p v-if="errors.email" class="text-red-600" v-text="errors.email[0]"></p>
            </div>
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your password</label>
                <input type="password" @input="clearErrors('password')" :class="{'border-red-600':errors.password}" v-model="form.password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" >
                <p v-if="errors.password" class="text-red-600" v-text="errors.password[0]"></p>
            </div>
            <button :disabled="processing" type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Login to your account</button>
        </form>
    </div>
        </div>
    </div>
</template>

<style scoped>

</style>
