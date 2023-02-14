<script setup>
import SideBar from '../component/SideBar.vue'
import Header from '../component/Header.vue'
import {onMounted} from "vue";
import {initModals,initDrawers,initDropdowns} from 'flowbite'


onMounted(() => {
    initModals();
    initDrawers();
    initDropdowns();

})
</script>
<template>
    <div>
        <Header />
        <side-bar/>
        <main>
            <div class="p-4 sm:ml-56">
                <div class="p-4   rounded-lg  mt-14">
                    <router-view />
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>

</style>
