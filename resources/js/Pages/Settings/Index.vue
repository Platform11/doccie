<template>
    <layout>
        <div class="flex flex-wrap w-full pb-12">
            <div class="w-full">
                <div class="flex items-center justify-between w-full">
                    <h1 class="hidden text-gray-900 md:block">
                        Instellingen
                    </h1>
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
                        <GeneralInfo :account="account" :errors="errors" />
                    </div>
                    <div v-show="activeTab == 'reports'" class="w-full">
                        Rapportinstellingen
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from "@/Shared/Layout";
import GeneralInfo from "@/Pages/Settings/Edit/General";

export default {
    // metaInfo: { title: this.user.name },
    components: {
        Layout,
        GeneralInfo
    },
    props: {
        account: Object,
        errors: Object
    },
    data() {
        return {
            activeTab: "info",
            showConfirmDelete: false,
            tabs: [
                { label: "Algemeen", id: "info" },
                { label: "Rapporten", id: "reports" }
            ]
        };
    },

    created() {
        this.pageTitle = "Instellingen";
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
