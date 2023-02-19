import {createRouter , createWebHistory} from "vue-router";
import {useAuthStore} from "../store/auth";
import {computed} from "vue";
const routes = [
    {
        'path':'/',
        name:'home',
        component:() => import('../pages/newTicket.vue'),
    },
    // {
    //     'path':'/category',
    //     name:'category',
    //     component:() => import('../pages/Home.vue'),
    // },
    // {
    //     'path':'/new_ticket',
    //     name:'new_ticket',
    //     component:() => import('../pages/newTicket.vue'),
    // },
    {
        'path':'/auth/login',
        name:'login',
        component:() => import('../pages/Auth/Login.vue'),
        meta: { requiresAuth: true }
    },
    {
        'path':'/admin/',
        component:() => import('../Layout/AdminLayout.vue'),
        children:[
            {
                path:'',
                name:'dashboard',
                component:() => import('../pages/Home.vue')
            },
            {
                path:'/help-topics',
                name:'help-topics',
                component:() => import('../pages/HelpTopic.vue')
            },
            {
                path:'/branch',
                name:'branch',
                component:() => import('../pages/Branch.vue')
            },
            {
                path:'/category',
                name:'category',
                component:() => import('../pages/Category.vue')
            },
            {
                path:'/ticket',
                name:'ticket',
                component:() => import('../pages/Ticket.vue')
            }
        ]
    }

]

const router = createRouter({
    history:createWebHistory(),
    routes
})

router.beforeEach((to) => {
    const store = useAuthStore()
    const token = computed(() => store.authenticated)
    if(!token.value && to.name !== 'login' && to.name !== 'home') {
        return {name:'login'}
    }
    if (to.meta.requiresAuth && token.value) {
        return {name:'dashboard'}
    }
})

export default router
