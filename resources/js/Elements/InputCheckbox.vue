<template>
    <div>
        <div class="relative flex items-start">
            <div class="flex items-center h-5">
                <input
                    :id="id"
                    :name="name"
                    type="checkbox"
                    ref="input"
                    v-bind="$attrs"
                    v-model="checked"
                    :checked="checked"
                    class="w-4 h-4 border-gray-300 rounded cursor-pointer text-primary form-checkbox"
                    :class="{ error: errors.length }"
                    @change="$emit('change', $event.target.value)"
                />
            </div>
            <div class="ml-2 text-sm">
                <label :for="name" class="font-medium text-gray-700">{{
                    label
                }}</label>
            </div>
        </div>
        <div v-if="errors.length" class="form-error">{{ errors[0] }}</div>
    </div>
</template>

<script>
export default {
    inheritAttrs: false,
    props: {
        id: {
            type: String,
            default() {
                return `input-checkbox-${this._uid}`;
            }
        },
        value: String,
        checkValue: Boolean,
        label: String,
        name: String,
        errors: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            checked: this.checkValue
        };
    },
    mounted() {},
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
