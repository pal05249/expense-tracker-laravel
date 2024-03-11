
import { createWebHistory, createRouter } from "vue-router";
import Transactions from './pages/Transactions.vue';
import Dashboard from './pages/Dashboard.vue'
import UserProfile from './pages/UserProfile.vue'
const routes = [

    { path: "/dashboard", component: Dashboard, name: "Dashboard" },
    { path: "/transactions", component: Transactions, name: "Transactions" },
    { path: "/profile", component: UserProfile, name: "UserProfile", props: true }

]

// const router = new VueRouter({
//     routes,
//     mode:"history",
// });
const router = createRouter({
    history: createWebHistory(),
    routes,
});


export default router;