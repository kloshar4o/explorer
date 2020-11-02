import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

const routesAuth = [
    {
        name: 'SignIn',
        path: '/sign-in',
        component: () =>
            import(/* webpackChunkName: "chunks/sign-in" */ './views/Auth/SignIn'),
        meta: {
            requiresAuth: false
        },
    },
]
const routesUser = [
    {
        name: 'Files',
        path: '/',
        component: () =>
            import(/* webpackChunkName: "chunks/files" */ './views/FilePages/Files'),
        meta: {
            requiresAuth: true
        },
    },
    {
        name: 'Trash',
        path: '/trash',
        component: () =>
            import(/* webpackChunkName: "chunks/trash" */ './views/FilePages/Trash'),
        meta: {
            requiresAuth: true
        },
    },
]

const router = new Router({
    mode: 'history',
    routes: [
        ...routesAuth,
        ...routesUser,
    ],
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return {x: 0, y: 0}
        }
    },
})

router.beforeEach((to, from, next) => {

    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.

        //if ( ! store.getters.isLogged) {
        if (false) {
            next({
                name: 'SignIn',
                query: {redirect: to.fullPath}
            })
        } else {
            next()
        }
    } else {
        next() // make sure to always call next()!
    }
})

export default router