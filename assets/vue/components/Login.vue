<template>
  <div class="container loginPage">
    <h1 class="loginPage__title">Login</h1>
    <transition name="fade">
      <div class="loadingCloak" v-if="isLoading || loginSuccess ">
        <div class="loadingCloak__content">
          <Loader v-if="!loginSuccess" />
          <transition name="popIn">
            <div class="success" v-if="this.loginSuccess">
              <i class="ion-md-checkmark-circle-outline"></i>
            </div>
          </transition>
        </div>
      </div>
    </transition>
    <form method="post" class="loginForm" v-on:submit="submitForm" :action="this.formUrl">
      <input name="email" v-model="email" type="email" placeholder="Email" required>
      <input name="password" v-model="password" type="password" placeholder="Password" required>
      <button type="submit" v-bind:class="{ isValid: isValid }" :disabled="!isValid">
        <i class="ion-md-key"></i>
        <span>Login</span>
      </button>
      <div class="errorMessage" v-if="this.errorMessage">
        <i class="ion-md-alert"></i>
        <span>{{ this.errorMessage }}</span>
      </div>
    </form>
  </div>
</template>

<script>
  import axios from 'axios';
  import Loader from './Loader';

  export default {
    name: 'login',

    props: [
      'formUrl',
      'successUrl'
    ],

    components: {
      Loader
    },

    data() {
      return {
        email: null,
        password: null,
        errorMessage: null,
        loginSuccess: false,
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
            this.loginSuccess = true;
            window.location.href = this.successUrl;
          }
        }).catch(error => {
          this.isLoading = false;
          this.password = null;
          if (error.response.data.error) {
            this.errorMessage = error.response.data.error;
          } else {
            this.errorMessage = error;
          }
        });
      }
    }
  }
</script>