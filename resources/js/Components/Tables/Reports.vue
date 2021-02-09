<template>
    <div>
        <div v-if="reports.length == 0" class="font-medium opacity-75 ">
            Er zijn nog geen overzichten verstuurd voor deze administratie
        </div>
        <table
            v-if="reports.length > 0"
            class="w-full mt-4 overflow-hidden divide-y divide-gray-200 rounded-md shadow"
        >
            <thead class="text-xs text-left text-gray-600 uppercase bg-gray-50">
                <tr>
                    <th class="p-3 pl-4 font-medium tracking-wider">
                        Naam
                    </th>
                    <th class="font-medium tracking-wider">
                        Transacties
                    </th>
                    <th class="font-medium tracking-wider">
                        Verstuurd naar
                    </th>
                    <th class="font-medium tracking-wider" width="225">
                        Status
                    </th>
                    <th class="font-medium tracking-wider">
                        Verstuurd door
                    </th>
                    <th class="font-medium tracking-wider">
                        Vertstuurd op
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="report in reports" :key="report.id">
                    <td class="p-3 py-4 pl-4" width="200">
                        {{ report.title }}
                    </td>

                    <td>
                        {{ JSON.parse(report.data).transactions }}
                    </td>

                    <td>
                        {{ report.sent_to }}
                    </td>

                    <td>
                        <div
                            v-if="report.sent_status == 'in_transit'"
                            class="inline-block px-2 py-1 text-xs font-semibold text-blue-900 bg-blue-200 rounded-full cursor-pointer"
                            @click="setStatusInfoToInTransit(report)"
                        >
                            Onderweg
                        </div>
                        <div
                            v-if="report.sent_status == 'delivered'"
                            class="inline-block px-2 py-1 text-xs font-semibold text-green-900 bg-green-200 rounded-full cursor-pointer"
                            @click="setStatusInfoToDelivered(report)"
                        >
                            Afgeleverd
                        </div>
                        <div
                            v-if="report.sent_status == 'failed'"
                            class="inline-block px-2 py-1 text-xs font-semibold text-red-900 bg-red-200 rounded-full cursor-pointer"
                            @click="setStatusInfoToFailed(report)"
                        >
                            Probleem bij versturen
                        </div>
                    </td>

                    <td>
                        {{ report.author.first_name }}
                        {{ report.author.last_name }}
                    </td>
                    <td>
                        {{ report.created_at }}
                    </td>
                </tr>
            </tbody>
        </table>
        <modal-info
            :show="infoModal.show"
            :type="infoModal.type"
            :title="infoModal.title"
            :message="infoModal.message"
            :additionalHtml="infoModal.additionalHtml"
            :confirm_text="infoModal.confirm_text"
            @close="infoModal.show = false"
        />
    </div>
</template>

<script>
import ModalInfo from "@/Components/Modals/Info";

export default {
    components: {
        ModalInfo
    },
    props: {
        reports: Array
    },

    data() {
        return {
            infoModal: {
                show: false,
                type: "",
                title: "",
                message: "",
                confirm_text: "",
                additionalHtml: ""
            }
        };
    },

    methods: {
        setStatusInfoToInTransit(report) {
            this.infoModal.type = "info";
            this.infoModal.title = "Overzicht is onderweg";
            this.infoModal.message =
                'Het overzicht is nog niet bij alle geadresseerden afgeleverd. Zodra dat het geval is komt de status op "Afgeleverd" te staan. Gaat er iets mis? Dan veranderd de status in "Mislukt"';
            this.infoModal.confirm_text = "Sluiten";
            this.infoModal.additionalHtml = this.generateSendInfoHTML(report);
            this.infoModal.show = true;
        },

        setStatusInfoToDelivered(report) {
            this.infoModal.type = "success";
            this.infoModal.title = "Overzicht is afgeleverd";
            this.infoModal.message =
                "Het emailbericht is geacepteerd door de mailservers van de ontvangende partijen. Je kan er in principe vanuit gaan dat de mail goed is aangekomen bij alle geadresseerden";
            this.infoModal.additionalHtml = this.generateSendInfoHTML(report);
            this.infoModal.confirm_text = "Sluiten";
            this.infoModal.show = true;
        },

        setStatusInfoToFailed(report) {
            this.infoModal.type = "error";
            this.infoModal.title = "Er is iets misgegaan";
            this.infoModal.additionalHtml = this.generateSendInfoHTML(report);
            this.infoModal.message =
                "Het overzicht is niet bij alle geadresseerden goed aangekomen. Neem voor meer informatie contact op met de applicatiebeheerder";
            this.infoModal.confirm_text = "Sluiten";
            this.infoModal.show = true;
        },

        generateSendInfoHTML(report) {
            let html = '<table class="w-full text-left text-gray-600">';

            report.notifications.forEach(function(element) {
                let iconColor = "text-gray-400";

                if (element.delivery_status == "delivered") {
                    iconColor = "text-green-600";
                }

                if (element.delivery_status == "bounced") {
                    iconColor = "text-red-500";
                }

                html += "<tr>";
                html += "<td>";
                html += "<div class='py-1'>";
                html += element.recipient;
                html += "</div>";
                html += "</td>";

                if (element.delivery_status == "delivered") {
                    html +=
                        "<td class='text-xs font-medium text-right text-green-600'>Afgeleverd</td>";
                } else if (element.delivery_status == "bounced") {
                    html +=
                        "<td class='text-xs font-medium text-right text-red-600'>MisluktR</td>";
                } else {
                    html +=
                        "<td class='text-xs font-medium text-right text-gray-400'>Onderweg</td>";
                }

                html += "</td>";
            });

            html += "</table>";

            return html;
        }
    }
};
</script>
