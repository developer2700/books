<template>
  <!-- Used when user is also author -->
  <span>
    <router-link class="btn btn-sm btn-outline-warning" :to="editBookLink">
      <i class="ion-edit"></i> <span>&nbsp;Edit Book</span>
    </router-link>
    <span>&nbsp;&nbsp;</span>
    <router-link class="btn btn-sm btn-outline-primary" :to="viewBookLink">
      <i class="ion-view"></i> <span>&nbsp;View Book</span>
    </router-link>
    <span>&nbsp;&nbsp;</span>
    <button class="btn btn-outline-danger btn-sm" @click="deleteBook">
      <i class="ion-trash-a"></i> <span>&nbsp;Delete Book</span>
    </button>
  </span>
</template>

<script>
import { mapGetters } from "vuex";
import {
  BOOK_DELETE,
} from "@/store/actions.type";

export default {
  name: "RwvBookActions",
  props: {
    book: { type: Object, required: true },
  },
  computed: {
    editBookLink() {
      return { name: "book-edit", params: { slug: this.book.id } };
    },
    viewBookLink() {
      return { name: "book", params: { slug: this.book.id } };
    },
  },
  methods: {
    async deleteBook() {
      if(confirm("Do yo want to remove this row?")) {
        try {
          await this.$store.dispatch(BOOK_DELETE, this.book.id);
          // this.$router.push("/");
          window.location.reload();
        } catch (err) {
          console.error(err);
        }
      }
    }
  }
};
</script>
