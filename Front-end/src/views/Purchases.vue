<template>
  <div class="container mt-4">
    <div class="row align-items-center">
      <div class="col">
        <div class="form-group d-flex align-items-center justify-content-center">
            <p class="m-0">Show purchases from</p>
            <input type="date" class="form-control mx-3 w-25" v-model="state.fromDate" />
            <p class="m-0">to</p>
            <input type="date" class="form-control mx-3 w-25" v-model="state.toDate" id="toDate" />
            <button class="btn btn-info px-3" @click="reloadTable()">
              Submit
            </button>
        </div>
      </div>
    </div>
    <div class="row align-items-center" v-if="state.showContent === true">
      <div class="col-5">
        <div class="input-group" title="Search by date and product name">
          <div class="input-group-prepend">
            <span class="input-group-text bg-dark text-white"> Search:</span>
          </div>
          <input
            type="text"
            class="form-control"
            v-model="state.searchInput"
            placeholder="Press 'Esc' to clear the input"
            v-on:keyup.esc="state.searchInput = ''"
          />
        </div>
      </div>
      <div class="col text-right">
        <div class="form-group mb-0">
          <span>Show </span>
          <span
            ><select
              name="purchases_length"
              v-model.number="state.pageSize"
              class="custom-select custom-select-sm form-control form-control-sm"
              style="width: 60px"
            >
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select></span
          >
          <span> purchases</span>
        </div>
      </div>
      <div class="col-2 text-right">
        <insertBtn />
      </div>
    </div>
    <div class="row mt-3">
      <div class="col">
        <PurchasesTable :searchInput="state.searchInput" :pageSizeProp="state.pageSize" v-if="state.showContent === true" />
        <h5 class="text-center mt-4" v-if="state.showLoading === true">
          Loading...
        </h5>
        <h4 class="text-center text-danger mt-4" v-if="state.showErrorMessage === true">
          Something went wrong
        </h4>
      </div>
    </div>
  </div>
</template>

<script>
import PurchasesTable from "@/components/Purchases/PurchasesTable.vue";
import InsertBtn from "@/components/Purchases/insertBtn.vue";
import { useStore } from "vuex";
import { onMounted, reactive } from "vue";

export default {
  name: "Purchase",
  components: { PurchasesTable, InsertBtn },

  setup() {
    const store = useStore();
    const state = reactive({
      searchInput: "",
      pageSize: 10,
      fromDate: null,
      toDate: null,
      showLoading: false,
      showErrorMessage: false,
      showContent: false
    });

    onMounted(() => {
      let date = new Date();
      let firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
      state.fromDate = formatDate(firstDay);
      state.toDate = new Date().toJSON().slice(0, 10);
    });

    async function reloadTable() {
      state.showLoading = true;
      try {
        await store.dispatch("FromAndToDate/SET_DATE", { fromDate: state.fromDate, toDate: state.toDate });
        if(!state.showContent) state.showContent = true;
        await store.dispatch("PurchasesTable/SELECT_DATA");
      } catch {
        state.showErrorMessage = true;
      }
      state.showLoading = false;
    }

    function formatDate(date) {
      let d = new Date(date),
        month = "" + (d.getMonth() + 1),
        day = "" + d.getDate(),
        year = d.getFullYear();

      if (month.length < 2) month = "0" + month;
      if (day.length < 2) day = "0" + day;

      return [year, month, day].join("-");
    }

    return {
      state,
      reloadTable,
    };
  },
};
</script>

<style lang="scss" scoped>
.input-group-prepend {
  user-select: none;
}
</style>
