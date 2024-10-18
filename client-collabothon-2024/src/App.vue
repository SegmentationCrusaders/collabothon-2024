<template>
    <div class="grid grid-cols-12 gap-4 p-4">
        <!-- Calendar Island in the first section -->
        <div class="col-span-12 p-6 bg-white rounded-lg shadow-md lg:col-span-7">
            <h2 class="mb-4 text-xl font-bold text-black">Calendar</h2>
            <CalendarComponent
                :events="fullCalendarEvents"
                @calendar-event-click="handleCalendarEventClick"
            />
        </div>

        <!-- Events Island with CalendarActions stacked in the same column -->
        <div class="flex flex-col col-span-12 space-y-4 lg:col-span-5">
            <!-- Schedule Consultations -->
            <ScheduleConsultations />

            <!-- Urgent CalendarActions Section -->
            <div class="flex-1 p-3 overflow-y-auto bg-gray-100 rounded-lg shadow-md">
                <h2 class="mb-4 text-lg font-bold text-black">To-Do Events</h2>
                <CalendarActionList
                    v-for="todo in todoEvents"
                    :key="todo.id"
                    @calendar-action-click="handleCalendarEventClick"
                    :action="todo"
                />
            </div>

            <!-- Proposed CalendarActions Section -->
            <div class="flex-1 p-3 overflow-y-auto bg-gray-100 rounded-lg shadow-md">
                <h2 class="mb-4 text-lg font-bold text-black">Proposed Events</h2>
                <CalendarActionList
                    v-for="proposed in proposedEvents"
                    @calendar-action-click="handleCalendarEventClick"
                    :key="proposed.id"
                    :action="proposed"
                />
            </div>
        </div>

        <!-- Popover for displaying selected CalendarAction -->
        <CalendarActionPopover
            v-if="selectedCalendarAction"
            :action="selectedCalendarAction"
            @eventAccepted="handleEventAccepted"
            @close="closePopover"
        />
    </div>
</template>

<script>
import CalendarComponent from "./components/CalendarComponent.vue";
import ScheduleConsultations from "./components/ScheduleConsultations.vue";
import CalendarActionList from "./components/CalendarActionListComponent.vue";
import CalendarActionPopover from "./components/popovers/CalendarActionPopover.vue";

export default {
    components: {
        CalendarComponent,
        ScheduleConsultations,
        CalendarActionList,
        CalendarActionPopover,
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
                {
                    id: 2,
                    title: "Test new product features",
                    tags: ["webinar", "social media"],
                    events: [
                        {
                            id: 3,
                            title: "312",
                            start: "2024-10-22T09:00:00",
                            end: "2024-10-22T10:00:00",
                        },
                        {
                            id: 4,
                            title: "123",
                            start: "2024-10-27T13:00:00",
                            end: "2024-10-27T14:30:00",
                        },
                    ],
                },
            ],
            selectedEvent: null, // Track the selected event for CalendarAction
            selectedCalendarAction: null,
            calendarEventToCalendarActionMap: {}, // Map to store the relationship between calendar events and actions
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
        handleCalendarEventClick(eventId) {
            console.log("Calendar Event Clicked:", eventId);
            const calendarAction = this.calendarEventToCalendarActionMap[eventId];

            if (calendarAction) {
                this.selectedCalendarAction = calendarAction.parentCalendarAction;
                console.log("Selected CalendarAction:", this.selectedCalendarAction);
            } else {
                console.error("Event not found in the map!");
            }
        },

        /**
         * Build a map of event IDs to CalendarAction objects.
         * This allows us to quickly look up the parent action and event type when an event is clicked.
         * The key is the event ID and the value is an object with the parent action and event type.
         * The parent action is the CalendarAction object that the event belongs to.
         */
        buildCalendarEventToCalendarActionMap() {
            this.todoEvents.forEach((todo) => {
                todo.events.forEach((event) => {
                    this.calendarEventToCalendarActionMap[event.id] = {
                        parentCalendarAction: todo,
                    };
                });
            });

            this.proposedEvents.forEach((proposed) => {
                proposed.events.forEach((event) => {
                    this.calendarEventToCalendarActionMap[event.id] = {
                        parentCalendarAction: proposed,
                    };
                });
            });

            console.log("Calendar Event Map:", this.calendarEventToCalendarActionMap);
        },

        closePopover() {
            this.selectedCalendarAction = null;
        },
    },

    mounted() {
        this.buildCalendarEventToCalendarActionMap();
    },
};
</script>

<style scoped></style>
