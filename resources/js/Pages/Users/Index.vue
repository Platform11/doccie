<template>
    <layout>
        <div class="flex flex-wrap w-full pb-12">
            <div class="w-full">
                <div class="flex justify-between w-full">
                    <h1 class="hidden text-gray-900 md:block">
                        Gebruikers
                    </h1>
                    <div>
                        <inertia-link :href="route('users.create')">
                            <div class="btn btn-primary">Toevoegen</div>
                        </inertia-link>
                    </div>
                </div>

                <div class="w-full mt-6">
                    <table
                        class="w-full mt-4 overflow-hidden divide-y divide-gray-200 rounded-md shadow"
                    >
                        <thead
                            class="text-xs text-left text-gray-600 uppercase bg-gray-50"
                        >
                            <tr>
                                <th
                                    class="py-3 pl-5 font-medium tracking-wider"
                                >
                                    Naam
                                </th>
                                <th class="font-medium tracking-wider">
                                    Email
                                </th>
                                <th class="font-medium tracking-wider">
                                    Administraties
                                </th>
                                <th class="font-medium tracking-wider">
                                    Status
                                </th>
                                <th class="font-medium tracking-wider">
                                    Laaste login
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="user in users" :key="user.id">
                                <td class="py-4 pl-5 font-medium">
                                    <inertia-link
                                        :href="
                                            route('users.show', { id: user.id })
                                        "
                                    >
                                        {{ user.first_name }}
                                        {{ user.last_name }}
                                    </inertia-link>
                                </td>

                                <td>
                                    <inertia-link
                                        :href="
                                            route('users.show', { id: user.id })
                                        "
                                    >
                                        {{ user.email }}
                                    </inertia-link>
                                </td>
                                <td>
                                    {{ user.administration_count }}
                                </td>
                                <td>
                                    <div
                                        v-if="user.status == 'active'"
                                        class="inline-block px-2 py-1 text-xs font-semibold text-green-900 bg-green-200 rounded-full cursor-pointer px-"
                                    >
                                        Actief
                                    </div>
                                    <div
                                        v-if="user.status == 'invited'"
                                        class="inline-block px-2 py-1 text-xs font-semibold text-purple-900 bg-purple-200 rounded-full cursor-pointer"
                                    >
                                        Uitgenodigd
                                    </div>
                                </td>
                                <td>
                                    {{ user.last_login }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from "@/Shared/Layout";
import InputCheckbox from "../../Elements/InputCheckbox.vue";
import Dropdown from "@/Elements/Dropdown";

export default {
    metaInfo: { title: "Gebruikers" },
    components: {
        Layout,
        Dropdown,
        InputCheckbox
    },
    props: {
        users: Array
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
