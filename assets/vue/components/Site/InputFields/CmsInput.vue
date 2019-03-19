<template>
  <select :name="this.id"
          :id="this.id"
          v-model="currentCms"
          v-on:change="onChange"
  >
    <option value="joomla">Joomla!</option>
    <option value="magento">Magento</option>
    <option :value="null">None</option>
    <option value="wordpress">WordPress</option>
  </select>
</template>

<script>
  export default {
    name: 'CmsInput',

    props: [
      'id',
      'cms'
    ],

    data() {
      return {
        currentCms: this.cms
      }
    },

    methods: {
      onChange(event) {
        this.$emit('update', event.target.value);
      }
    }
  }
</script>