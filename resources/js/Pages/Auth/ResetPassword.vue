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
                        label="Email"
                        type="email"
                        autofocus
                        autocapitalize="off"
                    />
                    <text-input
                        v-model="form.password"
                        :error="errors.password"
                        class="mt-4 text-gray-100"
                        label="Nieuw wachtwoord"
                        type="password"
                        autocomplete="new-password"
                        autofocus
                        autocapitalize="off"
                    />

                    <text-input
                        v-model="form.password_confirmation"
                        :error="errors.password_confirmation"
                        class="mt-4 text-gray-100"
                        label="Bevestig wachtwoord"
                        type="password"
                        autocomplete="new-password"
                        autofocus
                        autocapitalize="off"
                    />
                </div>
                <div
                    class="flex items-center justify-center px-8 py-4 bg-gray-50 dark:border-t dark:bg-gray-900 dark:border-gray-850"
                >
                    <loading-button
                        :loading="submitting"
                        class="relative inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out rounded-md btn btn-primary ring-2 ring-opacity-0 ring-red-500 hover:ring-opacity-40 bg-primary hover:bg-primary-light focus:outline-none active:bg-primary-dark sm:leading-5"
                        :class="submitting ? 'btn-submitting' : ''"
                        type="submit"
                        >{{
                            trans.get("passwords.restore_password")
                        }}</loading-button
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
import FlashMessages from "@/Shared/FlashMessages";

export default {
    components: {
        LoadingButton,
        Logo,
        TextInput,
        FlashMessages
    },

    props: {
        errors: Object,
        email: String,
        token: String
    },

    created() {
        console.log(this.token);
    },

    data() {
        return {
            submitting: false,
            form: {
                token: this.token,
                email: this.email,
                password: "",
                password_confirmation: ""
            }
        };
    },

    methods: {
        // submit() {
        //     this.submitting = true;
        //     this.$page.errors = {};
        //     this.$inertia.post(this.$route("password.update"), {
        //         ...this.form
        //     });
        //     this.form.password = "";
        //     this.form.password_confirmation = "";
        //     this.submitting = false;
        // }

        submit() {
            this.$inertia.post(this.route("password.update"), this.form, {
                onStart: () => (this.submitting = true),
                onFinish: () => (this.submitting = false)
            });
        }
    }
};
</script>
