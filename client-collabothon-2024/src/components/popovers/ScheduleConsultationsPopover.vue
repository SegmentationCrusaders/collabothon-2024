<template>
    <div
        class="fixed inset-0 z-20 flex items-center justify-center bg-gray-800 bg-opacity-75"
        @click="closePopoverOnOutsideClick"
    >
        <div class="relative flex w-3/4 p-6 bg-white rounded-lg shadow-lg commerzbank-border" @click.stop>
            <!-- Close button -->
            <button
                @click="closePopover"
                class="absolute text-3xl font-bold text-gray-500 top-2 right-2 hover:text-red-500"
            >
                &times;
            </button>

            <div class="w-full" v-if="generalAdvisor && availableTags.length">
                <h2 class="mb-4 text-lg font-semibold text-gray-800">Preview Email</h2>

                <!-- bagde with template -->
                <div class="flex justify-between mb-4 items center">
                    <div class="flex items-center p-2 rounded-lg comarzbank-bg">
                        <span class="text-sm font-semibold text-white">Subject:</span>
                        <span class="ml-2 text-sm font-semibold text-white">{{ currentTemplate }}</span>
                    </div>
                </div>

                <!-- Mock email content -->
                <div class="p-4 border rounded-md bg-gray-50">
                    <p><strong>From:</strong> {{ $root.loggedUser.email }}</p>
                    <p><strong>To:</strong> {{ generalAdvisor.email }} </p>
                    <p><strong>Subject:</strong> {{ emailTitle }}</p>
                    <hr class="my-2" />

                    <p>Dear {{ generalAdvisor.first_name }} {{ generalAdvisor.last_name }},</p>

                    <br />

                    <p>{{ emailDescription }}</p>

                    <br />

                    <p class="mb-4">Best regards,<br /> {{ $root.loggedUser.first_name }} {{ $root.loggedUser.last_name }}</p>

                    <hr class="thick-hr" />

                    <!-- Editable tags -->
                    <div class="mt-4">
                        <label class="block mb-2 font-bold text-gray-700">Email Title:</label>
                        <input v-model="emailTitle" type="text" class="w-full p-2 border rounded-md" placeholder="Enter email title" />

                        <label class="block mt-4 mb-2 font-bold text-gray-700">Email Description:</label>
                        <textarea v-model="emailDescription"
                            class="w-full p-2 border rounded-md"
                            placeholder="Enter email description"
                            rows="5"    
                        ></textarea>
                    </div>

                    <div class="mt-4">
                        <label class="block mb-2 font-bold text-gray-700">Tags (editable):</label>
                        <Multiselect
                            v-model="selectedTags"
                            :options="availableTags"
                            placeholder="Select tags"
                            label="tag"
                            track-by="uuid"
                            multiple
                            :close-on-select="false"
                            :clear-on-select="false"
                            hide-selected
                            :preserve-search="true"
                        />
                    </div>
                </div>

                <div class="flex justify-end">
                    <button @click="submitEmail" class="px-4 py-2 mt-4 text-white bg-blue-600 rounded hover:bg-blue-700">
                        Submit email
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

export default {
    components: {
        Multiselect,
    },
    props: {
        template: {
            type: String,
            default: "Consultation",
        },
        tags: {
            type: Array,
            default: () => [],
        },
    },
    emits: ["close", "emailSubmitted"],
    data() {
        return {
            currentTemplate: "consultation",
            emailTitle: "Consultation Request",
            emailDescription: `I am reaching out to request your expertise and insights for a consultancy meeting.`,
            availableTags: [],
            selectedTags: [],
            generalAdvisor: null,
        };
    },
    methods: {
        createNewConsultation() {
            this.closePopover();
            console.log("Sending consultation with tags:");
        },

        closePopover() {
            this.selectedTags = [];
            this.$emit("close");
        },

        closePopoverOnOutsideClick(event) {
            if (event.target === this.$el) {
                this.closePopover();
            }
        },

        loadGeneralAdvisor() {
            axios.get("/bank-employees/getGeneralAdvisor", {

            })
            .then((response) => {
                this.generalAdvisor = response.data.data;
            })
            .catch((error) => {
                console.error("Error loading General Advisor:", error);
            });
        },

        loadCalendarActionTags() {
            axios.get("/calendar-action-tags", {

            })
            .then((response) => {
                this.availableTags = response.data.data;
            })
            .catch((error) => {
                console.error("Error loading Calendar Action Tags:", error);
            });
        },

        submitEmail() {
            axios.post('/calendar-actions', {
                title: this.emailTitle,
                description: this.emailDescription,
                tags: this.selectedTags.map((tag) => tag.uuid),
            }).then((response) => {
                this.$emit("emailSubmitted");
                this.closePopover();
            }).catch((error) => {
                console.error("Error submitting email:", error);
            });
        },
    },

    mounted() {
        this.loadGeneralAdvisor();
        this.loadCalendarActionTags();

        this.currentTemplate = this.template;
        this.selectedTags = this.tags;
    },
};
</script>

<style scoped>
.thick-hr {
    height: 4px;
    background-color: black;
    border: none;
}
</style>