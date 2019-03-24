const state = {
  routes: {
    'edit_site': '/site/edit/{slug}',
    'add_site': '/site/add'
  }
};

const getters = {
  path: state => name => {
    return state.routes[name];
  }
};

const actions = {
  getRoute({ commit, state }, name) {
    return state.routes[name];
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions
}