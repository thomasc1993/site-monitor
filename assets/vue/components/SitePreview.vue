<template>
  <div :class="componentCssClass">
    <SiteInfo :cssClass="componentCssClass" :site="site" />
    <Monitor :cssClass="componentCssClass" :site="site" />
    <Cms :cssClass="componentCssClass" :site="site" />
    <Actions :cssClass="componentCssClass" :site="site" />
  </div>
</template>

<script>
  import Actions from './Site/Actions';
  import Cms from './Site/Cms';
  import Monitor from './Site/Monitor';
  import SiteInfo from './Site/SiteInfo';

  export default {
    name: 'SitePreview',

    props: [
      'cssClass',
      'site'
    ],

    components: {
      Actions,
      Cms,
      Monitor,
      SiteInfo
    },

    computed: {
      componentCssClass() {
        return this.cssClass ? this.cssClass + '__single' : 'single';
      }
    }
  }
</script>
