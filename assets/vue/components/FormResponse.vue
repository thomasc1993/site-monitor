<template>
  <transition name="fade">
    <div class="loadingCloak" v-if="isLoading || success || error">
      <div class="loadingCloak__content">
        <Loader v-if="isLoading" />
        <transition name="popIn">
          <div class="success" v-if="success">
            <i class="ion-md-checkmark-circle-outline"></i>
            <p class="success__message">{{ success }}</p>
          </div>
          <div class="error" v-if="error">
            <i class="ion-md-alert"></i>
            <p class="error__message">{{ error }}</p>
          </div>
        </transition>
      </div>
    </div>
  </transition>
</template>

<script>
  import Loader from './Loader';

  export default {
    name: 'FormResponse',

    props: [
      'isLoading',
      'success',
      'error'
    ],

    components: {
      Loader
    }
  }
</script>