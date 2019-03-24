<template>
  <div>
    <span class="siteList__single__title">Actions</span>
    <a :href="editUrl">
      <i class="ion-md-create"></i>
      <span>Edit</span>
    </a>
  </div>
</template>

<script>
  export default {
    name: 'Actions',

    props: [
      'cssClass',
      'site'
    ],

    computed: {
      componentClass() {
        return this.cssClass ? this.cssClass + '__actions' : 'actions';
      },

      editUrl() {
        const url = this.$store.getters['route/path']('edit_site');
        return url.replace('{slug}', this.site.slug);
      }
    }
  }
</script>
