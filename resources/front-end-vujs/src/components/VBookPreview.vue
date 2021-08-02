<template>
  <tr>
    <td>{{book.title|truncate(50, '...')}}</td>
    <td>{{author_name}}</td>
    <td>{{book.status}}</td>
    <td>{{book.created_at|date}}</td>
    <td>
      <rwv-book-actions
        :book="book"
      ></rwv-book-actions>
    </td>
  </tr>
</template>

<script>
  import RwvBookActions from "@/components/BookActions";

  export default {
    name: "RwvBookPreview",
    components: {
      RwvBookActions
    },
    props: {
      book: {type: Object, required: true},
    },
    computed: {
      author_name() {
        return `${this.book.author.first_name} ${this.book.author.last_name}`;
      },
    }
  };
</script>
