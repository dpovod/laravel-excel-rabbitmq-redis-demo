<template>
  <div class="row">
    <div class="col-sm-12">
      <div class="card border-0" v-if="list.length !== 0">
        <div class="card-body">

          <table class="table table-striped table-hover" cellspacing="0" width="100%">
            <thead class="thead-light">
            <tr>
              <th>Date</th>
              <th>ID</th>
              <th>Name</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(row, date) in list">
              <td class="align-middle">{{ date }}</td>
              <td class="align-middle">
                <ul class="list-unstyled mb-0">
                  <li v-for="item in row">{{ item.id }}</li>
                </ul>
              </td>
              <td class="align-middle">
                <ul class="list-unstyled mb-0">
                  <li v-for="item in row">{{ item.name }}</li>
                </ul>
              </td>
            </tr>
            </tbody>
          </table>
        </div>

        <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="10"
                              @paginate="fetchItems"></pagination>

      </div>

      <div class="card border-0" v-if="isMounted && list.length === 0">
        <div class="card-body">
          <div class="alert alert-secondary" role="alert">
            No data to display
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import RowService from "./../services/RowService";

export default {
  data() {
    return {
      list: [],
      pagination: {
        'current_page': 1
      },
      isMounted: false,
    }
  },
  methods: {
    getList(page) {
      return RowService.list(page);
    },
    fetchItems() {
      this.toggleLoader();

      return this.getList(this.pagination.current_page)
          .then(response => {
            this.list = response.data.data.items;
            this.pagination = response.data.data.pagination;
          })
          .catch(() => {
            this.$noty.error('Unknown Error');
          })
          .finally(() => {
            this.toggleLoader(false);
          });
    },
    toggleLoader(show = true) {
      const loader = document.getElementById('loading');
      show ? loader.classList.remove('d-none') : loader.classList.add('d-none');
    }
  },
  mounted() {
    this.fetchItems().then(() => this.isMounted = true);
  }
}
</script>