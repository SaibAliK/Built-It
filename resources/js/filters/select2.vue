
<template>
  <select>
    <slot></slot>
  </select>
</template>


<script>

export default {
  name: "select2",
  props: ["options", "value", 'placeHolder', 'minimumResultsForSearch','allowClear'],
  data() {
    return {

    };
  },
  mounted() {
    this.setSelect();
  },
  methods: {
   setSelect(){
     let vm = this;
     let minimumResultsForSearch = -1;
     let allowClear = false;
     if (vm.minimumResultsForSearch !== undefined){
       minimumResultsForSearch = vm.minimumResultsForSearch;
     }
     if (vm.allowClear !== undefined){
       allowClear = vm.allowClear;
     }

     setTimeout(()=> {
       $(this.$el)
           // init select2
           .select2({
             placeholder: this.placeHolder,
             allowClear: allowClear,
             minimumResultsForSearch:minimumResultsForSearch,
             data: this.options
           })
           .val(this.value)
           .trigger("change")
           // emit event on change.
           .on("change", function () {
             vm.$emit("input", this.value);
           });
     },300);
   }
  },
  watch: {
    value: function(value) {
      // update value
      $(this.$el)
          .val(value)
          .trigger("change");
    },
    options: function(options) {
      if (options.length == 0){
        this.value = '';
      }
      if (options[0] !== undefined) {
        if (options[0].id !== '') {
          options.unshift({id: '', text: this.placeHolder});
        }
      }
      let minimumResultsForSearch = -1;
      if (this.minimumResultsForSearch !== undefined){
        minimumResultsForSearch = this.minimumResultsForSearch;
      }
      $(this.$el)
          .empty()
          .select2({placeholder: this.placeHolder,allowClear: true, data: options, minimumResultsForSearch:minimumResultsForSearch });
    }
  },
  destroyed: function() {
    $(this.$el)
        .off()
        .select2("destroy");
  }

};
</script>



<style scoped>

</style>
