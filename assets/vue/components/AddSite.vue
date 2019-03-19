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
          <CmsInput id="cms"
                    :cms="cms"
                    v-on:update="this.updateCms"
          />
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
  import CmsInput from './Site/InputFields/CmsInput';

  export default {
    name: 'AddSite',

    props: [
      'formUrl',
      'csrfToken',
      'redirectUrl'
    ],

    components: {
      FormResponse,
      CmsInput
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
      updateCms(cms) {
        this.cms = cms;
      },
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
            this.success = 'Site successfully created.';
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
