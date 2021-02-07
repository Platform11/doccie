<template>
    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        General
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Algemene gebruikersinformatie
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form @submit.prevent="submit">
                    <div class="overflow-hidden shadow sm:rounded-md">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <form @submit.prevent="submit">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-3">
                                        <text-input
                                            v-model="form.first_name"
                                            label="Voornaam"
                                        ></text-input>
                                    </div>

                                    <div class="col-span-6 sm:col-span-3">
                                        <text-input
                                            v-model="form.last_name"
                                            label="Achternaam"
                                        ></text-input>
                                    </div>

                                    <div class="col-span-6 sm:col-span-6">
                                        <text-input
                                            v-model="form.email"
                                            label="Email"
                                        ></text-input>
                                    </div>
                                </div>
                            </form>
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
        user: Object
    },
    data() {
        return {
            form: {
                id: this.user.id,
                first_name: this.user.first_name,
                last_name: this.user.last_name,
                email: this.user.email
            },
            submitting: false
        };
    },

    created() {},

    methods: {
        submit() {
            this.submitting = true;
            this.$inertia.post(this.route("users.update"), this.form, {
                preserveScroll: true,
                onFinish: () => {
                    this.submitting = false;
                }
            });
        }
    }
};
</script>
