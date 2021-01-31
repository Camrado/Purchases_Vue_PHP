<template>
  <button class="btn btn-success btn-block d-flex align-items-center justify-content-around" @click="state.showModal = true">
    <img src="@/assets/img/addIcon.svg" alt="Add Icon" style="height: 14px;" />
    <span>Add Category</span>
  </button>

  <div class="modal-mask text-left" v-if="state.showModal" id="categories-modal">
    <div class="modal-wrapper">
      <div class="modal-container">
        <div class="modal-header align-items-center">
          <h4 class="modal-title text-success">Add Category</h4>
          <button
            type="button"
            class="close"
            @click="
              state.showModal = false;
              ClearForm();
            "
            style="outline: none"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="insertCategory(state.modifyObject)">
            <div class="form-group">
              <label for="CategoryName">Category name</label>
              <input
                type="text"
                required
                name="CategoryName"
                id="CategoryName"
                pattern="[a-zA-Z][a-zA-Z ]+"
                class="form-control"
                :class="state.invalidClassName"
                v-model="state.form[0]"
              />
              <div class="invalid-feedback">Allowed only letters/spaces</div>
            </div>
            <hr />
            <div class="form-group">
              <label for="CategoryDescription">Category Description</label>
              <input
                type="text"
                required
                name="CategoryDescription"
                id="CategoryDescription"
                pattern="^[a-zA-Z0-9,.!? ]*$"
                class="form-control"
                :class="state.invalidClassDescription"
                v-model="state.form[1]"
              />
              <div class="invalid-feedback">Allowed only letters/spaces/numbers/full stops/commas</div>
            </div>

            <div class="text-right">
              <input type="submit" value="Insert" class="btn btn-outline-success mr-3 px-5" />
              <button
                class="btn btn-secondary px-4"
                @click="
                  state.showModal = false;
                  ClearForm();
                "
              >
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { computed, reactive, watch } from "vue";
import { useStore } from "vuex";
import { useToast } from "vue-toastification";

export default {
  name: "insertBtn",

  setup() {
    const store = useStore();
    const toast = useToast();
    const state = reactive({
      form: [],
      showModal: false,
      //? Modify state.form into a ready object for the body of fetch
      modifyObject: computed(() => {
        return {
          ID: 0,
          name: state.form[0],
          description: state.form[1],
        };
      }),
      invalidClassName: {
        "is-invalid": false,
      },
      invalidClassDescription: {
        "is-invalid": false,
      },
    });

    //? Function for clearing form after submit
    function ClearForm() {
      state.form = [];
    }

    //Hide body's scrollbar if modal is opened
    watch(
      //Getter
      () => state.showModal,
      //Callback
      (newShowModal) => {
        if (newShowModal) {
          document.getElementsByTagName("body")[0].style = "overflow-y: hidden;";
        } else {
          document.getElementsByTagName("body")[0].style = "overflow-y: scroll;";
        }
      }
    );

    //? fetch function
    async function insertCategory(jsonObject) {
      try {
        toast.info("Adding...");

        const response = await fetch(store.getters["CategoriesTable/GET_URL"] + "/insert.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(jsonObject),
        });

        const message = await response.text();
        toast.success(message);
      } catch {
        toast.error("Something went wrong.");
      } finally {
        state.showModal = false;
        ClearForm();
        store.dispatch("CategoriesTable/SELECT_DATA");
      }
    }

    //Regex for state.form[0] - Category name
    watch(
      () => state.form[0],
      (newInput) => {
        if (!/^[a-zA-Z\s]*$/.test(newInput) && newInput) state.invalidClassName["is-invalid"] = true;
        else state.invalidClassName["is-invalid"] = false;
      }
    );

    //Regex for state.form[1] - Category description
    watch(
      () => state.form[1],
      (newInput) => {
        if (!/^[a-zA-Z0-9,.!? ]*$/.test(newInput) && newInput) state.invalidClassDescription["is-invalid"] = true;
        else state.invalidClassDescription["is-invalid"] = false;
      }
    );

    return {
      state,
      ClearForm,
      insertCategory,
    };
  },
};
</script>

<style lang="scss" scoped>
label {
  font-size: 17px;
}
.form-text {
  font-size: 14px;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
