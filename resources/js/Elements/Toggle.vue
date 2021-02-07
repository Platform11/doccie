<template>
    <button
        type="button"
        @click="toggle"
        :aria-pressed="toggledString"
        class="relative inline-flex flex-shrink-0 w-10 h-6 transition-colors duration-200 ease-in-out border-2 border-transparent rounded-full outline-none cursor-pointer"
        :class="isToggled ? 'bg-primary border-primary' : 'bg-gray-200'"
    >
        <span class="sr-only">Approve check</span>
        <span
            aria-hidden="true"
            class="inline-block w-5 h-5 transition duration-200 ease-in-out transform bg-white rounded-full shadow ring-0"
            :class="isToggled ? 'translate-x-4' : 'translate-x-0'"
        ></span>
    </button>
</template>
<script>
export default {
    props: {
        isToggled: Boolean
    },

    computed: {
        toggled: function() {
            return this.isToggled;
        },

        toggledString: function() {
            if (!this.isToggled) {
                return "false";
            }
            return "true";
        }
    },

    methods: {
        toggle() {
            this.$emit("toggle");
        }
    }
};
</script>
