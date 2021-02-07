const state = {
  pageTitle: "Dashboard",
};

// getters these are computed variant for store objects
const getters = {
  getPageTitle: (state, getters, rootState) => {
    return state.pagetTitle;
  },
};

// actions
const actions = {
  setPageTitle({ commit, state }, pageTitle) {
    commit("setPageTitle", pageTitle);
  },
};

// mutations
const mutations = {
  setPageTitle(state, pageTitle) {
    state.pageTitle = pageTitle;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
