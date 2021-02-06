<template>
  <div>
    <label
      v-if="label"
      class="block text-sm font-medium leading-5 text-gray-700 dark:text-gray-100"
      :for="id"
      >{{ label }}</label
    >
    <div class="w-full mt-1 rounded-md shadow-sm">
      <select
        :id="id" 
        ref="input" 
        v-model="selected" 
        v-bind="$attrs"
        class="block w-full truncate transition duration-150 ease-in-out form-select sm:text-sm sm:leading-5 focus:ring-2 focus:ring-blue-100 focus:border-blue-300 focus:outline-none"
      >
      <slot></slot>
      </select>
    </div>
        <div v-if="error" class="mt-1 text-xs text-danger">
            {{ error }}
        </div>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,
  props: {
    id: {
      default() {
        return `text-input-${this._uid}`;
      },
    },
    value: String | Number,
    label: String,
    error: {
      type: String,
    },
  },
  data() {
    return {
      selected: this.value,
    }
  },
  watch: {
    selected(selected) {
      this.$emit('input', selected)
    },
  },
  methods: {
    focus() {
      this.$refs.input.focus()
    },
    select() {
      this.$refs.input.select()
    },
  },
}
</script>
};
</script>
