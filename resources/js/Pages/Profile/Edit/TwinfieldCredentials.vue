<template>
    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        Twinfield
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Twinfield login informatie
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="overflow-hidden shadow sm:rounded-md">
                    <form @submit.prevent="submit">
                        <div class="px-4 py-5 bg-white sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <text-input
                                        v-model="form.twinfield_username"
                                        label="Gebruikersnaam"
                                    ></text-input>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <text-input
                                        :value="credentials[1]"
                                        label="Kantoor"
                                        disabled
                                    ></text-input>
                                </div>
                                <div class="col-span-6 sm:col-span-6">
                                    <text-input
                                        v-model="form.twinfield_password"
                                        label="Wachtwoord"
                                        type="password"
                                    ></text-input>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 text-right bg-gray-50 sm:px-6">
                            <loading-button
                                class="mr-2"
                                :loading="testingTwinfieldCredentials"
                                @click="testTwinfieldCredentials"
                            >
                                Test connectie
                            </loading-button>
                            <loading-button :loading="submitting" type="submit">
                                Opslaan
                            </loading-button>
                        </div>
                    </form>
                </div>
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
        credentials: Array
    },
    data() {
        return {
            testingTwinfieldCredentials: false,
            submitting: false,
            form: {
                id: this.$page.props.auth.user.id,
                twinfield_username: this.credentials[0],
                twinfield_password: "abcdefghijk"
            }
        };
    },

    created() {},

    methods: {
        submit() {
            this.submitting = true;
            this.$inertia.post(
                this.route("profile.update.twinfield-credentials"),
                this.form,
                {
                    preserveScroll: true,
                    onFinish: () => {
                        this.submitting = false;
                    }
                }
            );
        },

        testTwinfieldCredentials() {
            this.testingTwinfieldCredentials = true;
            let vm = this;
            axios({
                method: "get",
                url: this.route("twinfield.validate-credentials")
            }).then(response => {
                if (response.data.status == "success") {
                    vm.showSucccesAlert();
                } else {
                    vm.showErrorAlert();
                }
                vm.testingTwinfieldCredentials = false;
            });
        },

        showSucccesAlert() {
            this.$page.props.alert.type = "success";
            this.$page.props.alert.title = "Gelukt!";
            this.$page.props.alert.message =
                "Het is gelukt om een connectie te maken met Twinfield op basis van uw inloggegevens.";
            this.$page.props.alert.confirm_text = "Sluiten";
            this.$page.props.alert.show = true;
        },

        showErrorAlert() {
            this.$page.props.alert.type = "error";
            this.$page.props.alert.title = "Helaas!";
            this.$page.props.alert.message =
                "Het is niet gelukt om een connectie met Twinfield te maken. Controlleer uw gebruikersnaam, wachtwoord en de kantoorcode.";
            this.$page.props.alert.confirm_text = "Ik begrijp het";
            this.$page.props.alert.show = true;
        }
    }
};
</script>
