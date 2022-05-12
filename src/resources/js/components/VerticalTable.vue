<script setup lang="ts">
import { PropType } from 'vue'

const props = defineProps({
  columnNames: {
    type: Array as PropType<Array<String>>,
    default: (): Array<String> => [],
  },
});
</script>

<template>
  <table class="c-vertical-table">
    <tr v-for="(columnName, index) in columnNames" class="c-vertical-table__row">
      <th class="c-vertical-table__row-header">{{ columnName }}</th>
      <td class="c-vertical-table__row-data">
        <slot :name="'row' + (index + 1)"></slot>
      </td>
    </tr>
  </table>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.c-vertical-table {
  border: #dcc090 2px solid;
  border-collapse: collapse;
  width: 100%;
}

.c-vertical-table__row {
  width: 100%;
}

.c-vertical-table__row-header {
  border: #dcc090 2px solid;
  color: #dcc090;
  vertical-align: middle;

  @include mixins.mq(sp) {
    padding: 10px;
    width: 30%;
  }

  @include mixins.mq(tablet) {
    padding: 15px;
    width: 20%;
  }

  @include mixins.mq(pc) {
    padding: 20px;
    width: 20%;
  }
}

.c-vertical-table__row-data {
  background-color: #fff;
  border: #dcc090 2px solid;
  vertical-align: middle;

  @include mixins.mq(sp) {
    padding: 10px;
  }

  @include mixins.mq(tablet) {
    padding: 15px;
  }

  @include mixins.mq(pc) {
    padding: 20px;
  }
}
</style>
