<template>
  <div class="container mt-4">
    <div class="row align-items-center">
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
        <CategoriesTable :searchInput="state.searchInput" :pageSizeProp="state.pageSize" />
      </div>
    </div>
  </div>
</template>

<script>
import CategoriesTable from "@/components/Categories/CategoriesTable.vue";
import InsertBtn from "@/components/Categories/insertBtn.vue";
import { reactive } from "vue";

export default {
  name: "Categories",
  components: { CategoriesTable, InsertBtn },

  setup() {
    const state = reactive({
      searchInput: "",
      pageSize: 10,
    });

    return {
      state,
    };
  },
};
</script>

<style lang="scss" scoped>
.input-group-prepend {
  user-select: none;
}
</style>
