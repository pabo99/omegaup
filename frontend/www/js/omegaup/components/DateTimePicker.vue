<template>
  <input class="form-control"
        readonly
        size="16"
        type="text">
</template>

<script>
import {T} from '../omegaup.js';
export default {
  props: {
    value: Date,
    format: {
      type: String,
      'default': T.dateTimePickerFormat,
    },
    start: {type: Date, 'default': null},
    finish: {type: Date, 'default': null},
  },
  data: function() { return {};},
  watch: {
    value: function(val) { $(this.$el)
                               .data('datetimepicker')
                               .setDate(val);},
  },
  mounted: function() {
    var self = this;
    $(self.$el)
        .datetimepicker({
          format: self.format,
          defaultDate: self.value,
          language: T.locale,
        })
        .on('change', function(e) {
          self.$emit('input', $(self.$el).data('datetimepicker').getDate());
        });
    $(this.$el).data('datetimepicker').setDate(this.value);
    if (this.start != null) {
      $(this.$el).data('datetimepicker').setStartDate(this.start);
    }
    if (this.finish != null) {
      $(this.$el).data('datetimepicker').setEndDate(this.finish);
    }
  },
};
</script>
