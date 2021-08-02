import Vue from "vue";
import {
  BooksService,
  AuthorsService
} from "@/common/api.service";
import {
  FETCH_BOOK,
  BOOK_PUBLISH,
  BOOK_EDIT,
  BOOK_DELETE,
  BOOK_RESET_STATE,
  FETCH_AUTHORS,
  FETCH_BOOKS,
} from "./actions.type";
import {
  FETCH_END,
  FETCH_START,
  RESET_STATE,
  SET_AUTHORS,
  SET_BOOK,
  UPDATE_BOOK_IN_LIST,
} from "./mutations.type";

const initialState = {
  book: {
    author: {},
    title: "",
    status: "",
    description: "",
    attachments: []
  },
  authors:{}
};

export const state = { ...initialState };

export const actions = {
  async [FETCH_BOOK](context, bookSlug, prevBook) {
    // avoid extronuous network call if article exists
    if (prevBook !== undefined) {
      return context.commit(SET_BOOK, prevBook);
    }
    const { data } = await BooksService.get(bookSlug);
    context.commit(SET_BOOK, data.book);
    return data;
  },
  [FETCH_AUTHORS]({ commit }, params) {
    // commit(FETCH_START);
    return AuthorsService.query(params.type, params.filters)
      .then(({ data }) => {
        commit(SET_AUTHORS, data);
      })
      .catch(error => {
        throw new Error(error);
      });
  },
  [BOOK_PUBLISH]({ state }) {
    return BooksService.create(state.book);
  },
  [BOOK_DELETE](context, slug) {
    return BooksService.destroy(slug);
  },
  [BOOK_EDIT]({ state }) {
    return BooksService.update(state.book.id, state.book);
  },
  [BOOK_RESET_STATE]({ commit }) {
    commit(RESET_STATE);
  },
};

/* eslint no-param-reassign: ["error", { "props": false }] */
export const mutations = {
  [SET_BOOK](state, book) {
    state.book = book;
  },
  [SET_AUTHORS](state, { authors, authorsCount }) {
    state.authors = authors;
    state.authorsCount = authorsCount;
    state.isLoading = false;
  },
  [RESET_STATE]() {
    for (let f in state) {
      Vue.set(state, f, initialState[f]);
    }
  }
};

const getters = {
  book(state) {
    return state.book;
  },
  authors(state) {
    return state.authors;
  },
};

export default {
  state,
  actions,
  mutations,
  getters
};
