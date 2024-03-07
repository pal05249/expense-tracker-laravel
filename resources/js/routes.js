import VueRouter from 'vue-router';
import Index from './Index'
import charts from './pages/charts.vue';
import dashboard from './pages/ExampleComponent'

const routes=[
 
    { path:"/dashboard",component: dashboard,name:"dashboard"},
    {path:"/transactions",component:charts,name:"charts"}
       
]

const router = new VueRouter({
    routes,
    mode:"history",
});

export default router;