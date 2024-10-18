<template>
    <div
        class="fixed inset-0 flex items-center justify-center z-20"
        @click="closePopoverOnOutsideClick"
    >
        <div class="bg-white shadow-lg rounded-lg p-4 w-1/3" @click.stop>
            <h3 class="text-lg font-bold mb-2">Select a Meeting Date</h3>
            <ul class="list-disc list-inside text-gray-800 pl-5">
                <li
                    v-for="event in events"
                    :key="event.id"
                    class="cursor-pointer hover:bg-gray-200 p-2 rounded flex items-center"
                    @click="acceptEvent(event)"
                >
                    <CalendarEvent :event="event" />
                </li>
            </ul>
            <button @click="closePopover" class="text-red-500 mt-2">Close</button>
        </div>
    </div>
</template>

<script>
import CalendarEvent from "../CalendarEventComponent.vue";

export default {
    props: {
        events: {
            type: Array,
            required: true,
        },
    },
    components: {
        CalendarEvent,
    },

    methods: {
        acceptEvent(event) {
            // Logic to handle event acceptance
            console.log(`Accepted meeting date: ${event.date}`);
            this.$emit("eventAccepted", event); // Emit event to parent component
        },
        closePopover() {
            this.$emit("close"); // Emit event to close the popover
        },
        closePopoverOnOutsideClick(event) {
            // Check if the click was outside of the popover
            if (event.target === this.$el) {
                this.closePopover();
            }
        },
    },
};
</script>

<style scoped></style>
