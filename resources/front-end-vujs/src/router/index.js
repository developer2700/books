import Vue from "vue";
import Router from "vue-router";

Vue.use(Router);

export default new Router({
  routes: [
    {
      path: "/",
      component: () => import("@/views/Home"),
      children: [
        {
          path: "",
          name: "home",
          component: () => import("@/views/HomeGlobal")
        },
      ]
    },
    {
      name: "book",
      path: "/books/:slug",
      component: () => import("@/views/Book"),
      props: true
    },
    {
      name: "book-edit",
      path: "/editor/:slug?",
      props: true,
      component: () => import("@/views/BookEdit")
    },
    {
      name: "books-export",
      path: "/books/export",
      props: true,
      component: () => import("@/views/BooksExport")
    }
  ]
});
