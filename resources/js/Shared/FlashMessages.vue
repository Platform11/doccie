<template>
    <div
        class="fixed inset-0 flex items-end justify-center px-4 py-4 pointer-events-none sm:p-3 sm:items-start sm:justify-center"
        style="z-index: 1000;"
    >
        <Transition
            enter-active-class="transition duration-100 ease-out"
            enter-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <div
                v-if="$page.props.flash.success && show"
                class="w-full max-w-sm bg-white rounded-lg shadow-lg pointer-events-auto dark:bg-gray-800"
            >
                <div class="overflow-hidden rounded-lg shadow-xs">
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div
                                    class="text-green-500"
                                    style="padding-top: 0.1rem;"
                                >
                                    <svg
                                        width="16"
                                        height="16"
                                        fill="currentColor"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M8 0a8 8 0 110 16A8 8 0 018 0zm3.17 5.293l-3.524 3.29-1.81-1.69a1.12 1.12 0 00-1.037-.267 1.038 1.038 0 00-.764.715.957.957 0 00.293.972l2.562 2.394c.201.188.474.294.758.293.283 0 .556-.105.757-.293l4.281-3.999a.955.955 0 000-1.415 1.126 1.126 0 00-1.515 0z"
                                            fill-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 w-0 ml-3">
                                <p
                                    class="leading-5 text-gray-800 dark:text-gray-200"
                                >
                                    {{ $page.props.flash.success }}
                                </p>
                            </div>
                            <div class="flex flex-shrink-0 ml-4">
                                <button
                                    class="inline-flex text-gray-400 transition duration-150 ease-in-out focus:outline-none focus:text-gray-500"
                                    @click="show = false"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
        <Transition
            enter-active-class="transition duration-100 ease-out"
            enter-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <div
                v-if="
                    ($page.props.flash.error ||
                        Object.keys($page.props.errors).length > 0) &&
                        show
                "
                class="w-full max-w-sm bg-white rounded-lg shadow-lg pointer-events-auto dark:bg-gray-800"
            >
                <div class="overflow-hidden rounded-lg shadow-xs">
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div
                                    class="text-danger"
                                    style="padding-top: 0.1rem;"
                                >
                                    <svg
                                        width="16"
                                        height="16"
                                        fill="currentColor"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M8 0a8 8 0 110 16A8 8 0 018 0zm1.864 5.308l-1.592 1.59-1.591-1.59a.97.97 0 00-1.373 0l-.071.079a.971.971 0 00.07 1.294L6.9 8.27 5.308 9.865a.97.97 0 000 1.373l.079.07a.971.971 0 001.294-.07l1.591-1.592 1.592 1.592a.97.97 0 001.373 0l.07-.079a.971.971 0 00-.07-1.294L9.645 8.272l1.592-1.591a.97.97 0 000-1.373l-.079-.071a.971.971 0 00-1.294.07z"
                                            fill-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 w-0 ml-2">
                                <p
                                    class="leading-5 text-gray-800 dark:text-gray-200"
                                >
                                    <span
                                        v-if="
                                            $page.props.flash.error != null &&
                                                Object.keys($page.props.errors)
                                                    .length === 0
                                        "
                                        >{{ $page.props.flash.error }}</span
                                    >
                                    <span
                                        v-else-if="
                                            Object.keys($page.props.errors)
                                                .length === 1
                                        "
                                        >{{
                                            $page.props.errors[
                                                Object.keys(
                                                    $page.props.errors
                                                )[0]
                                            ]
                                        }}</span
                                    >
                                    <span v-else
                                        >{{
                                            trans.get(
                                                "forms.errors_occurred_at"
                                            )
                                        }}
                                        {{
                                            Object.keys($page.props.errors)
                                                .length
                                        }}
                                        {{ trans.get("forms.sections") }}
                                    </span>
                                </p>
                            </div>
                            <div class="flex flex-shrink-0 ml-4">
                                <button
                                    class="inline-flex text-gray-400 transition duration-150 ease-in-out focus:outline-none focus:text-gray-500"
                                    @click="show = false"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script>
export default {
    data() {
        return {
            show: false
        };
    },
    watch: {
        "$page.props.flash": {
            handler() {
                if (
                    this.$page.props.flash.error !== null ||
                    this.$page.props.flash.success !== null
                ) {
                    this.showFlashMessage(0);
                }
            },
            deep: true
        }
    },
    created() {
        if (
            this.$page.props.flash.error !== null ||
            this.$page.props.flash.success !== null
        ) {
            this.showFlashMessage(200);
        }
    },

    methods: {
        showFlashMessage(timeOut) {
            let vm = this;
            setTimeout(function() {
                vm.show = true;

                setTimeout(function() {
                    vm.show = false;
                    vm.$page.props.flash.error = null;
                    vm.$page.props.flash.success = null;
                }, 5000);
            }, timeOut);
        }
    }
};
</script>
