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
                    <h2 class="text-2xl text-center">Welkom!</h2>
                    <p class="mt-2 text-center">
                        Voordat je je account kan gebruiken hoef je alleen nog
                        maar een wachtwoord in te stellen.
                    </p>
                    <text-input
                        v-model="form.password"
                        :error="errors.password"
                        class="mt-6 text-gray-100"
                        label="Wachtwoord"
                        type="password"
                        autocapitalize="off"
                    />
                    <text-input
                        v-model="form.password_confirmation"
                        :error="errors.password_confirmation"
                        class="mt-4 text-gray-100"
                        label="Bevestig wachtwoord"
                        type="password"
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
                        >Stel wachtwoord in</loading-button
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
    // metaInfo: { title: this.user.name },
    components: {
        LoadingButton,
        Logo,
        TextInput,
        Toggle,
        FlashMessages
    },
    props: {
        token: String,
        errors: Object
    },
    data() {
        return {
            submitting: false,
            form: {
                password: "",
                password_confirmation: "",
                token: this.token
            }
        };
    },

    created() {
        this.pageTitle = "Welkom!";
    },

    methods: {
        submit() {
            this.submitting = true;
            this.$inertia.post(this.route("invite.accept"), this.form, {
                onFinish: () => {
                    this.submitting = false;
                }
            });
        }
    },
    computed: {
        pageTitle: {
            set(pageTitle) {
                this.$store.commit("app/setPageTitle", pageTitle);
            },
            get() {
                return this.$store.state.app.pageTitle;
            }
        }
    }
};
</script>
