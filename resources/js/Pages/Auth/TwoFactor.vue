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
                    <h3
                        v-if="success"
                        class="px-4 py-3 mb-6 text-sm font-medium text-center text-green-800 bg-green-200 rounded"
                    >
                        Er is een wachtwoord-herstel link is naar u gemaild.
                    </h3>
                    <text-input
                        v-model="form.code"
                        :error="errors.code"
                        class="text-gray-100 "
                        label="2FA code"
                        type="text"
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
                        >Verstuur</loading-button
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
                code: ""
            },
            success: false
        };
    },
    methods: {
        submit() {
            const data = {
                code: this.form.code
            };
            this.$inertia.post("/two-factor-challenge", data, {
                onStart: () => (this.submitting = true)
                //onFinish: () => ()
            });
        }

        // onSuccess() {
        //     this.submitting = false;

        //     if (this.$page.props.errors.email === undefined) {
        //         this.form.email = "";
        //         this.$page.props.flash.success = this.trans.get(
        //             "passwords.sent"
        //         );
        //     }
        // }
    }
};
</script>
