<template>
  <img
    src="@/assets/img/editIcon.svg"
    alt="Edit Icon"
    class="serverBtn"
    @click="getPurchaseAndSetValues()"
    title="Edit Category"
  />

  <div class="modal-mask text-left" v-if="state.showModal" id="categories-modal">
    <div class="modal-wrapper">
      <div class="modal-container">
        <div class="modal-header align-items-center">
          <h4 class="modal-title text-primary">Update Category</h4>
          <button type="button" class="close" @click="state.showModal = false" style="outline: none">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="updateCategory(state.modifyObject)">
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
              <input type="submit" value="Update" class="btn btn-outline-primary mr-3 px-4" />
              <button class="btn btn-secondary px-4" @click="state.showModal = false">Cancel</button>
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
  name: "updateBtn",

  props: {
    ID: {
      type: Number,
      required: true,
    },
  },

  setup(props) {
    const store = useStore();
    const toast = useToast();
    const state = reactive({
      form: [],
      showModal: false,
      //? Modify state.form into a ready object for the body of fetch
      modifyObject: computed(() => {
        return {
          ID: props.ID,
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

    async function getPurchaseAndSetValues() {
      const response = await fetch(store.getters["CategoriesTable/GET_URL"] + `/read_single.php?id=${props.ID}`, {
        method: "GET",
      });
      const purchase = await response.json();

      state.form[0] = purchase.name;
      state.form[1] = purchase.description;

      state.showModal = true;
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
    async function updateCategory(jsonObject) {
      const YPosition = window.pageYOffset;
      try {
        toast.info("Updating...");

        const response = await fetch(store.getters["CategoriesTable/GET_URL"] + "/update.php", {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(jsonObject),
        });

        const message = await response.text();
        toast.success(message);
      } catch {
        toast.error("Something went wrong");
      } finally {
        state.showModal = false;
        await store.dispatch("CategoriesTable/SELECT_DATA");
        window.scrollTo(0, YPosition);
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
      updateCategory,
      getPurchaseAndSetValues,
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
