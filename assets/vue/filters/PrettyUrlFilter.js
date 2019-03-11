import Vue from 'vue';

Vue.filter('prettyUrl', url => {
  return url.substr(url.indexOf('//') + 2);
});