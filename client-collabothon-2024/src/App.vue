<template>
    <div class="grid grid-cols-12 gap-4 p-4">
        <!-- Calendar Island in the first section -->
        <div class="col-span-12 lg:col-span-7 bg-white shadow-md p-6 rounded-lg">
            <h2 class="text-xl font-bold mb-4 text-black">Calendar</h2>
            <CalendarComponent :events="fullCalendarEvents" @event-click="handleEventClick" />
        </div>

        <!-- Events Island with To-Do and Proposed Events stacked in the same column -->
        <div class="col-span-12 lg:col-span-5 space-y-4 flex flex-col">
            <!-- Schedule Consultations -->
            <ScheduleConsultations />

            <!-- To-Do Events Section -->
            <div class="bg-gray-100 shadow-md p-3 rounded-lg flex-1 overflow-y-auto">
                <h2 class="text-lg font-bold mb-4 text-black">To-Do Events</h2>
                <CalendarActionList v-for="todo in todoEvents" :key="todo.id" :action="todo" />
            </div>

            <!-- Proposed Events Section -->
            <div class="bg-gray-100 shadow-md rounded-lg p-3 flex-1 overflow-y-auto">
                <h2 class="text-lg font-bold mb-4 text-black">Proposed Events</h2>
                <CalendarActionList
                    v-for="proposed in proposedEvents"
                    :key="proposed.id"
                    :action="proposed"
                />
            </div>
        </div>
    </div>
</template>

<script>
import CalendarComponent from "./components/CalendarComponent.vue";
import ScheduleConsultations from "./components/ScheduleConsultations.vue";
import CalendarActionList from "./components/CalendarActionListComponent.vue";

export default {
    components: {
        CalendarComponent,
        ScheduleConsultations,
        CalendarActionList,
    },

    data() {
        return {
            todoEvents: [
                {
                    id: 1,
                    title: "Meeting with John Doe Investment",
                    tags: ["meeting", "consultation"],
                    events: [
                        {
                            id: 1,
                            title: "In Office 7",
                            start: "2024-10-18T10:00:00",
                            end: "2024-10-18T11:30:00",
                        },
                        {
                            id: 2,
                            title: "In Office 8",
                            start: "2024-10-25T14:00:00",
                            end: "2024-10-18T15:30:00",
                        },
                    ],
                },
            ],
            proposedEvents: [
                {
                    id: 1,
                    title: "Enhance presence in social media",
                    tags: ["webinar", "social media"],
                    events: [
                        {
                            id: 1,
                            title: "Office 12 Strasse 5",
                            start: "2024-10-22T09:00:00",
                            end: "2024-10-22T10:00:00",
                        },
                        {
                            id: 2,
                            title: "Office 17 Strasse 2",
                            start: "2024-10-27T13:00:00",
                            end: "2024-10-27T14:30:00",
                        },
                    ],
                },
            ],
            selectedEvent: null, // Track the selected event for CalendarAction
        };
    },

    computed: {
        fullCalendarEvents() {
            const events = [];

            this.todoEvents.forEach((todo) => {
                todo.events.forEach((event) => {
                    events.push({
                        id: event.id,
                        title: todo.title,
                        start: event.start,
                        end: event.end,
                        extendedProps: {
                            tags: todo.tags,
                            originalEvent: event,
                            eventType: "todo", // Indicate the type of event
                        },
                    });
                });
            });

            this.proposedEvents.forEach((proposed) => {
                proposed.events.forEach((event) => {
                    events.push({
                        id: event.id,
                        title: proposed.title,
                        start: event.start,
                        end: event.end,
                        extendedProps: {
                            tags: proposed.tags,
                            originalEvent: event,
                            eventType: "proposed", // Indicate the type of event
                        },
                    });
                });
            });

            return events;
        },
    },

    methods: {
        
    },
};
</script>

<style scoped></style>
