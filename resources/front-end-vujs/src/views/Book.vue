<template>
  <div class="book-page">
    <div class="banner">
      <div class="container">
        <h1>{{ book.title }}</h1>
      </div>
    </div>
    <div class="container page">
      <div class="row book-content">
        <div class="col-xs-12">
          <div><strong>Title: </strong> <span v-text="book.title"></span></div>
          <div><strong>Author: </strong> <span v-text="`${book.author.first_name} ${book.author.last_name}`"></span></div>
          <div><strong>Date: </strong> <span v-text="book.created_at"></span></div>
          <div><strong>Status: </strong> <span v-text="book.status"></span></div>
          <ul class="tag-list">
            <li v-for="(attachment, index) of book.attachments" :key="attachment + index">
              <RwvTag
                :name="attachment.filename"
                className="tag-default tag-pill tag-outline"
              ></RwvTag>
            </li>
          </ul>
          <div v-text="book.title"></div>
          <div v-html="book.description"></div>

        </div>
      </div>
      <hr />
      <router-link
        class="btn btn-lg pull-xs-left btn-secondary"
        :to="{ name: 'home' }"
      >
        Back
      </router-link>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import marked from "marked";
import store from "@/store";
import RwvTag from "@/components/VTag";
import { FETCH_BOOK } from "@/store/actions.type";

export default {
  name: "rwv-book",
  props: {
    slug: {
      required: true
    }
  },
  components: {
    RwvTag
  },
  beforeRouteEnter(to, from, next) {
    Promise.all([
      store.dispatch(FETCH_BOOK, to.params.slug),
    ]).then(() => {
      next();
    });
  },
  computed: {
    ...mapGetters(["book"])
  },
  methods: {
    parseMarkdown(content) {
      return marked(content);
    }
  }
};
</script>
