<template>
<div></div>
</template>
<script>
export default {
  props: {

    categories: {
      type: Array,
      required: true
    },
      vendors: {
      type: Array,
      required: true
  },
      selected_categories:
          {
              type: Array,
          }
  },
  data() {
    return {
      filterQuery: [],
        lang: this.$store.getters.lang,
    };
  },
  created() {
    this.getUrlParameters();

    // this.locale = this.locale;
    // this.setPriceRange();
  },

  methods: {
    setFilterQuery: function(name, element) {

        let uri = window.location.href.split("?");
        let getVars =  null;

        if (uri.length == 2) {
            let vars = uri[1].split("&");
            let tmp = "";
            vars.forEach(function (v) {

                tmp = v.split("=");

                if (tmp.length == 2) {
                    if (tmp[0] == 'categories') {

                        getVars = (tmp[1]);
                    } else {
                        getVars = (tmp[1]);
                    }
                }
            });

            // this.$store.filterQuery = getVars;
        }

      if (this.$store.filterQuery[name]) {
          if (this.$store.filterQuery[name].includes(parseInt(getVars))  ==true ) {
              this.$store.filterQuery[name].splice(this.$store.filterQuery[name].indexOf(parseInt(getVars)), 1);
          }
        if (this.$store.filterQuery[name].includes(parseInt(element.id))  ==true ) {
           this.$store.filterQuery[name].splice(this.$store.filterQuery[name].indexOf(parseInt(element.id)),1);
        } else {
          this.$store.filterQuery[name].push(parseInt(element.id));
        }

      } else {
        if (this.$store.filterQuery.indexOf(name) == -1) {
          this.$store.filterQuery[name] = [];
        }
        this.$store.filterQuery[name].push(parseInt(element.id));

      }


    if(this.$store.filterQuery[name].length<1) {

        let uri = window.location.href.split("?");
        let getVars = [];

        if (uri.length == 2) {
            let vars = uri[1].split("&");
            let tmp = "";
            vars.forEach(function (v) {

                tmp = v.split("=");

                if (tmp.length == 2) {
                    if (tmp[0] == 'categories') {
                        getVars[tmp[0]] = [];
                        getVars[tmp[0]].push(parseInt(tmp[1]));
                    } else {
                        getVars[tmp[0]] = [];
                        getVars[tmp[0]].push((tmp[1]));
                    }
                }
            });
            this.$store.filterQuery = getVars;
        }
    }
      this.getFilterData();
    },

    getFilterData() {
      this.$emit("fetchProducts", this.$store.filterQuery);
    },
    getUrlParameters() {

      let uri = window.location.href.split("?");
      let getVars = [];

      if (uri.length == 2) {
        let vars = uri[1].split("&");
        let tmp = "";
        vars.forEach(function(v) {

          tmp = v.split("=");

          if (tmp.length == 2 ) {
              if(tmp[0] =='categories')
              {
                  getVars[tmp[0]] = [];
                  getVars[tmp[0]].push(parseInt(tmp[1]));
              }
              else {
                  getVars[tmp[0]] = [];
                  getVars[tmp[0]].push((tmp[1]));
              }
          }
        });
        this.$store.filterQuery = [];
        //
        // this.getFilterData();
      }
    },
    setPriceRange() {
      // setTimeout(() => {
      //   $("#amountfrm").val(Math.floor(this.min_price));
      //   // $("#slider-range").slider("option", "min", Math.floor(this.min_price));
      //   $("#amountto").val(Math.floor(this.max_price));
      //   $("#slider-range").slider("option", "max", Math.floor(this.max_price));
      // }, 1000);
    },
    getPriceRange() {
      // this.filterQuery["price_from"] = $("#amountfrm").val();
      // this.filterQuery["price_to"] = $("#amountto").val();
      // this.getFilterData();
    }
  }
};
</script>
