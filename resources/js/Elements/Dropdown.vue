<template>
    <div class="relative">
        <div class="flex items-center justify-end h-8">
            <div class="">
                <button
                    class="flex items-center text-gray-800 hover:text-gray-900 focus:outline-none focus:text-gray-900"
                    @click="test()"
                >
                    <svg
                        class="w-5 h-5"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"
                        />
                    </svg>
                </button>
            </div>
        </div>
        <Transition
            enter-active-class="transition duration-100 ease-out"
            enter-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <div
                class="absolute right-0 z-30 w-48 mt-0 origin-top-right rounded-md shadow-lg"
                v-show="isOpen"
            >
                <div class="bg-white rounded-md shadow-xs ">
                    <div
                        class="py-1"
                        v-for="(item, index) in items"
                        v-bind:key="index"
                    >
                        <button
                            @click="$emit(item.action)"
                            class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:cursor-pointer focus:outline-none"
                        >
                            {{ item.title }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script>
export default {
    props: {
        items: Array
    },

    data() {
        return {
            isOpen: false
        };
    },

    methods: {
        close() {
            if (this.isOpen) {
                this.isOpen = false;
            }
        },

        test() {
            this.isOpen = !this.isOpen;
            console.log(this.isOpen);
        }
    }
};
</script>
