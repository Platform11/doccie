<template>
    <div
        v-show="show"
        class="fixed inset-0 z-40 sm:inset-0 sm:p-0 sm:flex sm:items-center sm:justify-center"
    >
        <Transition
            enter-active-class="transition-opacity duration-300 ease-out"
            enter-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-300 ease-in"
            leave-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                class="fixed inset-0 z-10 w-full"
                v-show="show"
                @click="close()"
            >
                <div class="absolute inset-0 z-10 bg-black opacity-75"></div>
            </div>
        </Transition>

        <Transition
            enter-active-class="duration-300 ease-out"
            enter-class="transform translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
            enter-to-class="transform translate-y-0 opacity-100 sm:scale-100"
            leave-active-class="duration-300 ease-in"
            leave-class="transform translate-y-0 opacity-100 sm:scale-100"
            leave-to-class="transform translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
        >
            <div
                v-show="show"
                class="z-50 w-full dark:bg-gray-900 sm:p-0 standalone:mt-10"
                @click.stop=""
            >
                <slot></slot>
            </div>
        </Transition>
    </div>
</template>

<script>
export default {
    props: {
        show: Boolean
    },

    mounted: function() {
        document.addEventListener("keydown", e => {
            if (this.show && e.keyCode === 27) {
                this.close();
            }
        });
    },

    methods: {
        close: function() {
            this.$emit("close");
        }
    }
};
</script>
