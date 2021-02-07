<template>
    <div>
        <div class="mb-2">
            <label class="text-sm font-medium text-gray-700">
                {{ trans.get("tasks.tasks") }}
            </label>
        </div>
        <div class="mb-3" v-if="tasks.length > 0">
            <div
                class="flex justify-between w-full p-2 px-3 mb-2 bg-gray-100 rounded-md"
                v-for="(task, index) in tasks"
                :key="index"
                @click="openTaskModal(task, index)"
            >
                <div class="flex flex-wrap">
                    <div class="flex w-full">
                        <!-- <div>
            <div
              class="inline-block w-4 h-4 mt-1 mb-2 mr-2 border-2 border-gray-300 rounded-full"
            ></div>
          </div> -->
                        <div class="flex flex-wrap w-full cursor-pointer">
                            <div
                                class="w-full leading-none"
                                style="margin-top: 3px; margin-bottom: 4px;"
                            >
                                {{ task.name }}
                            </div>
                            <div class="w-full text-xs truncate opacity-50">
                                {{ trans.get("tasks.assigned_to") }}:
                                {{ taskAssigneeLabel(task.assignee) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <i
                        @click.stop="removeTask(index)"
                        class="opacity-50 cursor-pointer jam jam-trash"
                    ></i>
                </div>
            </div>
        </div>

        <div class="">
            <button
                v-on:click="openTaskModal()"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out rounded-md ring-2 ring-opacity-0 ring-red-500 hover:ring-opacity-40 bg-primary hover:bg-primary-light focus:outline-none active:bg-primary-dark sm:leading-5"
            >
                {{ trans.get("tasks.add_task") }}
            </button>
        </div>
        <modal
            :show="showTaskModal"
            @close="close"
            class="max-w-lg m-2 sm:mx-auto"
        >
            <div
                class="relative z-10 flex w-full bg-white rounded-lg shadow-xl"
            >
                <div class="w-full px-4 py-5 sm:p-6">
                    <h2 class="mb-3 text-xl font-medium">
                        {{ trans.get("tasks.add_task") }}
                    </h2>
                    <TextInput
                        class="mb-4"
                        :label="trans.get('tasks.task')"
                        v-model="task.name"
                        :errors="errors.name"
                    ></TextInput>
                    <TextareaInput
                        class="mb-2"
                        :label="trans.get('general.description')"
                        v-model="task.description"
                    ></TextareaInput>
                    <SelectInput
                        :label="trans.get('tasks.assign_task_to')"
                        v-model="task.assignee"
                        class="mb-2"
                        :errors="errors.assignee"
                    >
                        <option value="0" disabled>{{
                            trans.get("general.make_a_choice")
                        }}</option>
                        <option value="rsm">RSM</option>
                        <option value="sdm">SDM</option>
                        <option value="station">{{
                            trans.get("stations.station_manager")
                        }}</option>
                        <option value="techniek">{{
                            trans.get("general.technical_department")
                        }}</option>
                        <option value="hseq">HSEQ</option>
                        <option value="servauto-rcs">Servauto RCS</option>
                    </SelectInput>
                    <button
                        v-if="taskModalMode == 'create'"
                        v-on:click="processTask()"
                        class="inline-flex items-center justify-center px-4 py-2 mt-4 text-sm font-medium text-white transition duration-150 ease-in-out rounded-md ring-2 ring-opacity-0 ring-red-500 hover:ring-opacity-40 bg-primary hover:bg-primary-light focus:outline-none active:bg-primary-dark sm:leading-5"
                    >
                        {{ trans.get("tasks.add_task") }}
                    </button>
                    <button
                        v-if="taskModalMode == 'edit'"
                        v-on:click="processTask(task.index)"
                        class="inline-flex items-center justify-center px-4 py-2 mt-4 text-sm font-medium text-white transition duration-150 ease-in-out rounded-md ring-2 ring-opacity-0 ring-red-500 hover:ring-opacity-40 bg-primary hover:bg-primary-light focus:outline-none active:bg-primary-dark sm:leading-5"
                    >
                        {{ trans.get("tasks.update_task") }}
                    </button>
                    <div
                        class="inline-block pl-4 text-gray-600 cursor-pointer"
                        @click="close()"
                    >
                        {{ trans.get("general.cancel") }}
                    </div>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
import Modal from "@/Components/Modals/Base";
import SelectInput from "@/Elements/SelectInput";
import TextareaInput from "@/Elements/TextareaInput";
import TextInput from "@/Elements/TextInput";

export default {
    components: {
        SelectInput,
        TextareaInput,
        TextInput,
        Modal
    },

    data() {
        return {
            assignables: [
                { value: "rsm", label: "RSM" },
                { value: "sdm", label: "SDM" },
                { value: "station", label: "Stationsmanager" },
                {
                    value: "techniek",
                    label: this.trans.get("general.technical_department")
                },
                { value: "hseq", label: "HSEQ" },
                { value: "servauto-rcs", label: "Servauto RCS" }
            ],
            showTaskModal: false,
            taskModalMode: "create",
            task: { name: "", description: "", assignee: 0, index: -1 },
            errors: { name: [], assignee: [] },
            tasks: []
        };
    },

    methods: {
        openTaskModal(task = false, index = -1) {
            if (!task) {
                this.taskModalMode = "create";

                this.task.name = "";
                this.task.description = "";
                this.task.index = -1;
            } else {
                this.taskModalMode = "edit";
                this.task = JSON.parse(JSON.stringify(task));
            }

            this.showTaskModal = true;
        },

        addTask() {
            if (this.taskIsValid()) {
                this.task.index = this.tasks.length;
                this.tasks.push(JSON.parse(JSON.stringify(this.task)));
                this.$emit("tasksChanged", this.tasks);

                this.task.name = "";
                this.task.description = "";
                this.task.index = -1;

                this.close();
            }
        },

        editTask(index) {
            if (this.taskIsValid()) {
                this.task.index = index;
                this.tasks[index] = JSON.parse(JSON.stringify(this.task));

                this.task.name = "";
                this.task.description = "";
                this.task.index = -1;

                this.$emit("tasksChanged", this.tasks);

                this.close();
            }
        },

        removeTask(key) {
            this.tasks.splice(key, 1);
            this.$emit("tasksChanged", this.tasks);
        },

        taskIsValid() {
            let isValid = true;

            if (this.task.name == "") {
                this.errors.name = ["De actie mag niet leeg zijn"];
                isValid = false;
            } else {
                this.errors.name = [];
            }

            if (this.task.assignee == 0) {
                this.errors.assignee = ["Kies een verantwoordelijke"];
                isValid = false;
            } else {
                this.errors.assignee = [];
            }

            return isValid;
        },

        close() {
            this.showTaskModal = false;
        },

        taskAssigneeLabel($value) {
            let vm = this;
            let label;
            this.assignables.forEach(function(element) {
                if ($value == element.value) {
                    label = element.label;
                }
            });

            return label;
        },

        processTask(index) {
            if (this.taskModalMode == "create") {
                this.addTask();
            } else if (this.taskModalMode == "edit") {
                this.editTask(index);
            }
        }
    }
};
</script>
