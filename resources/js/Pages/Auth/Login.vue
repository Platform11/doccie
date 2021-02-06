<template>
    <div
        class="flex items-center justify-center min-h-screen p-6 bg-gray-100 dark:bg-black"
    >
        <flash-messages />
        <div class="w-full max-w-md">
            <logo class="w-48" />
            <form
                class="mt-8 overflow-hidden bg-white border-gray-900 shadow-xl dark:bg-gray-900 rounded-xl"
                @submit.prevent="submit"
            >
                <div
                    class="relative px-8 py-8 border-b border-gray-100 dark:border-gray-950"
                >
                    <text-input
                        v-model="form.email"
                        :error="errors.email"
                        class="text-gray-100 "
                        :label="trans.get('auth.email')"
                        type="email"
                        autofocus
                        autocapitalize="off"
                        id="email"
                    />
                    <text-input
                        v-model="form.password"
                        class="mt-6 text-gray-100"
                        :label="trans.get('auth.password')"
                        :error="errors.password"
                        type="password"
                        id="password"
                    />
                    <div class="flex items-center mt-6">
                        <toggle
                            class="mr-3"
                            :is-toggled="form.remember"
                            @toggle="toggleRemember"
                        ></toggle>
                        <span
                            class="text-sm text-gray-900 dark:text-gray-100"
                            >{{ trans.get("auth.remember_credentials") }}</span
                        >
                    </div>
                </div>
                <div
                    class="flex items-center justify-between px-8 py-4 bg-gray-50 dark:border-t dark:bg-gray-900 dark:border-gray-850"
                >
                    <inertia-link
                        class="text-sm outline-none text-primary dark:text-gray-100 hover:underline"
                        tabindex="-1"
                        :href="route('password.request')"
                        >{{ trans.get("auth.forgot_password") }}</inertia-link
                    >
                    <loading-button
                        id="login"
                        :loading="submitting"
                        :class="submitting ? 'btn-submitting' : ''"
                        type="submit"
                        >{{ trans.get("auth.login") }}</loading-button
                    >
                </div>
            </form>
        </div>
        <modal
            :show="showAccountIsBlockedModal"
            @close="showAccountIsBlockedModal = false"
            class="z-50 max-w-xl m-2 sm:mx-auto"
        >
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
                            class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full"
                        >
                            <!-- Heroicon name: check -->
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
                                {{ trans.get("account.deactivated") }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-600">
                                    {{
                                        trans.get(
                                            "account.deactivated_explanation"
                                        )
                                    }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-5 sm:mt-6">
                            <button
                                @click="showAccountIsBlockedModal = false"
                                type="button"
                                class="relative inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out rounded-md btn btn-primary ring-2 ring-opacity-0 ring-red-500 hover:ring-opacity-40 bg-primary hover:bg-primary-light focus:outline-none active:bg-primary-dark sm:leading-5"
                            >
                                {{ trans.get("general.i_understand") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
import LoadingButton from "@/Elements/LoadingButton";
import Logo from "@/Elements/Logo";
import TextInput from "@/Elements/TextInput";
import Toggle from "@/Elements/Toggle";
import FlashMessages from "@/Shared/FlashMessages";
import Modal from "@/Components/Modals/Base";

export default {
    metaInfo: { title: "Login" },
    components: {
        LoadingButton,
        Modal,
        Logo,
        TextInput,
        Toggle,
        FlashMessages
    },
    props: {
        errors: Object
    },
    data() {
        return {
            submitting: false,
            showAccountIsBlockedModal: this.$page.props.account_is_blocked,
            form: {
                email: "",
                password: "",
                remember: false
            }
        };
    },

    methods: {
        submit() {
            const data = {
                email: this.form.email,
                password: this.form.password
            };

            if (this.form.remember) {
                data.remember = true;
            }

            this.$inertia.post(this.route("login"), data, {
                onStart: () => (this.submitting = true),
                onFinish: () => (this.submitting = false)
            });
        },

        toggleRemember() {
            this.form.remember = !this.form.remember;
        }
    },

    watch: {
        "$page.props.account_is_blocked": {
            handler() {
                console.log(this.$page.props.account_is_blocked);
                this.showAccountIsBlockedModal = this.$page.props.account_is_blocked;
            },
            deep: true
        }
    }
};
</script>
