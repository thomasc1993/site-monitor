<template>
  <div class="container loginPage">
    <h1 class="loginPage__title">Login</h1>
    <FormResponse :is-loading="isLoading"
                  :success="loginSuccess"
    />
    <form method="post" class="loginForm" v-on:submit="submitForm" :action="this.formUrl">
      <input name="email" v-model="email" type="email" placeholder="Email" required>
      <input name="password" v-model="password" type="password" placeholder="Password" required>
      <button type="submit" v-bind:class="{ isValid: isValid }" :disabled="!isValid">
        <i class="ion-md-key"></i>
        <span>Login</span>
      </button>
      <div class="errorMessage" v-if="this.error">
        <i class="ion-md-alert"></i>
        <span>{{ this.error }}</span>
      </div>
    </form>
  </div>
</template>

<script>
  import axios from 'axios';
  import FormResponse from './FormResponse';

  export default {
    name: 'login',

    props: [
      'formUrl',
      'successUrl'
    ],

    components: {
      FormResponse
    },

    data() {
      return {
        email: null,
        password: null,
        error: null,
        loginSuccess: null,
        isLoading: false
      }
    },

    computed: {
      isValid() {
        return !!this.email && !!this.password;
      }
    },

    methods: {
      submitForm(e) {
        e.preventDefault();
        this.errorMessage = null;
        const formUrl = e.target.action;
        this.isLoading = true;
        axios.post(formUrl, {
          username: this.email,
          password: this.password
        }).then(res => {
          if (res.data.login_success) {
            this.isLoading = false;
            this.loginSuccess = 'Successfully logged in.';
            window.location.href = this.successUrl;
          }
        }).catch(error => {
          const jsonResponse = JSON.parse(error.request.response);
          this.isLoading = false;
          this.password = null;
          this.error = jsonResponse.error;
        });
      }
    }
  }
</script>
