<template>
    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        Algemeen
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Algemene kantoor informatie
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
                                        :error="errors.name"
                                        label="Naam"
                                    ></text-input>
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <text-input
                                        v-model="form.twinfield_office_code"
                                        :error="errors.twinfield_office_code"
                                        label="Twinfield office code"
                                    ></text-input>
                                </div>

                                <div class="col-span-6 sm:col-span-6">
                                    <div class="mb-2 font-semibold">Logo</div>

                                    <img
                                        v-if="account.logo != ''"
                                        style="height:32px;"
                                        :src="account.logo"
                                    />
                                    <div @click="editLogo = true">
                                        edit logo
                                    </div>
                                    <media-library-attachment
                                        ref="mediaComponent"
                                        v-if="editLogo || account.logo == ''"
                                        name="logo"
                                        class="w-full"
                                        :validation-rules="{
                                            accept: ['image/png', 'image/jpeg'],
                                            maxSizeInKB: 5000
                                        }"
                                        :validation-errors="errors.logo"
                                        @change="onChange"
                                        :translations="{
                                            fileTypeNotAllowed:
                                                'You must upload a file of type',
                                            tooLarge: 'File too large, max',
                                            tooSmall: 'File too small, min',
                                            tryAgain:
                                                'please try uploading this file again',
                                            somethingWentWrong:
                                                'Something went wrong while uploading this file',
                                            selectOrDrag:
                                                'Select or drag files',
                                            selectOrDragMax:
                                                'Selecteer een afbeelding of bestand {maxItems} {file}',
                                            file: {
                                                singular: 'bestand',
                                                plural: 'bestanden'
                                            },
                                            anyImage: 'any image',
                                            anyVideo: 'any video',
                                            goBack: 'Go back',
                                            dropFile: 'Drop file to upload',
                                            dragHere: 'Drag file here',
                                            remove: 'Verwijder'
                                        }"
                                    />
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
import { MediaLibraryAttachment } from "media-library-pro-vue2-attachment";

export default {
    components: {
        TextInput,
        LoadingButton,
        MediaLibraryAttachment
    },

    props: {
        account: Object,
        errors: Object
    },

    data() {
        return {
            editLogo: false,
            form: {
                account_id: this.account.id,
                name: this.account.name,
                logo: "",
                twinfield_office_code: this.account.twinfield_office_code
            },
            submitting: false
        };
    },

    created() {},

    methods: {
        onChange(logo) {
            this.form.logo = logo;
        },
        submit() {
            this.submitting = true;
            this.$inertia.post(this.route("settings.update.info"), this.form, {
                preserveScroll: true,
                onFinish: () => {
                    this.submitting = false;
                    this.logo = "";
                    this.editLogo = false;
                    this.$refs.mediaComponent.mediaLibrary.changeState(
                        state => {
                            state.media = [];
                        }
                    );
                }
            });
        }
    }
};
</script>
