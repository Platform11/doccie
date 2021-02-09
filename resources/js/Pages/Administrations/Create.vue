<template>
    <layout>
        <div class="flex flex-wrap w-full pb-12">
            <div class="w-full">
                <div class="flex items-center justify-between w-full">
                    <h1 class="hidden text-gray-900 md:block">
                        {{
                            form.name == "" ? "Nieuwe administratie" : form.name
                        }}
                        toevoegen
                    </h1>
                    <div>
                        <inertia-link :href="route('administrations.index')">
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
                                        Algemene administratieinformatie
                                    </p>
                                </div>
                            </div>
                            <div class="mt-5 md:mt-0 md:col-span-2">
                                <div
                                    class="overflow-hidden shadow sm:rounded-md"
                                >
                                    <div class="px-4 py-5 bg-white sm:p-6">
                                        <div class="grid grid-cols-6 gap-6">
                                            <div
                                                class="col-span-6 sm:col-span-3"
                                            >
                                                <text-input
                                                    v-model="form.name"
                                                    :error="errors.name"
                                                    label="Naam"
                                                ></text-input>
                                            </div>

                                            <div
                                                class="col-span-6 sm:col-span-3"
                                            >
                                                <text-input
                                                    v-model="form.code"
                                                    :error="errors.code"
                                                    label="Administratiecode"
                                                ></text-input>
                                            </div>
                                            <div
                                                class="col-span-6 sm:col-span-2"
                                            >
                                                <text-input
                                                    v-model="
                                                        form.call_posts_code
                                                    "
                                                    :error="
                                                        errors.call_posts_code
                                                    "
                                                    label="Vraagposten rekening"
                                                ></text-input>
                                            </div>
                                            <div
                                                class="col-span-6 sm:col-span-2"
                                            >
                                                <text-input
                                                    v-model="form.debtors_code"
                                                    :error="errors.debtors_code"
                                                    label="Debiteuren rekening"
                                                ></text-input>
                                            </div>
                                            <div
                                                class="col-span-6 sm:col-span-2"
                                            >
                                                <text-input
                                                    v-model="
                                                        form.creditors_code
                                                    "
                                                    :error="
                                                        errors.creditors_code
                                                    "
                                                    label="Crediteuren rekening"
                                                ></text-input>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hidden sm:block" aria-hidden="true">
                            <div class="py-8">
                                <div class="border-t border-gray-200"></div>
                            </div>
                        </div>
                        <div class="md:grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <div class="px-4 sm:px-0">
                                    <h3
                                        class="text-lg font-medium leading-6 text-gray-900"
                                    >
                                        Contactpersoon
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-600">
                                        Informatie van de contactpersoon
                                    </p>
                                </div>
                            </div>
                            <div class="mt-5 md:mt-0 md:col-span-2">
                                <div
                                    class="overflow-hidden shadow sm:rounded-md"
                                >
                                    <div class="px-4 py-5 bg-white sm:p-6">
                                        <div class="grid grid-cols-6 gap-6">
                                            <div
                                                class="col-span-6 sm:col-span-3"
                                            >
                                                <text-input
                                                    v-model="
                                                        form.contact_first_name
                                                    "
                                                    :error="
                                                        errors.contact_first_name
                                                    "
                                                    label="Voornaam"
                                                ></text-input>
                                            </div>

                                            <div
                                                class="col-span-6 sm:col-span-3"
                                            >
                                                <text-input
                                                    v-model="
                                                        form.contact_last_name
                                                    "
                                                    :error="
                                                        errors.contact_last_name
                                                    "
                                                    label="Achternaam"
                                                ></text-input>
                                            </div>

                                            <div
                                                class="col-span-6 sm:col-span-6"
                                            >
                                                <text-input
                                                    v-model="form.contact_email"
                                                    :error="
                                                        errors.contact_email
                                                    "
                                                    label="Email"
                                                ></text-input>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="hidden sm:block" aria-hidden="true">
                            <div class="py-8">
                                <div class="border-t border-gray-200"></div>
                            </div>
                        </div>
                        <div class="md:grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <div class="px-4 sm:px-0">
                                    <h3
                                        class="text-lg font-medium leading-6 text-gray-900"
                                    >
                                        Relatiebeheerder
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-600">
                                        Relatiebeheerder van deze administratie
                                    </p>
                                </div>
                            </div>
                            <div class="mt-5 md:mt-0 md:col-span-2">
                                <div
                                    class="overflow-hidden shadow sm:rounded-md"
                                >
                                    <div class="px-4 py-5 bg-white sm:p-6">
                                        <div class="grid grid-cols-6 gap-6">
                                            <div
                                                class="col-span-6 sm:col-span-6"
                                            >
                                                <select-input
                                                    v-model="
                                                        form.relation_manager_id
                                                    "
                                                    :error="
                                                        errors.relation_manager_id
                                                    "
                                                    label="Relatiebeheerder"
                                                >
                                                    <option
                                                        v-for="colleague in colleagues"
                                                        :key="colleague.id"
                                                        :value="colleague.id"
                                                    >
                                                        {{
                                                            colleague.first_name
                                                        }}
                                                        {{
                                                            colleague.last_name
                                                        }}
                                                    </option>
                                                </select-input>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hidden sm:block" aria-hidden="true">
                            <div class="py-8">
                                <div class="border-t border-gray-200"></div>
                            </div>
                        </div>
                        <div class="text-right ">
                            <loading-button type="submit" :loading="submitting">
                                Toevoegen
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
import LoadingButton from "@/Elements/LoadingButton.vue";
import TableReports from "@/Components/Tables/Reports";
import SelectInput from "@/Elements/SelectInput";

export default {
    // metaInfo: { title: this.user.name },
    components: {
        Layout,
        TextInput,
        LoadingButton,
        SelectInput
    },
    props: {
        colleagues: Array,
        errors: Object
    },
    data() {
        return {
            form: {
                name: "",
                code: "",
                call_posts_code: 2999,
                creditors_code: 1600,
                debtors_code: 1600,
                contact_first_name: "",
                contact_last_name: "",
                contact_email: "",
                relation_manager_id: this.$page.props.auth.user.id
            },
            submitting: false
        };
    },

    created() {
        this.pageTitle = "Nieuwe administratie toevoegen";
    },

    methods: {
        submit() {
            this.submitting = true;
            this.$inertia.post(this.route("administrations.store"), this.form, {
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
