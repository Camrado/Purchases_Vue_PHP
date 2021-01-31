export const FromAndToDate = {
  namespaced: true, //? If it's true, we can have the same states in other modules, and there will be no error.

  state: {
    fromAndTo: {
      fromDate: "2020-12-17",
      toDate: "2021-01-09",
    },
  },

  mutations: {
    SET_DATE_MUTATION(state, { fromDate, toDate }) {
      state.fromAndTo.fromDate = fromDate;
      state.fromAndTo.toDate = toDate;
    },
  },

  actions: {
    SET_DATE({ commit }, fromAndToDate) {
      commit("SET_DATE_MUTATION", fromAndToDate);
    },
  },

  getters: {
    GET_DATE: (state) => state.fromAndTo,
  },
};
