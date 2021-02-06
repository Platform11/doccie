<template>
    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        Relatiebeheerder
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Wijzig hier de relatiebeheerder. Uit naam van deze
                        persoon worden de overzichten verstuurd.
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form @submit.prevent="submit">
                    <div class="overflow-hidden shadow sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-6">
                                    <select-input
                                        v-model="form.relation_manager_id"
                                        label="Relatiebeheerder"
                                        :error="errors.relation_manager_id"
                                    >
                                        <option
                                            v-for="colleague in colleagues"
                                            :key="colleague.id"
                                            :value="colleague.id"
                                        >
                                            {{ colleague.first_name }}
                                            {{ colleague.last_name }}
                                        </option>
                                    </select-input>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 text-right bg-gray-50 sm:px-6">
                            <loading-button :loading="submitting">
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
import SelectInput from "@/Elements/SelectInput";
import LoadingButton from "@/Elements/LoadingButton.vue";

export default {
    components: {
        SelectInput,
        LoadingButton
    },
    props: {
        administration: Object,
        relation_manager: Object,
        colleagues: Array,
        errors: Object
    },
    data() {
        return {
            form: {
                administration_id: this.administration.id,
                relation_manager_id: this.relation_manager.id
            },
            submitting: false
        };
    },

    methods: {
        submit() {
            this.submitting = true;
            this.$inertia.post(
                this.route("administrations.update.relation_manager"),
                this.form,
                {
                    onFinish: () => {
                        this.submitting = false;
                    }
                }
            );
        }
    }
};
</script>
