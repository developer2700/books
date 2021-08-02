<template>
  <div class="editor-page">
    <div class="container page">
      <div class="row">
        <div class="col-md-10 offset-md-1 col-xs-12">
          <RwvListErrors :errors="errors"/>

          <form @submit.prevent="onPublish(book.id)">
            <fieldset :disabled="inProgress">
              <fieldset class="form-group">
                <input
                  type="text"
                  class="form-control form-control-lg"
                  v-model="book.title"
                  placeholder="Book Title"
                />
              </fieldset>
              <fieldset class="form-group">
                <em>Select an author or enter firstname & lastname bellow</em>
                <v-select
                  label="first_name"
                  @search="onSearch"
                  v-model="book.author"
                  :getOptionLabel="author => author.id ?  `${author.first_name} ${author.last_name}` : null"
                  :options="Object.values(authors)"
                >
                  <span slot="no-options" @click="$refs.select.open = false">
                    Type some words to get authors
                  </span>
                </v-select>
              </fieldset>

              <fieldset class="form-group">
                <input
                  type="text"
                  class="form-control form-control-md"
                  v-model="book.author.first_name"
                  placeholder="Author FirstName"
                />
              </fieldset>

              <fieldset class="form-group">
                <input
                  type="text"
                  class="form-control form-control-md"
                  v-model="book.author.last_name"
                  placeholder="Author LastName"
                />
              </fieldset>
              <fieldset class="form-group">
                <select
                  class="form-control form-control-md"
                  v-model="book.status" placeholder="Status">
                  <option>Pending</option>
                  <option>Published</option>
                </select>
              </fieldset>
              <fieldset class="form-group">
                <wysiwyg v-model="book.description" placeholder="Write your book description "/>
              </fieldset>

              <fieldset class="form-group">
                <vue-dropzone ref="myVueDropzone" id="dropzone"
                              v-on:vdropzone-success="sendingEvent"
                              v-on:vdropzone-removed-file="dropzoneRemovedFile"
                              :options="dropzoneOptions"></vue-dropzone>
              </fieldset>


            </fieldset>
            <button
              :disabled="inProgress"
              class="btn btn-lg pull-xs-right btn-primary"
              type="submit"
            >
              Publish Book
            </button>

            <router-link
              class="btn btn-lg pull-xs-left btn-secondary"
              :to="{ name: 'home' }"
            >
              Cancel
            </router-link>

          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

  import WysIwyg from "vue-wysiwyg";
  import "vue-wysiwyg/dist/vueWysiwyg.css";
  import vue2Dropzone from 'vue2-dropzone'
  import 'vue2-dropzone/dist/vue2Dropzone.min.css'
  import vSelect from 'vue-select'
  import 'vue-select/dist/vue-select.css';
  import {mapGetters} from "vuex";
  import store from "@/store";
  import RwvListErrors from "@/components/ListErrors";
  import {API_URL, MAX_FILE_UPLOAD_SIZE} from "@/common/config";
  import {
    BOOK_PUBLISH,
    BOOK_EDIT,
    FETCH_BOOK,
    BOOK_RESET_STATE,
    FETCH_AUTHORS
  } from "@/store/actions.type";

  export default {
    name: "RwvBookEdit",
    components: {
      RwvListErrors,
      wysiwyg: WysIwyg.component,
      vueDropzone: vue2Dropzone,
      vSelect: vSelect,
    },
    props: {
      previousBook: {
        type: Object,
        required: false
      }
    },
    async beforeRouteUpdate(to, from, next) {
      // Reset state if user goes from /editor/:id to /editor
      // The component is not recreated so we use to hook to reset the state.
      await store.dispatch(BOOK_RESET_STATE);
      return next();
    },
    async beforeRouteEnter(to, from, next) {
      // SO: https://github.com/vuejs/vue-router/issues/1034
      // If we arrive directly to this url, we need to fetch the book
      await store.dispatch(BOOK_RESET_STATE);
      if (to.params.slug !== undefined) {
        await store.dispatch(
          FETCH_BOOK,
          to.params.slug,
          to.params.previousBook
        );
      }
      return next();
    },
    async beforeRouteLeave(to, from, next) {
      await store.dispatch(BOOK_RESET_STATE);
      next();
    },
    data() {
      return {
        tagInput: null,
        inProgress: false,
        errors: {},
        dropzoneOptions: {
          url: API_URL + '/upload-file',
          thumbnailWidth: 150,
          maxFilesize: MAX_FILE_UPLOAD_SIZE,
          addRemoveLinks: true,
          acceptedFiles: 'image/*,application/pdf,.psd,application/*,text/*,.csv',
          headers: {"My-Awesome-Header": "header value"}
        }
      };
    },
    mounted() {
      // load uploaded attachments and display then on dropzone
      this.book.attachments.forEach((attachment) => {
        let file_url = API_URL + '/uploads/' + attachment.filename;
        let mockFile = {id: attachment.id, name: attachment.filename, size: 526};
        this.$refs.myVueDropzone.manuallyAddFile(mockFile, file_url);
      });
    },
    computed: {
      ...mapGetters(["book", 'authors'])
    },
    methods: {
      onSearch(search, loading) {
        if (search.length) {
          loading(true);
          const listConfig = {
            filters: {
              limit: 10,
              name: search,
            }
          };
          this.$store
            .dispatch(FETCH_AUTHORS, listConfig)
            .then(({data}) => {
              loading(false);
            })
            .catch(({response}) => {
              loading(false);
            });
        }
      },
      onPublish(slug) {
        let action = slug ? BOOK_EDIT : BOOK_PUBLISH;
        this.inProgress = true;
        this.$store
          .dispatch(action)
          .then(({data}) => {
            this.inProgress = false;
            this.$router.push({
              name: "book",
              params: {slug: data.book.id}
            });
          })
          .catch(({response}) => {
            this.inProgress = false;
            this.errors = response.data.errors;

            //scroll to errors
            setTimeout(() => {
              this.$el
                .getElementsByClassName("error-messages")
                [
              this.$el.getElementsByClassName("error-messages").length -
              1
                ].scrollIntoView();
            }, 50);
          });
      },
      sendingEvent(file, response) {
        this.book.attachments.push({filename: response.filename});
      },
      dropzoneRemovedFile(file, error, xhr) {
        this.book.attachments = this.book.attachments.filter((attachment) => attachment.filename != file.name)
      },

    }
  };
</script>
