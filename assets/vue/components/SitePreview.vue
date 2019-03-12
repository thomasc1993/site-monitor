<template>
  <div :class="componentCssClass">
    <SiteInfo :cssClass="componentCssClass" :site="site" />
    <Monitor :cssClass="componentCssClass" :site="site" />
    <Cms :cssClass="componentCssClass" :site="site" />
    <Actions :cssClass="componentCssClass" :site="site" />
  </div>
</template>

<script>
  import Actions from './SitePreview/Actions';
  import Cms from './SitePreview/Cms';
  import Monitor from './SitePreview/Monitor';
  import SiteInfo from './SitePreview/SiteInfo';

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
