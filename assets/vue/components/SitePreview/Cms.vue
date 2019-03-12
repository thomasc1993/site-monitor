<template>
  <div :class="componentClass">
    <span class="siteList__single__title">CMS</span>
    <div v-if="!site.cms">
      <i class="ion-md-help"></i>
      <span>Unknown</span>
    </div>
    <div v-else-if="site.cms == 'wordpress'">
      <i class="ion-logo-wordpress"></i>
      <span>WordPress</span>
    </div>
    <div v-else>
      <i class="ion-md-cube"></i>
      <span v-if="site.cms == 'joomla'">Joomla!</span>
      <span v-if="site.cms == 'magento'">Magento</span>
    </div>
    <a :class="componentClass + 'admin'"
       :href="site.url + site.adminUrl"
       target="_blank"
       v-if="site.cms"
    >
      <i class="ion-md-lock"></i>
      <span>Access admin panel</span>
    </a>
  </div>
</template>

<script>
  export default {
    name: 'Cms',

    props: [
      'cssClass',
      'site'
    ],

    computed: {
      componentClass() {
        return this.cssClass ? this.cssClass + '__cms' : 'cms';
      }
    }
  }
</script>
