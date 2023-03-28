<script setup>
import {RouterView} from 'vue-router'

</script>
<template>
    <section>
        <router-view />
        <notifications position="top right" duration="10000" :closeOnClick="true" />
    </section>
</template>

<style scoped>

</style>
