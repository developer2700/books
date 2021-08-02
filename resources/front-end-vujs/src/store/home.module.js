import { BooksService } from "@/common/api.service";
import { FETCH_BOOKS } from "./actions.type";
import {
  FETCH_START,
  FETCH_END,
  UPDATE_BOOK_IN_LIST,
  SET_AUTH,
} from "./mutations.type";

const state = {
  books: [],
  isLoading: true,
  booksCount: 0
};

const getters = {
  booksCount(state) {
    return state.booksCount;
  },
  books(state) {
    return state.books;
  },
  isLoading(state) {
    return state.isLoading;
  },
};

const actions = {
  [FETCH_BOOKS]({ commit }, params) {
    commit(FETCH_START);
    return BooksService.query(params.type, params.filters)
      .then(({ data }) => {
        commit(FETCH_END, data);
      })
      .catch(error => {
        throw new Error(error);
      });
  },
};

/* eslint no-param-reassign: ["error", { "props": false }] */
const mutations = {
  [FETCH_START](state) {
    state.isLoading = true;
  },
  [FETCH_END](state, { books, booksCount }) {
    state.books = books;
    state.booksCount = booksCount;
    state.isLoading = false;
  },
  [UPDATE_BOOK_IN_LIST](state, data) {
    state.books = state.books.map(book => {
      if (book.slug !== data.slug) {
        return book;
      }
      // We could just return data, but it seems dangerous to
      // mix the results of different api calls, so we
      // protect ourselves by copying the information.
      book.attachments = data.attachments;
      return book;
    });
  }
};

export default {
  state,
  getters,
  actions,
  mutations
};
