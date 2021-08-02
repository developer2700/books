<template>
  <div class="book-page">
    <div class="banner">
      <div class="container">
        <h1>Export {{ booksCount }}  {{ booksCount > 2 ? 'rows' : 'row' }}  </h1>
      </div>
    </div>
    <div class="container page">
      <div class="row book-content">
        <div class="col-xs-12">

          <h4>Export Format:</h4>
          <div class="radio-inline">
              <label> CSV
              <input type="radio" class="custom-radio-checkbox"  value="csv" v-model="export_format">
              </label>
          </div>
          <div class="radio-inline ">
              <label>  XML
                <input class="custom-radio-checkbox" type="radio" id="xml" value="xml" v-model="export_format">
              </label>
          </div>

          <h4>Export Fields</h4>

          <div class="checkbox-inline">
            <label> Title
              <input class="custom-radio-checkbox" v-model="export_fields" type="checkbox" value="title">
            </label>
          </div>
          <div class="checkbox-inline">
            <label> Author
              <input class="custom-radio-checkbox" v-model="export_fields" type="checkbox" value="author.full_name">
            </label>
          </div>

        </div>
      </div>
      <hr />
      <router-link
        class="btn btn-md pull-xs-right btn-secondary"
        :to="{ name: 'home' }"
      >
        Back
      </router-link>

      <button class="btn btn-primary btn-md" @click="exportBooks">
        <i class="ion-code-download"></i> <span>&nbsp;Export Books</span>
      </button>

    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import store from "@/store";
import { API_URL } from "@/common/config";
import { BooksService,} from "@/common/api.service";

export default {
  name: "rwve-books",
  data() {
    return {
      export_format: 'csv',
      export_fields: ['title','author.full_name'],
    };
  },
  props: {
    listConfig: {
      required: true
    },
    booksCount:{
      required: true
    }
  },

  methods: {
    async exportBooks() {
        try {
          this.listConfig.filters.format = this.export_format;
          this.listConfig.filters.fields = this.export_fields;
          await BooksService.query('export', this.listConfig.filters)
            .then(({ data }) => {
              window.location.href = API_URL+'/'+data;
            })
            .catch(error => {
              throw new Error(error);
            });
        } catch (err) {
          console.error(err);
        }

    },
  }

};
</script>
