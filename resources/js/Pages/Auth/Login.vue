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
    </div>
</template>

<script>
import LoadingButton from "@/Elements/LoadingButton";
import Logo from "@/Elements/Logo";
import TextInput from "@/Elements/TextInput";
import Toggle from "@/Elements/Toggle";
import FlashMessages from "@/Shared/FlashMessages";

export default {
    metaInfo: { title: "Login" },
    components: {
        LoadingButton,
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
    }
};
</script>
