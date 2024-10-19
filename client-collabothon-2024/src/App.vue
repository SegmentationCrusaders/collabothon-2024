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
            <div class="flex-1 px-2 overflow-y-auto bg-gray-100 rounded-lg shadow-md">
                <div class="sticky top-0 bg-gray-100 py-2">
                    <h2 class="mb-4 text-lg font-bold text-black">Proposed Actions</h2>
                </div>
                <span v-if="isLoadingProposedActions">
                    Loading proposed actions...
                </span>
                <CalendarActionList
                    v-for="proposed in proposedActions"
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

    <div class="flex space-x-4 p-4">
        <button 
            v-on:click="switchToCEO" 
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Switch to CEO
        </button>
        <button 
            v-on:click="switchToController" 
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Switch to Controller
        </button>
        <button 
            v-on:click="switchToCashManagementSpecialist" 
            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
            Switch to Cash Management Specialist
        </button>   
        <button 
            v-on:click="switchToAccountant" 
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            Switch to Accountant
        </button>
        <button 
            v-on:click="switchToCommerzbankAdmin" 
            class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
            Switch to Commerzbank Admin
        </button>
        <div class="p-4">
            <p class="text-lg font-bold">Current Role: {{ currentRole }}</p>
        </div>
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
            roles: {
                '2rqkCplZPDNNibrXTAyA576IeOLu18ASBiuer0oqmXuCruwJ5WAaF2KvAa9pCRh2': 'CEO',
                'koP7tEvVel3gfG0gWOjG3bTgrzo1ubzbfsD5vKll2mjVM263aEGPHhIZSIMWNdy1': 'Controller',
                'Yk7lYm6LaZwpeDW4yJucFVJ5UaqfWbL9Hc9t5SjmgmZXs03HWZQnaBFErTANrFgm': 'Cash Management Specialist',
                'BB4I3gN8OJZPFfVt4fWuYUYxhvnB0jS6feg0KQCx0u33EIl6aCgbx7qZ1VJOxsm0': 'Accountant',
                's0G3UTt79wsL4wgIHHBea7ptulrXpCvxIOYRXBdM5rOIbIdasOhAaKRWSJQG1XrU': 'Commerzbank Admin',
            },
            isLoadingProposedActions: false,
            proposedActions: [],
            selectedEvent: null, // Track the selected event for CalendarAction
            selectedCalendarAction: null,
            calendarEventToCalendarActionMap: {}, // Map to store the relationship between calendar events and actions
        };
    },

    computed: {
        currentRole() {
            const token = localStorage.getItem('bearer_token');
        
            return this.roles[token] || 'Unknown';
        },

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

            this.proposedActions.forEach((proposed) => {
                if (proposed.events) {
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
                }
            });

            return events;
        },
    },

    methods: {
        switchToCEO() {
            let token = '2rqkCplZPDNNibrXTAyA576IeOLu18ASBiuer0oqmXuCruwJ5WAaF2KvAa9pCRh2';
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            localStorage.setItem('bearer_token', token);
            window.location.reload();
        },
        
        switchToController() {
            let token = 'koP7tEvVel3gfG0gWOjG3bTgrzo1ubzbfsD5vKll2mjVM263aEGPHhIZSIMWNdy1';
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            localStorage.setItem('bearer_token', token);
            window.location.reload();
        },

        switchToCashManagementSpecialist() {
            let token = 'Yk7lYm6LaZwpeDW4yJucFVJ5UaqfWbL9Hc9t5SjmgmZXs03HWZQnaBFErTANrFgm';
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            localStorage.setItem('bearer_token', token);
            window.location.reload();
        },

        switchToAccountant() {
            let token = 'BB4I3gN8OJZPFfVt4fWuYUYxhvnB0jS6feg0KQCx0u33EIl6aCgbx7qZ1VJOxsm0';
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            localStorage.setItem('bearer_token', token);
            window.location.reload();
        },
        
        switchToCommerzbankAdmin() {
            let token = 's0G3UTt79wsL4wgIHHBea7ptulrXpCvxIOYRXBdM5rOIbIdasOhAaKRWSJQG1XrU';
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            localStorage.setItem('bearer_token', token);
            window.location.reload();
        },

        loadProposedActions() {
            this.isLoadingProposedActions = true;

            axios.get('/calendar-action-templates', {

            }).then((response) => {
                this.proposedActions = response.data.data;

                this.buildCalendarEventToCalendarActionMap();
            }).catch((error) => {
                console.error("Error loading proposed actions:", error);
            }).finally(() => {
                this.isLoadingProposedActions = false;
            });
        },

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

            this.proposedActions.forEach((proposed) => {
                if (proposed.events) {
                    proposed.events.forEach((event) => {
                        this.calendarEventToCalendarActionMap[event.id] = {
                            parentCalendarAction: proposed,
                        };
                    });
                }
            });

            console.log("Calendar Event Map:", this.calendarEventToCalendarActionMap);
        },

        closePopover() {
            this.selectedCalendarAction = null;
        },
    },

    mounted() {
        if (localStorage.getItem('bearer_token')) {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('bearer_token')}`;
        } else {
            this.switchToCEO();
        }

        this.loadProposedActions();

        this.buildCalendarEventToCalendarActionMap();
    },
};
</script>

<style scoped></style>
