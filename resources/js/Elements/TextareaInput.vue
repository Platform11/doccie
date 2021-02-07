<template>
    <div>
        <label
            v-if="label"
            class="block text-sm font-medium leading-5 text-gray-700 dark:text-gray-100"
            :for="id"
            >{{ label }}</label
        >
        <textarea
            :id="id"
            rows="4"
            ref="input"
            v-bind="$attrs"
            class="w-full p-2 pt-1 mt-1 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 focus:outline-none"
            :class="{ error: errors.length }"
            :value="value"
            @input="$emit('input', $event.target.value)"
        />
        <div v-if="errors.length" class="form-error">{{ errors[0] }}</div>
    </div>
</template>

<script>
import autosize from "autosize";

export default {
    inheritAttrs: false,
    props: {
        id: {
            type: String,
            default() {
                return `textarea-input-${this._uid}`;
            }
        },
        value: String,
        label: String,
        errors: {
            type: Array,
            default: () => []
        },
        autosize: {
            type: Boolean,
            default: false
        }
    },
    mounted() {
        if (this.autosize) {
            autosize(this.$refs.input);
        }
    },
    methods: {
        focus() {
            this.$refs.input.focus();
        },
        select() {
            this.$refs.input.select();
        }
    }
};
</script>
