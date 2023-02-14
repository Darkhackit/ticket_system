import {createRouter , createWebHistory} from "vue-router";

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
            }
        ]
    }

]

const router = createRouter({
    history:createWebHistory(),
    routes
})

export default router
