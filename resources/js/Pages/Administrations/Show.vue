<template>
    <layout>
        <div class="flex flex-wrap w-full pb-12">
            <div class="w-full">
                <div class="flex justify-between w-full">
                    <h1 class="hidden text-gray-900 md:block">
                        {{ administration.name }}
                    </h1>
                    <div
                        class="btn btn-delete"
                        @click="showConfirmDelete = true"
                    >
                        Verwijderen
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
                        <EditGeneralInformation
                            :administration="administration"
                            :errors="errors"
                        />
                        <div class="hidden sm:block" aria-hidden="true">
                            <div class="py-8">
                                <div class="border-t border-gray-200"></div>
                            </div>
                        </div>
                        <EditContactPerson
                            :administration="administration"
                            :errors="errors"
                            :contact-person="{
                                first_name: administration.contact_first_name,
                                last_name: administration.contact_last_name,
                                email: administration.contact_email
                            }"
                        />
                        <div class="hidden sm:block" aria-hidden="true">
                            <div class="py-8">
                                <div class="border-t border-gray-200"></div>
                            </div>
                        </div>
                        <EditRelationManager
                            :administration="administration"
                            :relation_manager="administration.relation_manager"
                            :errors="errors"
                            :colleagues="colleagues"
                        />
                    </div>
                    <div v-show="activeTab == 'sent-reports'" class="w-full">
                        <TableReports :reports="administration.reports" />
                    </div>
                </div>
            </div>
            <modal-confirm-danger
                title="Administratie verwijderen"
                description="Weet je zeker dat je deze administratie wil verwijderen? Alle informatie die aan deze adminisratie is gekoppeld zal verloren gaan. Deze actie kan niet ongedaan worden gemaakt."
                confirm-text="Verwijder"
                :show="showConfirmDelete"
                @confirm="deleteAdministration()"
                @close="showConfirmDelete = false"
            />
        </div>
    </layout>
</template>

<script>
import Layout from "@/Shared/Layout";
import EditGeneralInformation from "@/Pages/Administrations/Edit/General";
import EditContactPerson from "@/Pages/Administrations/Edit/ContactPerson";
import EditRelationManager from "@/Pages/Administrations/Edit/RelationManager";
import ModalConfirmDanger from "@/Components/Modals/ConfirmDanger";
import TableReports from "@/Components/Tables/Reports";

export default {
    // metaInfo: { title: this.user.name },
    components: {
        Layout,
        EditGeneralInformation,
        EditContactPerson,
        EditRelationManager,
        TableReports,
        ModalConfirmDanger
    },
    props: {
        administration: Object,
        colleagues: Array,
        errors: Object
    },
    data() {
        return {
            showConfirmDelete: false,
            activeTab: "sent-reports",
            tabs: [
                { label: "Verstuurde overzichten", id: "sent-reports" },
                { label: "Gegevens", id: "info" }
            ]
        };
    },

    created() {
        this.pageTitle = this.administration.name;
    },

    methods: {
        toggleTab(tabID) {
            this.activeTab = tabID;
        },
        deleteAdministration() {
            this.$inertia.post(
                this.route("administrations.delete", {
                    administration: this.administration.id
                })
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
