<template>
  <table class="table table-striped">
    <thead class="thead-dark">
      <tr>
        <th @click="sort('date')" class="tableTh">
          <div class="d-flex justify-content-between">
            Date
            <div>
              <span :class="{ 'text-warning': state.currentSort === 'date' && state.currentSortDir === 'asc' }">
                &uarr;
              </span>
              <span :class="{ 'text-warning': state.currentSort === 'date' && state.currentSortDir === 'desc' }">
                &darr;
              </span>
            </div>
          </div>
        </th>
        <th @click="sort('category')" class="tableTh">
          <div class="d-flex justify-content-between">
            Category
            <div>
              <span :class="{ 'text-warning': state.currentSort === 'category' && state.currentSortDir === 'asc' }">
                &uarr;
              </span>
              <span :class="{ 'text-warning': state.currentSort === 'category' && state.currentSortDir === 'desc' }">
                &darr;
              </span>
            </div>
          </div>
        </th>
        <th @click="sort('item')" class="tableTh">
          <div class="d-flex justify-content-between">
            Product
            <div>
              <span :class="{ 'text-warning': state.currentSort === 'item' && state.currentSortDir === 'asc' }">
                &uarr;
              </span>
              <span :class="{ 'text-warning': state.currentSort === 'item' && state.currentSortDir === 'desc' }">
                &darr;
              </span>
            </div>
          </div>
        </th>
        <th class="tableTh">
            Price
        </th>
        <th class="tableTh">
            Quantity
        </th>
        <th @click="sort('total_price')" class="tableTh">
          <div class="d-flex justify-content-between">
            Total Price
            <div>
              <span :class="{ 'text-warning': state.currentSort === 'total_price' && state.currentSortDir === 'asc' }">
                &uarr;
              </span>
              <span :class="{ 'text-warning': state.currentSort === 'total_price' && state.currentSortDir === 'desc' }">
                &darr;
              </span>
            </div>
          </div>
        </th>
        <th class="buttonTh"></th>
        <th class="buttonTh"></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="purchase in sortedContent" :key="purchase.ID">
        <td>{{ purchase.date }}</td>
        <td>{{ purchase.category }}</td>
        <td>{{ purchase.item }}</td>
        <td>{{ purchase.price }}</td>
        <td>{{ purchase.quantity }}</td>
        <td>{{ purchase.total_price }}</td>
        <td class="buttonTh"><updateBtn :ID="purchase.ID" /></td>
        <td class="buttonTh"><deleteBtn :ID="purchase.ID" /></td>
      </tr>
    </tbody>
  </table>

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
import updateBtn from "@/components/Purchases/updateBtn.vue";
import deleteBtn from "@/components/Purchases/deleteBtn.vue";

export default {
  name: "PurchasesTable",
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
    const content = computed(() => store.getters["PurchasesTable/GET_CONTENT"]);

    const state = reactive({
      currentSort: "date",
      currentSortDir: "asc",
      pageSize: computed(() => {
        state.currentPage = 1;
        return props.pageSizeProp;
      }),
      currentPage: 1,
      fromAndToDate: computed(() => store.getters["FromAndToDate/GET_DATE"]),
    });

    onMounted(() => {
      store.dispatch("CategoriesTable/SELECT_DATA");
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
          //fromAndToDate filter
          .filter((purchase) => purchase.date >= state.fromAndToDate.fromDate && purchase.date <= state.fromAndToDate.toDate)
          //Search filter
          .filter(
            (purchase) =>
              purchase.date.toLowerCase().includes(props.searchInput.toLowerCase()) ||
              purchase.item.toLowerCase().includes(props.searchInput.toLowerCase()) ||
              purchase.category.toLowerCase().includes(props.searchInput.toLowerCase())
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
          //fromAndToDate filter
          .filter((purchase) => purchase.date >= state.fromAndToDate.fromDate && purchase.date <= state.fromAndToDate.toDate)
          //Search filter
          .filter(
            (purchase) =>
              purchase.date.toLowerCase().includes(props.searchInput.toLowerCase()) ||
              purchase.item.toLowerCase().includes(props.searchInput.toLowerCase()) ||
              purchase.category.toLowerCase().includes(props.searchInput.toLowerCase())
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

  &:nth-child(1) {
    width: 130px;
  }
  &:nth-child(2) {
    width: 210px;
  }
  &:nth-child(4) {
    width: 105px;
  }
  &:nth-child(5) {
    width: 120px;
  }
  &:nth-child(6) {
    width: 135px;
  }
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
