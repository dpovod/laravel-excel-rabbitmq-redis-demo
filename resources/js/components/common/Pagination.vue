<template>
  <div class="row mb-5">
    <nav aria-label="pagination">
      <ul class="pagination">
        <li class="page-item" :class="{disabled: pagination.current_page <= 1}">
          <a class="page-link" @click.prevent="changePage(1)" :disabled="pagination.current_page <= 1" href="javascript:">First
            page</a>
        </li>
        <li class="page-item" :class="{disabled: pagination.current_page <= 1}">
          <a class="page-link" @click.prevent="changePage(pagination.current_page - 1)"
             :disabled="pagination.current_page <= 1" href="javascript:">Previous</a>
        </li>
        <li v-for="page in pages" class="page-item" :class="{active: isCurrentPage(page)}">
          <a class="page-link" @click.prevent="changePage(page)"
             href="javascript:" :disabled="pagination.current_page === page">{{ page }}</a>
        </li>
        <li class="page-item" :class="{disabled: pagination.current_page >= pagination.last_page}">
          <a class="page-link" @click.prevent="changePage(pagination.current_page + 1)"
             :disabled="pagination.current_page >= pagination.last_page" href="javascript:">Next page</a>
        </li>
        <li class="page-item" :class="{disabled: pagination.current_page >= pagination.last_page}">
          <a class="page-link" @click.prevent="changePage(pagination.last_page)"
             :disabled="pagination.current_page >= pagination.last_page" href="javascript:">Last page</a>
        </li>
      </ul>
    </nav>
    <div class="text-muted" style="position: absolute; right: 0;">
      <div class="pagination ml-3" style="display:table; margin-block-end: 1em; padding: 7px;">
        <div style="display:table-cell;vertical-align:middle;">
          <div style="text-align:right;">{{ pagination.first_item }}..{{ pagination.last_item}} of {{pagination.total}}</div>
        </div>
      </div>
    </div>
  </div>

</template>

<style>
.pagination {
  margin-top: 40px;
}
</style>

<script>
export default {
  props: ['pagination', 'offset'],

  methods: {
    isCurrentPage(page) {
      return this.pagination.current_page === page;
    },

    changePage(page) {
      if (this.isCurrentPage(page)) {
        return;
      }

      if (page > this.pagination.last_page) {
        page = this.pagination.last_page;
      }

      this.pagination.current_page = page;
      this.$emit('paginate');
    }
  },

  computed: {
    pages() {
      let pages = [];

      let first_item = this.pagination.current_page - Math.floor(this.offset / 2);

      if (first_item < 1) {
        first_item = 1;
      }

      let last_item = first_item + this.offset - 1;

      if (last_item > this.pagination.last_page) {
        last_item = this.pagination.last_page;
      }

      while (first_item <= last_item) {
        pages.push(first_item);
        first_item++;
      }

      return pages;
    }
  }
}
</script>
