import {defineStore} from "pinia/dist/pinia";
import {computed, ref} from "vue";

export const useAuthStore = defineStore('auth',() => {
    const authenticated = computed(() => JSON.parse(window.localStorage.getItem('ticket_auth')))
    const user =  computed(() => JSON.parse(window.localStorage.getItem('ticket_user')))


    return {authenticated , user}
})


