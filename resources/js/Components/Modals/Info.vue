<template>
    <modal :show="show" @close="close()" class="z-50 max-w-xl m-2 sm:mx-auto">
        <div
            class="relative z-10 max-w-xl mx-auto overflow-hidden rounded sm:rounded-lg"
        >
            <div class="relative z-10 flex justify-center w-full">
                <div
                    class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="modal-headline"
                >
                    <div
                        v-if="type == 'success'"
                        class="flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full"
                    >
                        <!-- Heroicon name: check -->
                        <svg
                            class="w-6 h-6 text-primary"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>
                    </div>
                    <div
                        v-if="type == 'info'"
                        class="flex items-center justify-center w-12 h-12 mx-auto bg-blue-100 rounded-full"
                    >
                        <!-- Heroicon name: exclamation -->
                        <svg
                            class="w-6 h-6 text-blue-600"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div
                        v-if="type == 'error'"
                        class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full"
                    >
                        <!-- Heroicon name: exclamation -->
                        <svg
                            class="w-6 h-6 text-red-600"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                            />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3
                            class="text-lg font-medium leading-6 text-gray-900"
                            id="modal-headline"
                        >
                            {{ title }}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-600">
                                {{ message }}
                            </p>
                            <div
                                class="mt-3"
                                v-if="additionalHtml != ''"
                                v-html="additionalHtml"
                            ></div>
                        </div>
                    </div>

                    <div class="mt-5 sm:mt-6">
                        <button
                            @click="close()"
                            type="button"
                            class="relative inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out rounded-md btn btn-primary ring-2 ring-opacity-0 ring-primary hover:ring-opacity-40 bg-primary hover:bg-primary-light focus:outline-none active:bg-primary-dark sm:leading-5"
                        >
                            {{ confirm_text }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </modal>
</template>

<script>
import Modal from "@/Components/Modals/Base";

export default {
    components: {
        Modal
    },

    props: {
        show: Boolean,
        type: String,
        title: String,
        message: String,
        confirm_text: String,
        additionalHtml: String
    },

    methods: {
        close: function() {
            this.$emit("close");
        }
    }
};
</script>
