<template>
    <div>
        <div v-if="administrations.length == 0" class="font-medium opacity-75 ">
            Er zijn nog geen administraties gekoppeld deze gebruiker
        </div>
        <table
            v-if="administrations.length > 0"
            class="w-full mt-4 overflow-hidden divide-y divide-gray-200 rounded-md shadow"
        >
            <thead class="text-xs text-left text-gray-600 uppercase bg-gray-50">
                <tr>
                    <!-- <th
                        class="w-3 p-3 pl-4 text-sm font-medium tracking-wider text-gray-700 uppercase bg-gray-50 "
                    >
                        <div class="flex items-center h-5">
                            <input
                                type="checkbox"
                                ref="input"
                                class="w-4 h-4 border-gray-300 rounded cursor-pointer text-primary form-checkbox"
                            />
                        </div>
                    </th> -->
                    <th
                        class="py-3 pl-5 font-medium tracking-wider"
                        width="350"
                    >
                        Naam
                    </th>
                    <th
                        v-if="!hide.includes('relation_manager')"
                        class="font-medium tracking-wider"
                    >
                        Relatiebeheerder
                    </th>
                    <th class="font-medium tracking-wider" width="250">
                        Contactpersoon
                    </th>
                    <th class="font-medium tracking-wider" width="250">
                        Status
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr
                    v-for="(administration, index) in administrations"
                    :key="administration.id"
                >
                    <!-- <td class="w-3 p-3 py-4 pl-4">
                        <div class="flex items-center h-5">
                            <input
                                type="checkbox"
                                ref="input"
                                class="w-4 h-4 border-gray-300 rounded cursor-pointer text-primary form-checkbox"
                            />
                        </div>
                    </td> -->

                    <td class="py-4 pl-5 font-medium">
                        <inertia-link
                            :href="
                                route('administrations.show', {
                                    id: administration.id
                                })
                            "
                        >
                            {{ administration.name }}
                        </inertia-link>
                    </td>

                    <td v-if="!hide.includes('relation_manager')">
                        {{ administration.relation_manager.first_name }}
                        {{ administration.relation_manager.last_name }}
                    </td>

                    <td>
                        {{ administration.contact_email }}
                    </td>

                    <td>
                        <div v-if="administration.status == 'sent'">
                            <div class="text-xs text-gray-600">
                                Laatst verstuurd op
                            </div>
                            {{ administration.last_sent_report_date_time }}
                        </div>
                        <div
                            v-if="administration.status == 'new'"
                            class="inline-block px-3 py-1 text-xs font-semibold text-purple-900 bg-purple-200 rounded-full"
                        >
                            Nieuw
                        </div>
                        <div
                            v-if="administration.status == 'queued'"
                            class="inline-block px-3 py-1 text-xs font-semibold text-yellow-900 bg-yellow-200 rounded-full "
                        >
                            In de wachtrij
                        </div>
                        <div
                            v-if="administration.status == 'in_progress'"
                            class="inline-block px-3 py-1 text-xs font-semibold text-blue-900 bg-blue-200 rounded-full animate-pulse "
                        >
                            Verwerken
                        </div>
                        <div
                            v-if="administration.status == 'error'"
                            @click="showAdministrationError(administration)"
                            class="inline-block px-3 py-1 text-xs font-semibold text-red-900 bg-red-200 rounded-full cursor-pointer"
                        >
                            Mislukt
                        </div>
                    </td>
                    <td class="p-3 py-3 pr-6 text-right" width="100">
                        <div
                            v-if="
                                administration.status != 'queued' &&
                                    administration.status != 'in_progress'
                            "
                            @click="sendReport(administration, index)"
                        >
                            <div
                                class="font-medium cursor-pointer text-primary"
                            >
                                Verstuur
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <modal-info
            :show="infoModal.show"
            :type="infoModal.type"
            :title="infoModal.title"
            :message="infoModal.message"
            :confirm_text="infoModal.confirm_text"
            @close="infoModal.show = false"
        />
    </div>
</template>

<script>
import Dropdown from "@/Elements/Dropdown";
import ModalInfo from "@/Components/Modals/Info";

export default {
    components: {
        Dropdown,
        ModalInfo
    },
    props: {
        administrations: Array,
        hide: {
            type: Array,
            default: function() {
                return [];
            }
        }
    },

    data() {
        return {
            infoModal: {
                show: false,
                type: "",
                title: "",
                message: "",
                confirm_text: ""
            }
        };
    },

    methods: {
        sendReport(administration, index) {
            let vm = this;
            axios({
                method: "get",
                url: this.route("administrations.send-report", {
                    administration: administration.id
                })
            }).then(response => {
                administration.status = response.data.status;
                vm.pollAdminstration(administration);
            });
        },

        pollAdminstration(administration) {
            if (
                administration.status == "in_progress" ||
                administration.status == "queued"
            ) {
                axios({
                    method: "get",
                    url: this.route("administrations.info", {
                        administration: administration.id
                    })
                }).then(response => {
                    administration.status = response.data.status;
                    administration.last_sent_report_date_time =
                        response.data.last_sent_report_date_time;
                    administration.last_sent_report_author =
                        response.data.last_sent_report_author;
                    administration.error_reason = response.data.error_reason;
                });
                let vm = this;

                setTimeout(function() {
                    vm.pollAdminstration(administration);
                }, 1000);
            }
        },

        showAdministrationError(administration) {
            console.log(this.infoModal);
            this.infoModal.type = "error";
            this.infoModal.title = "Versturen is mislukt";
            this.infoModal.message = administration.error_reason;
            this.infoModal.confirm_text = "Ik begrijp het";
            this.infoModal.show = true;
        }
    }
};
</script>
