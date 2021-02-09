<template>
    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        Algemeen
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Algemene administratieinformatie
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form @submit.prevent="submit">
                    <div class="overflow-hidden shadow sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <text-input
                                        v-model="form.name"
                                        label="Naam"
                                        :error="errors.name"
                                    ></text-input>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <text-input
                                        v-model="form.code"
                                        label="Administratiecode"
                                        :error="errors.code"
                                    ></text-input>
                                </div>
                                <div class="col-span-6 sm:col-span-2">
                                    <text-input
                                        v-model="form.call_posts_code"
                                        :error="errors.call_posts_code"
                                        label="Vraagposten rekening"
                                    ></text-input>
                                </div>
                                <div class="col-span-6 sm:col-span-2">
                                    <text-input
                                        v-model="form.creditors_code"
                                        :error="errors.creditors_code"
                                        label="Crediteuren rekening"
                                    ></text-input>
                                </div>
                                <div class="col-span-6 sm:col-span-2">
                                    <text-input
                                        v-model="form.debtors_code"
                                        :error="errors.debtors_code"
                                        label="Debiteuren rekening"
                                    ></text-input>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 text-right bg-gray-50 sm:px-6">
                            <loading-button type="submit" :loading="submitting">
                                Opslaan
                            </loading-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import TextInput from "@/Elements/TextInput";
import LoadingButton from "@/Elements/LoadingButton.vue";

export default {
    components: {
        TextInput,
        LoadingButton
    },
    props: {
        administration: Object,
        errors: Object
    },
    data() {
        return {
            submitting: false,
            form: {
                administration_id: this.administration.id,
                name: this.administration.name,
                code: this.administration.code,
                call_posts_code: this.administration.call_posts_code,
                creditors_code: this.administration.creditors_code,
                debtors_code: this.administration.debtors_code
            }
        };
    },

    methods: {
        submit() {
            this.submitting = true;
            this.$inertia.post(
                this.route("administrations.update.info"),
                this.form,
                {
                    preserveScroll: true,
                    onFinish: () => {
                        this.submitting = false;
                    }
                }
            );
        }
    }
};
</script>
