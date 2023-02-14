<script setup>
import {ref} from "vue";

const value = ref("")

</script>
<template>
    <div class=" h-screen">
        <div class="flex flex-col md:grid grid-cols-2 lg:grid grid-cols-2   py-32 px-10">
            <div class=" w-full md:w-4/5 lg:w-4/5 ">
            <div class=" p-6 bg-white border border-gray-200 rounded-lg shadow-md sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
                <form  class="space-y-6" action="">
                    <div class="flex justify-between">
                        <div>
<!--                            <router-link to="/">Back</router-link>-->
                        </div>
                        <div>
                            <h5 class="text-xl font-medium text-gray-900 dark:text-white">Open New Tickets</h5>
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Department</label>
                        <v-select :options="['Canada', 'United States']"></v-select>
                        <p class="text-red-600" ></p>
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Help Topic</label>
                        <v-select :options="['Canada', 'United States']"></v-select>
                        <p class="text-red-600" ></p>
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Category</label>
                        <v-select :options="['Canada', 'United States']"></v-select>
                        <p class="text-red-600" ></p>
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                        <input type="email"  id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="username@primeinsuranceghana.com" >
                        <p class="text-red-600"></p>
                    </div>
                    <div>
                        <mavon-editor language="en" v-model="value"/>
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
