<script setup>
import {RouterView} from 'vue-router'

</script>
<template>
<router-view />
    <notifications position="top right" duration="10000" :closeOnClick="true" />
</template>

<style scoped>

</style>
