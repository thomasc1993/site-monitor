<template>
  <div class="container">
    <div class="dashboardToolbar">
      <h1 class="colorTitle">Dashboard ({{ sites.length }})</h1>
      <div class="crawlSites" v-on:click="crawlSites" v-bind:class="{ active: loading }">
        <i class="ion-md-refresh"></i>
      </div>
    </div>
    <div class="siteList">
      <transition name="fade">
        <div v-if="loading">
          <Loader></Loader>
          <div class="loadingCloak"></div>
        </div>
      </transition>
      <div class="siteList__single" v-for="site in sites">
        <div class="siteList__single__siteInfo">
          <h2 class="siteList__single__siteInfo__name">{{ site.name }}</h2>
          <a :href="site.url" class="siteList__single__siteInfo__link" target="_blank">
            {{ prettyUrl(site.url) }}
            <i class="ion-md-open"></i>
          </a>
        </div>
        <div class="siteList__single__monitor">
          <span class="siteList__single__title">Monitor</span>
          <div class="siteList__single__monitor__isUp" v-if="site.status == 'up'">
            <i class="ion-md-checkmark-circle-outline"></i>
            <span>Site is up</span>
          </div>
          <div class="siteList__single__monitor__isSlow" v-else-if="site.status == 'slow'">
            <i class="ion-md-timer"></i>
            <span>Site is slow (+5s response time)</span>
          </div>
          <div class="siteList__single__monitor__isDown" v-else>
            <i class="ion-md-alert"></i>
            <span>Site is down</span>
          </div>
          <div class="siteList__single__monitor__responseTime" v-if="site.status != 'down'">
            <span>
              Response time:
              {{ prettyResponseTime(site.responseTimeLatest) }}s
            </span>
          </div>
        </div>
        <div class="siteList__single__cms">
          <span class="siteList__single__title">CMS</span>
          <div v-if="site.cms == 'wordpress'">
            <i class="ion-logo-wordpress"></i>
            <span>WordPress</span>
          </div>
          <div v-else-if="site.cms == 'joomla'">
            <i class="ion-md-cube"></i>
            <span>Joomla!</span>
          </div>
          <div v-else-if="site.cms == 'magento'">
            <i class="ion-md-cube"></i>
            <span>Magento</span>
          </div>
          <div v-else>
            <i class="ion-md-help"></i>
            <span>Unknown</span>
          </div>
          <a class="siteList__single__cms__admin"
             :href="site.url + site.adminUrl"
             target="_blank"
             v-if="site.cms"
          >
            <i class="ion-md-lock"></i>
            <span>Access admin panel</span>
          </a>
        </div>
        <div class="siteList__single__actions">
          <span class="siteList__single__title">Actions</span>
          <a :href="`site/${site.id}`">
            <i class="ion-md-create"></i>
            Edit
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios';
  import Loader from './Loader';

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
      Loader
    },

    methods: {
      prettyUrl(url) {
        return url.substr(url.indexOf('//') + 2);
      },
      prettyResponseTime(responseTime) {
        const float = parseFloat(responseTime);
        return float.toFixed(2);
      },
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