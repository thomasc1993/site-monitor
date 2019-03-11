<template>
  <div class="siteList">
    <transition name="fade">
      <div v-if="loading">
        <Loader></Loader>
        <div class="loadingCloak"></div>
      </div>
    </transition>
    <SitePreview :site="site"
                 v-for="site in sites"
                 :key="site.id"
                 cssClass="siteList"
    />
  </div>
</template>

<script>
  import Loader from './Loader';
  import SitePreview from './SitePreview';

  export default {
    name: 'SiteList',

    props: [
      'sites',
      'loading'
    ],

    components: {
      Loader,
      SitePreview
    }
  }
</script>