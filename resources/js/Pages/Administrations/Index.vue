<template>
    <layout>
        <div class="flex flex-wrap w-full pb-12">
            <div class="w-full">
                <div class="flex justify-between w-full">
                    <h1 class="hidden text-gray-900 md:block">
                        Administraties
                    </h1>
                    <div class="flex">
                        <inertia-link :href="route('administrations.create')">
                            <div class="btn btn-primary">Toevoegen</div>
                        </inertia-link>
                        <!-- <div class="btn btn-primary">Verstuur overzichten</div> -->
                    </div>
                </div>

                <div class="w-full mt-6">
                    <TableAdminstrations
                        :administrations="administrations"
                    ></TableAdminstrations>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from "@/Shared/Layout";
import TableAdminstrations from "@/Components/Tables/Administrations";

export default {
    metaInfo: { title: "Gebruikers" },
    components: {
        Layout,
        TableAdminstrations
    },
    props: {
        administrations: Array
    },
    data() {
        return {};
    },

    created() {
        this.pageTitle = "Gebruikers";
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
