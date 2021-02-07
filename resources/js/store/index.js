import Vue from "vue";
import Vuex from "vuex";
import moduleApp from "./modules/app";

Vue.use(Vuex);

const store = new Vuex.Store({
  modules: {
    app: moduleApp,
  },
});

if (module.hot) {
  // accept actions and mutations as hot modules
  module.hot.accept(["./modules/app"], () => {
    const newModuleApp = require("./modules/app").default;

    store.hotUpdate({
      modules: {
        app: newModuleApp,
      },
    });
  });
}

export default store;
