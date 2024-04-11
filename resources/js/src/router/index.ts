import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';
import { useAppStore } from '@/stores/index';
import appSetting from '@/app-setting';

import HomeView from '../views/index.vue';
import Task from '../views/task.vue';
import Login from '../views/login.vue'
import Register from '../views/register.vue';
import TaskView from '../views/task-view.vue';
import TaskEdit from '../views/task-edit.vue';

const routes: RouteRecordRaw[] = [
    // dashboard
    { path: '/', name: 'home', component: HomeView },
    { path: '/task', name: 'task', component: Task,
        beforeEnter: (to, from, next) => {
            JSON.parse(localStorage.getItem('auth')) === null? next('login'): next();
        }
    },
    { path: '/login', name: 'login', component: Login },
    { path: '/register', name: 'register', component: Register },
    { path: '/task-view/:id', name: 'task-view', component: TaskView },
    { path: '/task-edit/:id', name: 'task-edit', component: TaskEdit },
];

const router = createRouter({
    history: createWebHistory(),
    linkExactActiveClass: 'active',
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        } else {
            return { left: 0, top: 0 };
        }
    },
});

router.beforeEach((to, from, next) => {
    const store = useAppStore();

    if (to?.meta?.layout == 'auth') {
        store.setMainLayout('auth');
    } else {
        store.setMainLayout('app');
    }
    next(true);
});
router.afterEach((to, from, next) => {
    appSetting.changeAnimation();
});
export default router;
