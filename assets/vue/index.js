import Vue from 'vue';
import store from './store';

// Components
import AddSite from './components/AddSite';
import Dashboard from './components/Dashboard';
import EditSite from './components/EditSite';
import Login from './components/Login';

// Filters
import './filters/PrettyUrlFilter';

// Styles
import '../css/app.scss';

new Vue({
  el: '#app',

  components: {
    AddSite,
    Dashboard,
    EditSite,
    Login
  },

  store
});
