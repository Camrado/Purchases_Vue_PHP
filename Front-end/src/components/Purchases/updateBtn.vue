<template>
  <img
    src="@/assets/img/editIcon.svg"
    alt="Edit Icon"
    class="serverBtn"
    @click="getPurchaseAndSetValues()"
    title="Edit Purchase"
  />

  <div class="modal-mask text-left" v-if="state.showModal" id="purchases-modal">
    <div class="modal-wrapper">
      <div class="modal-container">
        <div class="modal-header align-items-center">
          <h4 class="modal-title text-primary">Update Purchase</h4>
          <button type="button" class="close" @click="state.showModal = false" style="outline: none">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="updatePurchase(state.modifyObject)">
            <div class="input-group">
              <div class="input-group-prepend"><label for="dateInput" class="input-group-text">Date</label></div>
              <input type="date" required id="dateInput" class="form-control" v-model="state.form[0]" />
            </div>
            <hr />
            <div class="input-group">
              <div class="input-group-prepend"><label for="categoryInput" class="input-group-text">Category</label></div>
              <select name="categoryInput" id="categoryInput" v-model="state.form[1]" class="custom-select" required>
                <option :value="category.name" v-for="category in state.categories" :key="category.ID">
                  {{ category.name }}
                </option>
              </select>
            </div>
            <hr />
            <div class="input-group">
              <div class="input-group-prepend"><label for="itemInput" class="input-group-text">Product name</label></div>
              <input
                type="text"
                required
                name="ProductName"
                id="itemInput"
                pattern="[a-zA-Z][a-zA-Z ]+"
                class="form-control"
                :class="state.invalidClassName"
                v-model="state.form[2]"
              />
              <div class="invalid-feedback">Allowed only letters/spaces</div>
            </div>
            <hr />
            <div class="input-group">
              <div class="input-group-prepend"><label for="priceInput" class="input-group-text">Price of one piece</label></div>
              <input
                type="number"
                step="0.01"
                name="PriceOfOne"
                pattern="/^\d+(?:,\d{1,2})*$/"
                min="0.01"
                required
                id="priceInput"
                class="form-control"
                :class="state.invalidClassPrice"
                v-model="state.form[3]"
              />
              <div class="invalid-feedback">Allowed only positive numbers (with maximum 2 digits after decimal point)</div>
            </div>
            <hr />
            <div class="input-group">
              <div class="input-group-prepend"><label for="quantityInput" class="input-group-text">Quantity</label></div>
              <input
                type="number"
                required
                name="Quantity"
                step="1"
                pattern="^[1-9]+[0-9]*$"
                min="1"
                id="quantityInput"
                class="form-control"
                :class="state.invalidClassQty"
                v-model="state.form[4]"
              />
              <div class="invalid-feedback">Allowed only positive integers</div>
            </div>

            <div class="text-right button-container">
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
import { computed, reactive, watch } from 'vue';
import { useStore } from 'vuex';
import { useToast } from 'vue-toastification';

export default {
  name: 'updateBtn',

  props: {
    ID: {
      type: Number,
      required: true
    }
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
          date: state.form[0],
          category: state.form[1],
          item: state.form[2],
          price: state.form[3],
          quantity: state.form[4],
          total_price: 0
        };
      }),
      invalidClassName: {
        'is-invalid': false
      },
      invalidClassPrice: {
        'is-invalid': false
      },
      invalidClassQty: {
        'is-invalid': false
      },
      categories: computed(() => store.getters['CategoriesTable/GET_CONTENT'])
    });

    async function getPurchaseAndSetValues() {
      const response = await fetch(store.getters['PurchasesTable/GET_URL'] + `/read_single.php?id=${props.ID}`, {
        method: 'GET'
      });
      const purchase = await response.json();

      state.form[0] = purchase.date;
      state.form[1] = purchase.category;
      state.form[2] = purchase.item;
      state.form[3] = purchase.price;
      state.form[4] = purchase.quantity;

      state.showModal = true;
    }

    //Hide body's scrollbar if modal is opened
    watch(
      //Getter
      () => state.showModal,
      //Callback
      (newShowModal) => {
        if (newShowModal) {
          document.getElementsByTagName('body')[0].style = 'overflow-y: hidden;';
        } else {
          document.getElementsByTagName('body')[0].style = 'overflow-y: scroll;';
        }
      }
    );

    //? fetch function
    async function updatePurchase(jsonObject) {
      const YPosition = window.pageYOffset;
      try {
        toast.info('Updating...');

        const response = await fetch(store.getters['PurchasesTable/GET_URL'] + '/update.php', {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(jsonObject)
        });

        const message = await response.text();
        toast.success(message);
      } catch {
        toast.error('Something went wrong');
      } finally {
        state.showModal = false;
        await store.dispatch('PurchasesTable/SELECT_DATA');
        window.scrollTo(0, YPosition);
      }
    }

    //Regex for state.form[2] - Product name
    watch(
      () => state.form[2],
      (newInput) => {
        if (!/^[a-zA-Z\s]*$/.test(newInput) && newInput) state.invalidClassName['is-invalid'] = true;
        else state.invalidClassName['is-invalid'] = false;
      }
    );

    //Regex for state.form[3] - Price for one piece
    watch(
      () => state.form[3],
      (newInput) => {
        if (!/^(?!0*[.,]0*$|[.,]0*$|0*$)\d+[,.]?\d{0,2}$/.test(newInput) && newInput)
          state.invalidClassPrice['is-invalid'] = true;
        else state.invalidClassPrice['is-invalid'] = false;
      }
    );

    //Regex for state.form[4] - Quantity
    watch(
      () => state.form[4],
      (newInput) => {
        if (!/^[1-9]\d*$/.test(newInput) && newInput) state.invalidClassQty['is-invalid'] = true;
        else state.invalidClassQty['is-invalid'] = false;
      }
    );

    return {
      state,
      updatePurchase,
      getPurchaseAndSetValues
    };
  }
};
</script>

<style lang="scss" scoped>
label {
  font-size: 17px;
}
.form-text {
  font-size: 14px;
}
.input-group-text {
  width: 170px;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
