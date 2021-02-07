<template>
    <layout>
        <div class="flex flex-wrap w-full pb-12">
            <div class="w-full">
                <div class="flex items-center justify-between w-full">
                    <h1 class="hidden text-gray-900 md:block">
                        Nieuwe gebruiker toevoegen
                    </h1>
                    <div>
                        <inertia-link :href="route('users.index')">
                            <div class="btn">Annuleren</div>
                        </inertia-link>
                    </div>
                </div>

                <div class="w-full mt-4">
                    <div class="border-b border-gray-200"></div>
                </div>
                <form @submit.prevent="submit">
                    <div class="w-full mt-8">
                        <div class="md:grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <div class="px-4 sm:px-0">
                                    <h3
                                        class="text-lg font-medium leading-6 text-gray-900"
                                    >
                                        General
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-600">
                                        Algemene gebruikersinformatie
                                    </p>
                                </div>
                            </div>
                            <div class="mt-5 md:mt-0 md:col-span-2">
                                <form @submit.prevent="submit">
                                    <div
                                        class="overflow-hidden shadow sm:rounded-md"
                                    >
                                        <div class="px-4 py-5 bg-white sm:p-6">
                                            <form @submit.prevent="submit">
                                                <div
                                                    class="grid grid-cols-6 gap-6"
                                                >
                                                    <div
                                                        class="col-span-6 sm:col-span-3"
                                                    >
                                                        <text-input
                                                            v-model="
                                                                form.first_name
                                                            "
                                                            label="Voornaam"
                                                        ></text-input>
                                                    </div>

                                                    <div
                                                        class="col-span-6 sm:col-span-3"
                                                    >
                                                        <text-input
                                                            v-model="
                                                                form.last_name
                                                            "
                                                            label="Achternaam"
                                                        ></text-input>
                                                    </div>

                                                    <div
                                                        class="col-span-6 sm:col-span-6"
                                                    >
                                                        <text-input
                                                            v-model="form.email"
                                                            label="Email"
                                                        ></text-input>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="hidden sm:block" aria-hidden="true">
                            <div class="py-8">
                                <div class="border-t border-gray-200"></div>
                            </div>
                        </div>
                        <div class="text-right ">
                            <loading-button type="submit" :loading="submitting">
                                Toevoegen en uitnodigen
                            </loading-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from "@/Shared/Layout";
import TextInput from "@/Elements/TextInput";
import LoadingButton from "@/Elements/LoadingButton";

export default {
    // metaInfo: { title: this.user.name },
    components: {
        Layout,
        TextInput,
        LoadingButton
    },
    props: {
        errors: Object
    },
    data() {
        return {
            form: {
                first_name: "",
                last_name: "",
                email: ""
            },
            submitting: false
        };
    },

    created() {
        this.pageTitle = "Gebruiker toevoegen";
    },

    methods: {
        submit() {
            this.submitting = true;
            this.$inertia.post(this.route("users.store"), this.form, {
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
