import Vue from 'vue';
import Dashboard from './components/Dashboard';
import EditSite from './components/EditSite';
import Login from './components/Login';
import '../css/app.scss';

new Vue({
  el: '#app',
  components: {
    Dashboard,
    EditSite,
    Login
  }
});