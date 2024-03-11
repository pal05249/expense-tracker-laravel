<template>
  <ul class="pagination float-right">
    <li class="paginate_button page-item" v-if="pagination.current_page > 1">
      <a class="page-link"
        href="javascript:void(0)"
        aria-label="Previous"
        v-on:click.prevent="changePage(pagination.current_page - 1)"
      >
        <span aria-hidden="true">«</span>
      </a>
    </li>
    <li class="paginate_button page-item"
      v-for="(page, i) in pagesNumber"
      :key="i"
      :class="{ active: page == pagination.current_page }"
    >
      <a class="page-link bg-primary" href="javascript:void(0)" v-on:click.prevent="changePage(page)">{{
        page
      }}</a>
    </li>
    <li class="paginate_button page-item" v-if="pagination.current_page < pagination.last_page">
      <a class="page-link"
        href="javascript:void(0)"
        aria-label="Next"
        v-on:click.prevent="changePage(pagination.current_page + 1)"
      >
        <span aria-hidden="true">»</span>
      </a>
    </li>
  </ul>
</template>
<script>
export default {
  props: {
    pagination: {
      type: Object,
      required: true,
    },
    offset: {
      type: Number,
      default: 4,
    },
  },
  computed: {
    pagesNumber() {
      if (!this.pagination.to) {
        return [];
      }
      let from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }
      let to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }
      let pagesArray = [];
      for (let page = from; page <= to; page++) {
        pagesArray.push(page);
      }
      return pagesArray;
    },
  },
  methods: {
    changePage(page) {
      this.pagination.current_page = page;
      this.$emit("paginate");
    },
  },
};
</script>
<style scoped>
.pagination {
  margin: 2px 0;
  white-space: nowrap;
  justify-content: flex-end;
  display: flex;
  padding-left: 0;
  list-style: none;
  border-radius: 0.25rem;
}
.pagination > li {
  display: inline;
}
.pagination > li > a,
.pagination > li > span {
  position: relative;
  float: left;
  padding: 6px 12px;
  line-height: 1.6;
  text-decoration: none;
  color: #3097d1;
  background-color: #fff;
  border: 1px solid #ddd;
  margin-left: -1px;
}
.pagination > li:first-child > a,
.pagination > li:first-child > span {
  margin-left: 0;
  border-bottom-left-radius: 4px;
  border-top-left-radius: 4px;
}
.pagination > li:last-child > a,
.pagination > li:last-child > span {
  border-bottom-right-radius: 4px;
  border-top-right-radius: 4px;
}
.pagination > li > a:focus,
.pagination > li > a:hover,
.pagination > li > span:focus,
.pagination > li > span:hover {
  z-index: 2;
  color: #216a94;
  background-color: #eee;
  border-color: #ddd;
}
.pagination > .active > a,
.pagination > .active > a:focus,
.pagination > .active > a:hover,
.pagination > .active > span,
.pagination > .active > span:focus,
.pagination > .active > span:hover {
  z-index: 3;
  color: #fff;
  background-color: #3097d1;
  border-color: #3097d1;
  cursor: default;
}
.pagination > .disabled > a,
.pagination > .disabled > a:focus,
.pagination > .disabled > a:hover,
.pagination > .disabled > span,
.pagination > .disabled > span:focus,
.pagination > .disabled > span:hover {
  color: #777;
  background-color: #fff;
  border-color: #ddd;
  cursor: not-allowed;
}
.pagination-lg > li > a,
.pagination-lg > li > span {
  padding: 10px 16px;
  font-size: 18px;
  line-height: 1.3333333;
}
.pagination-lg > li:first-child > a,
.pagination-lg > li:first-child > span {
  border-bottom-left-radius: 6px;
  border-top-left-radius: 6px;
}
.pagination-lg > li:last-child > a,
.pagination-lg > li:last-child > span {
  border-bottom-right-radius: 6px;
  border-top-right-radius: 6px;
}
.pagination-sm > li > a,
.pagination-sm > li > span {
  padding: 5px 10px;
  font-size: 12px;
  line-height: 1.5;
}
.badge,
.label {
  font-weight: 700;
  line-height: 1;
  white-space: nowrap;
  text-align: center;
}
.pagination-sm > li:first-child > a,
.pagination-sm > li:first-child > span {
  border-bottom-left-radius: 3px;
  border-top-left-radius: 3px;
}
.pagination-sm > li:last-child > a,
.pagination-sm > li:last-child > span {
  border-bottom-right-radius: 3px;
  border-top-right-radius: 3px;
}
</style>