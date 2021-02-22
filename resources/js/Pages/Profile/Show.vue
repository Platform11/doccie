<template>
    <layout>
        <div class="flex flex-wrap w-full pb-12">
            <div class="w-full">
                <div class="flex justify-between w-full">
                    <h1 class="hidden text-gray-900 md:block">
                        Mijn gegevens
                    </h1>
                    <div>
                        <!-- <div class="btn">Verwijderen</div> -->
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
                        <EditGeneralInformation :errors="errors" />
                        <div class="hidden sm:block" aria-hidden="true">
                            <div class="py-8">
                                <div class="border-t border-gray-200"></div>
                            </div>
                        </div>
                        <EditCredentials :errors="errors" />
                        <div class="hidden sm:block" aria-hidden="true">
                            <div class="py-8">
                                <div class="border-t border-gray-200"></div>
                            </div>
                        </div>
                        <EditTwinfieldCredentils
                            :credentials="twinfield_credentials"
                            :errors="errors"
                        />
                        <div class="hidden sm:block" aria-hidden="true">
                            <div class="py-8">
                                <div class="border-t border-gray-200"></div>
                            </div>
                        </div>
                    </div>
                    <div v-show="activeTab == 'administrations'" class="w-full">
                        <TableAdminstrations
                            :administrations="administrations"
                            :hide="['relation_manager']"
                        ></TableAdminstrations>
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from "@/Shared/Layout";
import EditGeneralInformation from "@/Pages/Profile/Edit/General";
import EditCredentials from "@/Pages/Profile/Edit/Credentials";
import EditTwinfieldCredentils from "@/Pages/Profile/Edit/TwinfieldCredentials";
import TableAdminstrations from "@/Components/Tables/Administrations/Base";

export default {
    // metaInfo: { title: this.user.name },
    components: {
        Layout,
        EditGeneralInformation,
        EditCredentials,
        EditTwinfieldCredentils,
        TableAdminstrations
    },
    props: {
        administrations: Array,
        twinfield_credentials: Array,
        errors: Object
    },
    data() {
        return {
            activeTab: "info",
            tabs: [
                { label: "Gegevens", id: "info" },
                { label: "Administraties", id: "administrations" }
            ]
        };
    },

    created() {
        this.pageTitle =
            this.$page.props.auth.user.first_name +
            " " +
            this.$page.props.auth.user.last_name;
    },

    methods: {
        toggleTab(tabID) {
            this.activeTab = tabID;
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
