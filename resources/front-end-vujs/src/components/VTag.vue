<template>
  <a target="_blank"
     :href="fileLink"
     class="tag-pill tag-default">{{name}}</a>
</template>

<script>
  import { API_URL } from "@/common/config";

  export default {
  name: "RwvTag",
  props: {
    name: {
      type: String,
      required: true
    },
    className: {
      type: String,
      default: "tag-pill tag-default"
    }
  },
  computed: {
    fileLink() {
      return `${API_URL}/uploads/${this.name}`;
    },
  },
};
</script>
