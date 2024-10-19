<template>
    <div
        class="fixed inset-0 z-20 flex items-center justify-center"
        @click="closePopoverOnOutsideClick"
    >
        <div class="relative flex w-3/4 p-4 bg-white rounded-lg shadow-lg" @click.stop>
            <!-- Close button (X) -->
            <button
                @click="closePopover"
                class="absolute text-3xl font-bold text-gray-500 top-2 right-2 hover:text-red-500"
            >
                &times;
            </button>

            <!-- Left side: CalendarAction Info -->
            <div class="w-2/3 p-4 border-r border-gray-300">
                <h3 class="my-2 text-xl font-bold">Title: {{ action?.title }}</h3>
                <span
                    v-for="tag in action.tags"
                    :key="tag"
                    class="px-2 py-1 text-green-800 bg-green-100 rounded-full"
                >
                    {{ tag.tag }}
                </span>

                <p class="my-2 text-gray-600">Description: {{ action?.description }}</p>

                <div v-if="selectedEvent">
                    <div class="mb-4">
                        <p class="mb-2 text-gray-600">Client Employees:</p>
                        <div class="p-2">
                            <div v-for="(employee, index) in selectedEvent.client_employees" :key="index" class="card mb-2 p-2 border border-gray-300 rounded">
                                <div class="font-bold">{{ employee.first_name }} {{ employee.last_name }}</div>
                                <div>{{ employee.email }}</div>
                                <div>{{ employee.phone }}</div>
                                <div>
                                    Status: 
                                    <span v-if="employee.accepted === 1">✔ Accepted</span>
                                    <span v-else-if="employee.accepted === 0">✖ Not Accepted</span>
                                    <span v-else>? No Decision Yet</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="mb-2 text-gray-600">Bank Employees:</p>
                        <div class="p-2">
                            <div v-for="(employee, index) in selectedEvent.bank_employees" :key="index" class="card mb-2 p-2 border border-gray-300 rounded">
                                <div class="font-bold">{{ employee.first_name }} {{ employee.last_name }}</div>
                                <div>{{ employee.email }}</div>
                                <div>{{ employee.phone }}</div>
                                <div>
                                    Status: 
                                    <span v-if="employee.accepted === 1">✔ Accepted</span>
                                    <span v-else-if="employee.accepted === 0">✖ Not Accepted</span>
                                    <span v-else>? No Decision Yet</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <p class="text-gray-600">Select a date to schedule a meeting</p>
                </div>
            </div>

            <!-- Right side: List of Events -->
            <div class="w-1/3 p-4">
                <h3 class="mb-2 text-lg font-bold">Proposed Date</h3>
                <ul class="pl-5 text-gray-800 list-disc list-inside">
                    <li
                        v-for="event in action.calendar_events"
                        :key="event.id"
                        class="flex items-center justify-between p-2 rounded"
                    >
                        <CalendarEvent @eventClicked="eventClicked" :event="event" />

                        <div class="flex space-x-2">
                            <button
                                @click="acceptEvent(event)"
                                class="px-2 py-1 text-green-500 transition duration-200 ease-in-out bg-transparent rounded-md hover:bg-green-100 hover:text-yellow-700"
                            >
                                ✔
                            </button>
                            <button
                                @click="rejectEvent(event)"
                                class="px-2 py-1 text-red-500 transition duration-200 ease-in-out bg-transparent rounded-md hover:bg-red-100 hover:text-yellow-700"
                            >
                                ✖
                            </button>
                        </div>
                    </li>
                </ul>
                <button
                    @click="createNewDate"
                    class="w-full p-2 mt-4 text-white bg-blue-500 rounded"
                >
                    Create New Date
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import CalendarEvent from "../CalendarEventComponent.vue";

export default {
    props: {
        action: {
            type: Object,
            required: true,
        },
    },
    components: {
        CalendarEvent,
    },
    data() {
        return {
            selectedEvent: null,
        };
    },
    methods: {
        acceptEvent(event) {
            console.log(`Accepted meeting date: ${event.date}`);
            this.$emit("eventAccepted", event);
        },
        rejectEvent(event) {
            console.log(`Rejected meeting date: ${event.date}`);
            this.$emit("eventRejected", event);
        },
        createNewDate() {
            // Logic to create a new date
            console.log("Creating a new date");
        },
        closePopover() {
            this.$emit("close");
        },
        closePopoverOnOutsideClick(event) {
            if (event.target === this.$el) {
                this.closePopover();
            }
        },
        eventClicked(event) {
            this.selectedEvent = event;
        },
    },
};
</script>

<style scoped>
/* Add your custom styling here */
</style>
