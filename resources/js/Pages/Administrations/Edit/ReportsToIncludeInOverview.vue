<template>
    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        Rapporten
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Hier kan aangegeven worden welke rapporten standaard
                        meegestuurd moeten worden met ieder overzicht.
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form @submit.prevent="submit">
                    <div class="overflow-hidden shadow sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-2">
                                    <input-checkbox
                                        label="Vraagpostenoverzicht"
                                        value="call_posts"
                                        :check-value="reports.call_posts"
                                        :error="errors.name"
                                        @change="
                                            reports.call_posts = !reports.call_posts
                                        "
                                    ></input-checkbox>
                                </div>
                                <div class="col-span-6 sm:col-span-2">
                                    <input-checkbox
                                        label="Debiteurenoverzicht"
                                        value="debtors"
                                        :check-value="reports.debtors"
                                        :error="errors.name"
                                        @change="
                                            reports.debtors = !reports.debtors
                                        "
                                    ></input-checkbox>
                                </div>
                                <div class="col-span-6 sm:col-span-2">
                                    <input-checkbox
                                        label="Crediteurenoverzicht"
                                        value="creditors"
                                        :check-value="reports.creditors"
                                        :error="errors.name"
                                        @change="
                                            reports.creditors = !reports.creditors
                                        "
                                    ></input-checkbox>
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
import InputCheckbox from "@/Elements/InputCheckbox";
import LoadingButton from "@/Elements/LoadingButton.vue";

export default {
    components: {
        InputCheckbox,
        LoadingButton
    },
    props: {
        administration: Object,
        errors: Object
    },
    data() {
        return {
            submitting: false,
            reports: {
                call_posts: false,
                debtors: false,
                creditors: false
            },
            form: {
                administration_id: this.administration.id,
                reports_to_include_in_overview: this.administration
                    .reports_to_include_in_overview
            }
        };
    },

    created() {
        let vm = this;
        if (this.form.reports_to_include_in_overview !== null) {
            this.form.reports_to_include_in_overview.forEach(function(element) {
                if (element == "call_posts") {
                    vm.reports.call_posts = true;
                }

                if (element == "debtors") {
                    vm.reports.debtors = true;
                }

                if (element == "creditors") {
                    vm.reports.creditors = true;
                }
            });
        }
    },

    methods: {
        calculateEnabledReports() {
            let reports = [];

            if (this.reports.call_posts) {
                reports.push("call_posts");
            }

            if (this.reports.debtors) {
                reports.push("debtors");
            }

            if (this.reports.creditors) {
                reports.push("creditors");
            }

            this.form.reports_to_include_in_overview = reports;
        },

        submit() {
            this.submitting = true;
            this.calculateEnabledReports();
            this.$inertia.post(
                this.route("administrations.update.reports_to_include"),
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
