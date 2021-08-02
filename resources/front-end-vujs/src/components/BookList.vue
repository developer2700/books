<template>
  <div>
    <div class="row align-items-center">
      <div class="col-md-3 my-1">
        <input
          type="text"
          class="form-control"
          v-model="title"
          placeholder="Title"
        />
      </div>
      <div class="col-md-3 my-1">
        <input
          type="text"
          class="form-control"
          v-model="author"
          placeholder="Author"
        />
      </div>

      <div class="col-md-3 my-1">
        <select
          class="form-control"
          v-model="status">
          <option>All</option>
          <option>Pending</option>
          <option>Published</option>
        </select>

      </div>

      <router-link class="btn btn-md btn-primary" :to="exportBooksLink">
        <i class="ion-android-list"></i> <span>&nbsp;Export Books</span>
      </router-link>

    </div>
    <span v-if="isLoading" class="book-preview">Loading books...</span>
    <div >
      <div v-if="books.length === 0" class="book-preview">
        No books are here... yet.
      </div>
      <VPagination :pages="pages" :currentPage.sync="currentPage"/>
      <div class="list-items">
        <div v-if="booksCount">rows: {{booksCount}}</div>
        <div class="row">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead class="thead-light">
              <tr>
                <th class="ion-connection-bars" @click="resetSort('title')"> Title
                  <span v-if="order_by=='title'" >
                       <span v-if="sort=='DESC'" class="ion-arrow-up-c"></span>
                       <span v-else class="ion-arrow-down-c"></span>
                  </span>
                </th>
                <th class="ion-connection-bars" @click="resetSort('authors.first_name')"> Author
                  <span v-if="order_by=='authors.first_name'" >
                       <span v-if="sort=='DESC'" class="ion-arrow-up-c"></span>
                       <span v-else class="ion-arrow-down-c"></span>
                  </span>
                </th>
                <th class="ion-connection-bars" @click="resetSort('status')"> Status
                  <span v-if="order_by=='status'">
                       <span v-if="sort=='DESC'" class="ion-arrow-up-c"></span>
                       <span v-else class="ion-arrow-down-c"></span>
                  </span>
                </th>
                <th class="ion-connection-bars" @click="resetSort('created_at')"> Date
                  <span v-if="order_by=='created_at'">
                       <span v-if="sort=='DESC'" class="ion-arrow-up-c"></span>
                       <span v-else class="ion-arrow-down-c"></span>
                  </span>
                </th>
                <th>Actions</th>
              </tr>
              </thead>
              <tbody>
              <RwvBookPreview
                :sort="sort"
                :order_by="order_by"
                v-for="(book, index) in books"
                :book="book"
                :key="book.title + index"
              />
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import {mapGetters} from "vuex";
  import RwvBookPreview from "./VBookPreview";
  import VPagination from "./VPagination";
  import {FETCH_BOOKS} from "../store/actions.type";

  export default {
    name: "RwvBookList",
    components: {
      RwvBookPreview,
      VPagination
    },
    props: {
      type: {
        type: String,
        required: false,
        default: "all"
      },
      itemsPerPage: {
        type: Number,
        required: false,
        default: 10
      }
    },
    data() {
      return {
        currentPage: 1,
        title: '',
        author: '',
        status: '',
        sort: 'DESC',
        order_by: 'id',
      };
    },
    computed: {
      exportBooksLink() {
        return { name: "books-export", params: { listConfig: this.listConfig , booksCount:this.booksCount } };
      },
      listConfig() {
        const {type} = this;
        const filters = {
          offset: (this.currentPage - 1) * this.itemsPerPage,
          limit: this.itemsPerPage
        };
        if (this.title) {
          filters.title = this.title;
        }
        if (this.status) {
          filters.status = this.status;
        }
        if (this.author) {
          filters.author = this.author;
        }
        if (this.sort) {
          filters.sort = this.sort;
        }
        if (this.order_by) {
          filters.order_by = this.order_by;
        }

        return {
          type,
          filters
        };
      },
      pages() {
        if (this.isLoading || this.booksCount <= this.itemsPerPage) {
          return [];
        }
        return [
          ...Array(Math.ceil(this.booksCount / this.itemsPerPage)).keys()
        ].map(e => e + 1);
      },
      ...mapGetters(["booksCount", "isLoading", "books"])
    },
    watch: {
      currentPage(newValue) {
        this.listConfig.filters.offset = (newValue - 1) * this.itemsPerPage;
        this.fetchBooks();
      },
      type() {
        this.resetPagination();
        this.fetchBooks();
      },
      title() {
        this.resetPagination();
        this.fetchBooks();
      },
      author() {
        this.resetPagination();
        this.fetchBooks();
      },
      status() {
        this.resetPagination();
        this.fetchBooks();
      },
      sort() {
        this.resetPagination();
        this.fetchBooks();
      },
      order_by() {
        this.resetPagination();
        this.fetchBooks();
      },
    },
    mounted() {
      this.fetchBooks();
    },
    methods: {
      fetchBooks() {
        this.$store.dispatch(FETCH_BOOKS, this.listConfig);
      },
      resetPagination() {
        this.listConfig.offset = 0;
        this.currentPage = 1;
      },
      resetSort(order_by) {
        if (order_by === this.order_by) {
          this.sort = this.sort === 'ASC' ? 'DESC' : 'ASC';
        }
        this.order_by = order_by;
      },
    }
  };
</script>
