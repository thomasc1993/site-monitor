import Vue from 'vue';
import VueRouter from 'vue-router';
import Dashboard from '../views/Dashboard';

Vue.use(VueRouter);

export default new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      component: Dashboard
    },
    {
      path: '*',
      redirect: '/'
    }
  ]
})