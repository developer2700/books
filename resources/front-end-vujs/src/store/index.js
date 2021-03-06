import Vue from "vue";
import Vuex from "vuex";

import home from "./home.module";
import auth from "./auth.module";
import book from "./book.module";

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    home,
    auth,
    book,
  }
});
