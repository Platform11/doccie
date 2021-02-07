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
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <text-input
                                    v-model="form.username"
                                    label="Gebruikersnaam"
                                    disabled
                                ></text-input>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <text-input
                                    :value="twinfield_credentials[1]"
                                    label="Kantoor"
                                    disabled
                                ></text-input>
                            </div>
                        </div>
                    </div>
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
        twinfield_credentials: Array
    },
    data() {
        return {
            form: {
                username: this.twinfield_credentials[0],
                password: "dashj2nd"
            }
        };
    },

    created() {}
};
</script>
