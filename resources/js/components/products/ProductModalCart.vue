<template>
  <div id="add-cart" class="modal-add-cart modal fade">
    <div class="modal-dialog">
      <div class="modal-content text-center">
        <div class="modal-body">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <div class="row item-row">
            <div class="col-sm-4">
              <div class="item-img">
                <img :src="product.main_image_path" alt>
              </div>
            </div>
            <div class="col-sm-8">
              <p class="item-name">{{ product.name }}</p>
              <div v-if="product.offer">
                <p class="old-price">
                  {{ this.product.currncy_name }}
                  <span
                    id="product_old_price_with_offer"
                  >{{ product.price * this.product.currency_exchange_rate }}</span>
                </p>
                <p class="price">
                  {{ this.product.currncy_name }}
                  <span
                    id="product_price"
                  >{{ productPrice * this.product.currency_exchange_rate }}</span>
                </p>
              </div>
              <div v-else>
                <p class="price">
                  {{ this.product.currncy_name }}
                  <span
                    id="product_price"
                  >{{ productPrice * this.product.currency_exchange_rate }}</span>
                </p>
              </div>
              <div v-if="product.options">
                <div class="row size-qty" v-for="option in product.options" :key="option.key">
                  <div class="col-xs-6 col-md-5">
                    <span class="size-text">{{option.name}}</span>
                  </div>
                  <div v-for="option_value in product.optionvalues" :key="option_value.key">
                    <div class="col-xs-6 col-md-2" v-if="option_value.option_id == option.id">
                      <!-- <span class="size-value">{{option_value.value_en}}</span> -->
                      <label class="btn size">
                        <input
                          class="input_option_price"
                          @change="calcPrice()"
                          :type="option.type"
                          :value="option_value.id"
                          :name="option.id"
                          :data-option-value-id="option_value.id"
                          :data-option-id="option.id"
                        >
                        {{ option_value.value }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <span class="error" v-if="optionError">{{optionErrorMessage}}</span>
              <div class="row size-qty" v-if="!disabledQuantity">
                <div class="col-xs-6 col-md-5">
                  <p class="quantity text-left">
                    {{ $t('website.qty_label')}}
                    <span>&lpar;{{ $t('website.limit_label')}} {{ product.maxima_order ? product.maxima_order : product.quantity}}&rpar;</span>
                  </p>
                </div>
                <div class="col-xs-6 col-md-2">
                  <div class="input-group counter pull-right">
                    <span class="input-group-btn">
                      <button
                        @click="quantityDec()"
                        type="button"
                        class="btn btn-number"
                        data-type="minus"
                        data-field
                      >
                        <span class="glyphicon glyphicon-minus"></span>
                      </button>
                    </span>
                    <input
                      readonly
                      type="text"
                      id="quantity"
                      v-model="productQuantity"
                      name="quantity"
                      class="form-control input-number"
                    >
                    <span class="input-group-btn">
                      <button
                        @click="quantityInc()"
                        type="button"
                        class="btn btn-number"
                        data-type="plus"
                        data-field
                      >
                        <span class="glyphicon glyphicon-plus"></span>
                      </button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <button
                data-dismiss="modal"
                class="btn text-uppercase form-control"
              >{{ $t('website.continue_shopping_label')}}</button>
            </div>
            <div class="col-sm-6">
              <a
                href="#"
                @click.prevent="addToCart(product)"
                type="button"
                class="check-out btn text-uppercase form-control"
              >{{ $t(buttonName) }}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    product: {
      type: Object,
      required: true
    },
    buttonName: {
      default: "website.add_to_shopping_bag_label",
      type: String,
      required: false
    },
    disabledQuantity: {
      default: false,
      type: Boolean,
      required: false
    },
    isGroupProducts: {
      default: false,
      type: Boolean,
      required: false
    },
    groupProducts: {
      type: Object,
      required: false
    }
  },
  data() {
    return {
      checkedOptions: [],
      productPrice: 0,
      productQuantity: 1,
      errorCount: 0,
      optionError: false,
      optionErrorMessage: null
    };
  },
  created() {
    this.setProductPrice(this.product.price);
  },
  methods: {
    addToCart(product) {
      this.calcPrice();

      // handel errors
      this.handleErrors();
      if (this.errorCount > 0) {
        return;
      }

      let data = {
        product: product,
        options: this.checkedOptions,
        price: this.productPrice,
        quantity: this.productQuantity
      };

      if (this.isGroupProducts) {
        data["groupProducts"] = this.groupProducts;

        this.$store.commit("addGroupProducts", data);
      } else {
        this.$store.commit("addToCart", data);
      }

      this.showAlert(this.$t("website.added_to_cart_message"));
    },
    quantityDec() {
      if (this.productQuantity > 1) {
        this.productQuantity--;
      }
    },
    quantityInc() {
      let maxima_order = this.product.maxima_order
        ? this.product.maxima_order
        : this.product.quantity;
      if (this.productQuantity < maxima_order) {
        this.productQuantity++;
      }
    },
    handleErrors() {
      this.optionError = false;
      this.errorCount = 0;

      if (this.checkedOptions.length != this.product.options.length) {
        this.optionError = true;
        this.optionErrorMessage = this.$t("website.option_error_message");
        this.errorCount += 1;
      }
    },
    setProductPrice(price) {

      this.productPrice = this.product.offer
        ? price - this.product.offer.fixed
        : price;
    },
    showAlert(message) {
      this.$swal({
        type: "success",
        title: message,
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000
      });
    },
    calcPrice() {
      // defined local checkedOption to save checked optoins inside jQuery each
      let checkedOptions = [];
      // normal jQuery each we are useing jQuery each becuse we need the option sorted asc
      $(".input_option_price").each(function() {
        if ($(this).is(":checked")) {
          checkedOptions.push($(this).data("option-value-id"));
        }
      });
      // delete all data from global checkedOptions
      this.checkedOptions = [];
      // global checkedOptions = local checkedOptions
      this.checkedOptions = checkedOptions;

      let price = 0;
      // check if product option equal checked options
      if (
        this.product.options.length > 0 &&
        this.checkedOptions.length == this.product.options.length
      ) {
        let priceCombination = this.product.optionvalues[0].pivot
          .price_combination;
        if (priceCombination != "" && priceCombination != null) {
          priceCombination = JSON.parse(priceCombination)["options"];
          priceCombination =
            priceCombination[this.checkedOptions.join("_")]["price"];
        } else {
          priceCombination = 0;
        }
        price = priceCombination == 0 ? this.product.price : priceCombination;
      } else {
        price = this.product.price;
      }
      this.setProductPrice(price);
    }
  }
};
</script>
