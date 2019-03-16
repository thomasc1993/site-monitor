<template>
  <div :class="componentClass">
    <h2 :class="componentClass + '__name'">{{ site.name }}</h2>
    <a :href="site.url"
       :class="componentClass + '__link'"
       target="_blank"
    >
      <span>{{ site.url | prettyUrl }}</span>
      <i class="ion-md-open"></i>
    </a>
  </div>
</template>

<script>
  export default {
    name: 'SiteInfo',

    props: [
      'cssClass',
      'site'
    ],

    computed: {
      componentClass() {
        return this.cssClass ? this.cssClass + '__siteInfo' : 'siteInfo';
      }
    }
  }
</script>
