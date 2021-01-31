import { createStore } from "vuex";

import { PurchasesTable } from "@/store/modules/PurchasesTable";
import { CategoriesTable } from "@/store/modules/CategoriesTable";
import { FromAndToDate } from "@/store/modules/FromAndToDate";

export default createStore({
  state: {},
  mutations: {},
  actions: {},
  getters: {},
  modules: { PurchasesTable, FromAndToDate, CategoriesTable },
});
