<template>
  <table class="table table-striped">
    <thead class="thead-dark">
      <tr>
        <th @click="sort('name')" class="tableTh">
          <div class="d-flex justify-content-between">
            Category
            <div>
              <span :class="{ 'text-warning': state.currentSort === 'name' && state.currentSortDir === 'asc' }">
                &uarr;
              </span>
              <span :class="{ 'text-warning': state.currentSort === 'name' && state.currentSortDir === 'desc' }">
                &darr;
              </span>
            </div>
          </div>
        </th>
        <th class="tableTh">
          Description
        </th>
        <th class="buttonTh"></th>
        <th class="buttonTh"></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="category in sortedContent" :key="category.ID">
        <td>{{ category.name }}</td>
        <td>{{ category.description }}</td>
        <td class="buttonTh"><updateBtn :ID="category.ID" /></td>
        <td class="buttonTh"><deleteBtn :ID="category.ID" /></td>
      </tr>
    </tbody>
  </table>

  <h5 class="text-center mt-4" v-if="state.showLoading === true">Loading...</h5>
  <h4 class="text-center text-danger mt-4" v-if="state.showErrorMessage === true">
    Something went wrong
  </h4>

  <!-- PAGINATION -->
  <nav v-if="pageNums !== 0" class="mt-2 d-flex justify-content-between align-items-center">
    <p>
      Showing {{ (state.currentPage - 1) * state.pageSize + 1 }} to
      <span v-if="state.currentPage === pageNums.length">{{ sortedForSearchContent.length }}</span>
      <span v-else>{{ state.currentPage * state.pageSize }}</span>
      of
      {{ sortedForSearchContent.length }} purchases
    </p>

    <ul class="pagination">
      <li class="page-item" :class="{ disabled: state.currentPage === 1 }" title="First">
        <button class="page-link" @click="setPage(1)">&laquo;</button>
      </li>
      <li class="page-item" :class="{ disabled: state.currentPage === 1 }" title="Previous">
        <button class="page-link" @click="prevPage()">&lsaquo;</button>
      </li>
      <li class="page-item" v-for="page in pageNums" :key="page" :class="{ active: state.currentPage === page }">
        <button class="page-link" @click="setPage(page)">{{ page }}</button>
      </li>
      <li class="page-item" :class="{ disabled: state.currentPage === pageNums.length }" title="Next">
        <button class="page-link" @click="nextPage()">&rsaquo;</button>
      </li>
      <li class="page-item" :class="{ disabled: state.currentPage === pageNums.length }" title="Last">
        <button class="page-link" @click="setPage(pageNums.length)">&raquo;</button>
      </li>
    </ul>
  </nav>
</template>

<script>
import { computed, onMounted, reactive, watch } from "vue";
import { useStore } from "vuex";
import updateBtn from "@/components/Categories/updateBtn.vue";
import deleteBtn from "@/components/Categories/deleteBtn.vue";

export default {
  name: "CategoriesTable",
  components: { updateBtn, deleteBtn },

  props: {
    searchInput: {
      type: String,
      required: false,
    },
    pageSizeProp: {
      type: Number,
      required: true,
    },
  },

  setup(props) {
    const store = useStore();
    const content = computed(() => store.getters["CategoriesTable/GET_CONTENT"]);

    const state = reactive({
      currentSort: "name",
      currentSortDir: "asc",
      pageSize: computed(() => {
        state.currentPage = 1;
        return props.pageSizeProp;
      }),
      currentPage: 1,
      showLoading: true,
      showErrorMessage: false,
    });

    onMounted(async () => {
      try {
        state.showLoading = true;
        await store.dispatch("CategoriesTable/SELECT_DATA");
      } catch {
        state.showErrorMessage = true;
      } finally {
        state.showLoading = false;
      }
    });

    function sort(s) {
      if (s === state.currentSort) {
        //Change direction
        state.currentSortDir = state.currentSortDir === "asc" ? "desc" : "asc";
      } else {
        //Set currentSort to the clicked one
        state.currentSort = s;
        state.currentSortDir = "asc";
      }
      state.currentPage = 1;
    }

    const sortedContent = computed(() => {
      return (
        content.value
          //Search filter
          .filter(
            (category) =>
              category.name.toLowerCase().includes(props.searchInput.toLowerCase()) ||
              category.description.toLowerCase().includes(props.searchInput.toLowerCase())
          )
          //Sort filter
          .sort((a, b) => {
            let modifier = 1;
            if (state.currentSortDir === "desc") modifier = -1;
            if (a[state.currentSort] < b[state.currentSort]) return -1 * modifier;
            if (a[state.currentSort] > b[state.currentSort]) return modifier;
            return 0;
          })
          //Pagination filter
          .filter((row, index) => {
            let start = (state.currentPage - 1) * state.pageSize;
            let end = state.currentPage * state.pageSize;
            if (index >= start && index < end) return true;
          })
      );
    });

    //Needed for pagination nav
    const pageNums = computed(() => {
      const numOfPages = computed(() => Math.ceil(sortedForSearchContent.value.length / state.pageSize));
      const pageNums = [];
      for (let i = 1; i <= numOfPages.value; i++) {
        pageNums.push(i);
      }
      return pageNums;
    });

    //Needed for 'pageNums'
    const sortedForSearchContent = computed(() => {
      return (
        content.value
          //Search filter
          .filter(
            (category) =>
              category.name.toLowerCase().includes(props.searchInput.toLowerCase()) ||
              category.description.toLowerCase().includes(props.searchInput.toLowerCase())
          )
      );
    });

    function nextPage() {
      state.currentPage++;
    }
    function prevPage() {
      state.currentPage--;
    }
    function setPage(pageId) {
      state.currentPage = pageId;
    }

    //Go to the first page when searching something
    watch(
      () => props.searchInput,
      () => {
        state.currentPage = 1;
      }
    );

    return {
      sortedContent,
      sort,
      nextPage,
      prevPage,
      setPage,
      pageNums,
      state,
      sortedForSearchContent,
    };
  },
};
</script>

<style lang="scss" scoped>
.tableTh {
  cursor: pointer;
  user-select: none;
}
.buttonTh {
  width: 40px;
  padding: 0 !important;
  padding-top: 5px !important;
  text-align: center;
}
.pagination {
  user-select: none;
  flex-wrap: wrap;
}
th {
  padding: 0.4rem !important;
}
td {
  padding: 0.35rem !important;
}
</style>
