<template>
  <img src="@/assets/img/deleteIcon.svg" alt="Delete Icon" class="serverBtn" @click="showModal = true" title="Delete Purchase" />

  <div class="modal-mask text-left" v-if="showModal" id="modal">
    <div class="modal-wrapper">
      <div class="modal-container">
        <div class="modal-header align-items-center">
          <h4 class="modal-title text-danger">Delete Purchase</h4>
          <button type="button" class="close" @click="showModal = false" style="outline: none">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          <p class="mb-4 mt-2 text-danger">Are you sure that you want to permanently delete the selected purchase?</p>
          <div class="text-right">
            <button class="btn btn-outline-danger mr-3 px-4" @click="deletePurchase()">Delete</button>
            <button class="btn btn-secondary px-4" @click="showModal = false">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, watch } from "vue";
import { useStore } from "vuex";
import { useToast } from "vue-toastification";

export default {
  name: "deleteBtn",

  props: {
    ID: {
      type: Number,
      required: true,
    },
  },

  setup(props) {
    const store = useStore();
    const toast = useToast();
    const showModal = ref(false);

    //Hide body's scrollbar if modal is opened
    watch(
      //Getter
      () => showModal.value,
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
    async function deletePurchase() {
      const YPosition = window.pageYOffset;
      try {
        toast.info("Deleting...");

        const response = await fetch(store.getters["PurchasesTable/GET_URL"] + `/delete.php?id=${props.ID}`, {
          method: "DELETE",
        });

        const message = await response.text();
        toast.success(message);
      } catch {
        toast.error("Something went wrong.");
      } finally {
        showModal.value = false;
        await store.dispatch("PurchasesTable/SELECT_DATA");
        window.scrollTo(0, YPosition);
      }
    }

    return {
      showModal,
      deletePurchase,
    };
  },
};
</script>

<style lang="scss" scoped>
.modal-body {
  margin-bottom: 0 !important;
}
</style>
