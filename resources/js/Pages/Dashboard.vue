<template>
    <layout>
        <div class="flex flex-wrap w-full pb-12">
            <div class="w-full">
                <h1 class="hidden text-gray-900 md:block">
                    Administraties
                </h1>

                {{ data }}
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from "@/Shared/Layout";

export default {
    metaInfo: { title: "Dashboard" },
    components: {
        Layout
    },
    props: {
        data: String
    },
    data() {
        return {};
    },

    created() {
        this.pageTitle = "Dashboard";
    },

    methods: {},
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
