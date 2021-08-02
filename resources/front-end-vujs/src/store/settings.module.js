import { FETCH_BOOK} from "./actions.type";
import { SET_BOOK } from "./mutations.type";
import {BooksService} from "../common/api.service";

export const state = {
  book: {},
};

export const actions = {
  [FETCH_BOOK](context, bookSlug) {
    return BooksService.get(bookSlug)
      .then(({ data }) => {
        context.commit(SET_BOOK, data.book);
      })
      .catch(error => {
        throw new Error(error);
      });
  },
};

/* eslint no-param-reassign: ["error", { "props": false }] */
export const mutations = {
  [SET_BOOK](state, book) {
    state.book = book;
  },
};

export default {
  state,
  actions,
  mutations
};
