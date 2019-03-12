<template>
  <div class="container">
    <div class="dashboardToolbar">
      <h1 class="colorTitle">Dashboard ({{ sites.length }})</h1>
      <div class="crawlSites"
           v-on:click="crawlSites"
           v-bind:class="{ active: loading }"
      >
        <i class="ion-md-refresh"></i>
      </div>
    </div>
    <SiteList :sites="sites"
              :loading="loading"
    />
  </div>
</template>

<script>
  import axios from 'axios';
  import SiteList from './SiteList';

  export default {
    name: 'dashboard',

    data() {
      return {
        sites: this.dataSites,
        loading: false
      }
    },

    props: [
      'dataSites'
    ],

    components: {
      SiteList
    },

    methods: {
      crawlSites() {
        if (this.loading) {
          return;
        }

        this.loading = true;
        axios.get('/sites/crawl')
          .then(res => {
            this.sites = res.data;
            this.loading = false;
          });
      }
    }
  }
</script>
