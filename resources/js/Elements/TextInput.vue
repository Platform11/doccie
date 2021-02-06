<template>
    <div>
        <label
            v-if="label"
            class="block text-sm font-medium leading-5 text-gray-700 dark:text-gray-100"
            :for="id"
            >{{ label }}</label
        >
        <div class="">
            <input
                :id="id"
                ref="input"
                v-bind="$attrs"
                class="w-full px-3 py-2 mt-1 text-gray-900 bg-white border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-300 focus:outline-none sm:text-sm sm:leading-5"
                :class="{ error: error }"
                :type="type"
                :value="value"
                @input="$emit('input', $event.target.value)"
            />
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
            type: String,
            default() {
                return `text-input-${this._uid}`;
            }
        },
        type: {
            type: String,
            default: "text"
        },
        value: String | Number,
        label: String,
        error: String
    },
    methods: {
        focus() {
            this.$refs.input.focus();
        },
        select() {
            this.$refs.input.select();
        },
        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end);
        }
    }
};
</script>
