<template>
  <div class="container">
    <div class="addSite">
      <FormResponse :is-loading="isLoading"
                    :success="success"
                    :error="error"
      />
      <form method="post"
            v-on:submit="submitForm"
            :action="this.formUrl"
      >
        <input type="text"
               name="name"
               class="addSite__name"
               v-model="name"
               placeholder="Site name"
               required
        >
        <div class="inputRow">
          <label for="url">URL</label>
          <input id="url"
                 name="url"
                 type="text"
                 v-model="url"
                 required
          >
        </div>
        <div class="inputRow">
          <label for="cms">CMS</label>
          <input id="cms"
                 name="cms"
                 type="text"
                 v-model="cms"
                 required
          >
        </div>
        <div class="inputRow">
          <label for="admin-url">Admin URL</label>
          <input id="admin-url"
                 name="admin-url"
                 type="text"
                 v-model="adminUrl"
                 required
          >
        </div>
        <input type="hidden"
               name="token"
               :value="csrfToken"
        >
        <button type="submit">
          <i class="ion-md-save"></i>
          <span>Create</span>
        </button>
      </form>
    </div>
  </div>
</template>

<script>
  import axios from 'axios';
  import FormResponse from './FormResponse';

  export default {
    name: 'AddSite',

    props: [
      'formUrl',
      'csrfToken',
      'redirectUrl'
    ],

    components: {
      FormResponse
    },

    data() {
      return {
        name: null,
        url: null,
        cms: null,
        adminUrl: null,
        isLoading: false,
        error: null,
        success: null
      }
    },

    methods: {
      submitForm(e) {
        e.preventDefault();
        this.error = null;
        const formUrl = e.target.action;
        this.isLoading = true;
        axios.post(formUrl, {
          name: this.name,
          url: this.url,
          cms: this.cms,
          admin_url: this.adminUrl,
          token: this.csrfToken
        }).then(res => {
          if (res.data.response) {
            this.isLoading = false;
            this.success = 'Site successfully added.';
            window.location.href = this.redirectUrl;
          }
        }).catch(error => {
          this.isLoading = false;
          if (error.response.data.error) {
            this.error = error.response.data.error;
          } else {
            this.error = error;
          }
        });
      }
    }
  }
</script>
