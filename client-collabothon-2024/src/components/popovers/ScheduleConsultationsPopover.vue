<template>
    <div
        class="fixed inset-0 z-20 flex items-center justify-center"
        @click="closePopoverOnOutsideClick"
    >
        <div class="relative flex w-3/4 p-6 bg-white rounded-lg shadow-lg" @click.stop>
            <!-- Close button -->
            <button
                @click="closePopover"
                class="absolute text-3xl font-bold text-gray-500 top-2 right-2 hover:text-red-500"
            >
                &times;
            </button>

            <div class="w-full">
                <h2 class="mb-4 text-lg font-semibold">Preview Email</h2>

                <!-- Mock email content -->
                <div class="p-4 border rounded-md">
                    <p><strong>From:</strong> consultations@company.com</p>
                    <p><strong>To:</strong> here put general consultant email</p>
                    <p><strong>Subject:</strong>Consultations Required</p>
                    <hr class="my-2" />

                    <p>Dear general consultant,</p>
                    <p>You are invited to a consultation on the following details:</p>
                    <ul class="ml-4 list-disc">
                        <li><strong>Date:</strong> {{ Date.now() }}</li>
                    </ul>

                    <p>If you have any questions, feel free to contact us.</p>
                    <p>Best regards,<br />The Consultation Team</p>

                    <!-- Editable tags -->
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
                            clear-on-select="false"
                            hide-selected
                            :preserve-search="true"
                        />
                    </div>
                </div>

                <button
                    @click="createNewConsultation"
                    class="px-4 py-2 mt-4 text-white bg-blue-600 rounded-md hover:bg-blue-700"
                >
                    Send Consultation
                </button>
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
    data() {
        return {
            availableTags: [],
            selectedTags: [],
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
    },

    mounted() {
        axios
            .get("/calendar-action-tags", {})
            .then((response) => {
                this.availableTags = response.data.data;
            })
            .catch((error) => {
                console.error("Error loading Calendar Action Tags:", error);
            });
    },
};
</script>
