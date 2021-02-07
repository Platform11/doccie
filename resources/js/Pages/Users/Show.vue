<template>
    <layout>
        <div class="flex flex-wrap w-full pb-12">
            <div class="w-full">
                <div class="flex items-center justify-between w-full">
                    <h1 class="hidden text-gray-900 md:block">
                        {{ user.first_name }} {{ user.last_name }}
                    </h1>
                    <div>
                        <div
                            class="btn btn-delete"
                            @click="showConfirmDelete = true"
                        >
                            Verwijderen
                        </div>
                    </div>
                </div>

                <div class="w-full mt-4">
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px space-x-8" aria-label="Tabs">
                            <div
                                v-for="tab in tabs"
                                @click="toggleTab(tab.id)"
                                :key="tab.id"
                                class="px-1 py-4 text-sm font-medium text-gray-500 border-b-2 cursor-pointer hover:text-gray-700 hover:border-gray-300 whitespace-nowrap"
                                :class="
                                    tab.id == activeTab
                                        ? ' border-primary text-primary border-opacity-100 hover:border-primary hover:text-primary'
                                        : '  text-gray-500 border-opacity-0 border-gray-300'
                                "
                            >
                                {{ tab.label }}
                            </div>
                        </nav>
                    </div>
                </div>
                <div class="w-full mt-8">
                    <div v-show="activeTab == 'info'" class="w-full">
                        <EditGeneralInformation :user="user" />
                        <!-- <div class="hidden sm:block" aria-hidden="true">
                            <div class="py-8">
                                <div class="border-t border-gray-200"></div>
                            </div>
                        </div> -->
                        <!-- <EditTwinfieldCredentils :user="user" />
                        <div class="hidden sm:block" aria-hidden="true">
                            <div class="py-8">
                                <div class="border-t border-gray-200"></div>
                            </div>
                        </div> -->
                    </div>
                    <div v-show="activeTab == 'administrations'" class="w-full">
                        <TableAdminstrations
                            :administrations="administrations"
                        ></TableAdminstrations>
                    </div>
                </div>
            </div>
            <modal-confirm-danger
                title="Gebruiker verwijderen"
                description="Weet je zeker dat je deze gebruiker wil verwijderen? De administraties waarvan deze gebruiker de relatiemanager is zullen overgedragen worden naar jou. Deze actie kan niet ongedaan worden gemaakt."
                confirm-text="Verwijder"
                :show="showConfirmDelete"
                @confirm="deleteUser()"
                @close="showConfirmDelete = false"
            />
        </div>
    </layout>
</template>

<script>
import Layout from "@/Shared/Layout";
import EditGeneralInformation from "@/Pages/Users/Edit/General";
import EditTwinfieldCredentials from "@/Pages/Users/Edit/TwinfieldCredentials";
import ModalConfirmDanger from "@/Components/Modals/ConfirmDanger";
import TableAdminstrations from "@/Components/Tables/Administrations";

export default {
    // metaInfo: { title: this.user.name },
    components: {
        Layout,
        EditGeneralInformation,
        EditTwinfieldCredentials,
        TableAdminstrations,
        ModalConfirmDanger
    },
    props: {
        user: Object,
        administrations: Array
    },
    data() {
        return {
            activeTab: "info",
            showConfirmDelete: false,
            tabs: [
                { label: "Gegevens", id: "info" },
                { label: "Administraties", id: "administrations" }
            ]
        };
    },

    created() {
        this.pageTitle = this.user.name;
    },

    methods: {
        toggleTab(tabID) {
            this.activeTab = tabID;
        },
        deleteUser() {
            this.$inertia.post(
                this.route("users.delete", { user: this.user.id })
            );
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
