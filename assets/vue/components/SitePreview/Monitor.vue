<template>
  <div :class="componentClass">
    <span class="siteList__single__title">Monitor</span>
    <div :class="componentClass + '--up'"
         v-if="site.status == 'up'"
    >
      <i class="ion-md-checkmark-circle-outline"></i>
      <span>Site is up</span>
    </div>
    <div :class="componentClass + '--slow'"
         v-else-if="site.status == 'slow'"
    >
      <i class="ion-md-timer"></i>
      <span>Site is slow (+5s response time)</span>
    </div>
    <div :class="componentClass + '--down'"
         v-else
    >
      <i class="ion-md-alert"></i>
      <span>Site is down</span>
    </div>
    <div :class="componentClass + '__responseTime'"
         v-if="site.status != 'down'"
    >
      <span>
        Response time:
        {{ site.responseTimeLatest | prettyResponseTime }}s
      </span>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Monitor',

    props: [
      'cssClass',
      'site'
    ],

    computed: {
      componentClass() {
        return this.cssClass ? this.cssClass + '__monitor' : 'monitor';
      }
    },

    filters: {
      prettyResponseTime(responseTime) {
        const float = parseFloat(responseTime);
        return float.toFixed(2);
      }
    }
  }
</script>