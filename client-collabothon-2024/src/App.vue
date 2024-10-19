<template>
    <CalendarActionIdeaCarousel @onCreate="createEmailFromTemplate" />
    
    <div class="grid grid-cols-12 gap-4 p-4">
        <!-- Calendar Island in the first section -->
        <div class="col-span-12 p-6 bg-white commerzbank-shadow lg:col-span-7 border rounded-lg commerzbank-border">
            <h2 class="mb-4 text-xl font-bold text-black">Calendar</h2>
            <CalendarComponent
                :events="allCalendarEvents"
                @calendar-event-click="handleCalendarEventClick"
            />
        </div>

        <!-- Events Island with CalendarActions stacked in the same column -->
        <div class="flex flex-col col-span-12 space-y-4 lg:col-span-5">
            <!-- Schedule Consultations -->
            <ScheduleConsultations
                @show-schedule-consultations-popover="showScheduleConsultationsPopover"
            />

            <!-- Urgent CalendarActions Section -->
            <div class="flex-1 p-3 overflow-y-auto bg-gray-100 commerzbank-shadow max-h-96 border rounded-lg commerzbank-border">
                <h2 class="mb-4 text-lg font-bold text-black">Urgent Actions</h2>
                <CalendarActionList
                    v-for="todo in todoEvents"
                    :key="todo.id"
                    @calendar-action-click="handleCalendarActionClick"
                    :action="todo"
                />
            </div>

            <!-- Proposed CalendarActions Section -->
            <div class="flex-1 p-3 overflow-y-auto bg-gray-100 commerzbank-shadow max-h-96 border rounded-lg commerzbank-border">
                <div class="sticky top-0 bg-gray-100">
                    <h2 class="mb-4 text-lg font-bold text-black">Proposed Actions</h2>
                </div>
                <span v-if="isLoadingProposedActions">Loading proposed actions...</span>
                <CalendarActionList
                    v-for="proposed in proposedActions"
                    @calendar-action-click="handleCalendarActionClick"
                    :key="proposed.id"
                    :action="proposed"
                />
            </div>
        </div>

        <!-- Popover for displaying selected CalendarAction -->
        <Transition name="fade-popover">
            <CalendarActionPopover
                v-if="selectedCalendarAction"
                :action="selectedCalendarAction"
                @eventAccepted="handleEventAccepted"
                @close="closePopover"
            />
        </Transition>

        <!-- Popover for displaying SchedulingConsultation -->
        <Transition name="fade-popover">
            <ScheduleConsultationsPopover
                v-if="shouldDisplayScheduleConsultationsPopover"
                :template="template"
                @close="closePopover"
            />
        </Transition>
    </div>

    <div class="flex p-4 space-x-4">
        <button
            v-on:click="switchToCEO"
            class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700"
        >
            Switch to CEO
        </button>
        <button
            v-on:click="switchToController"
            class="px-4 py-2 font-bold text-white bg-green-500 rounded hover:bg-green-700"
        >
            Switch to Controller
        </button>
        <button
            v-on:click="switchToCashManagementSpecialist"
            class="px-4 py-2 font-bold text-white bg-yellow-500 rounded hover:bg-yellow-700"
        >
            Switch to Cash Management Specialist
        </button>
        <button
            v-on:click="switchToAccountant"
            class="px-4 py-2 font-bold text-white bg-red-500 rounded hover:bg-red-700"
        >
            Switch to Accountant
        </button>
        <button
            v-on:click="switchToCommerzbankAdmin"
            class="px-4 py-2 font-bold text-white bg-purple-500 rounded hover:bg-purple-700"
        >
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
import ScheduleConsultationsPopover from "./components/popovers/ScheduleConsultationsPopover.vue";
import CalendarActionIdeaCarousel from "./components/CalendarActionIdeaCarousel.vue";

export default {
    components: {
        CalendarComponent,
        ScheduleConsultations,
        CalendarActionList,
        CalendarActionPopover,
        ScheduleConsultationsPopover,
        CalendarActionIdeaCarousel,
    },

    data() {
        return {
            todoEvents: [],
            proposedEvents: [],
            allCalendarEvents: [],
            template: 'consultation',

            roles: {
                "2rqkCplZPDNNibrXTAyA576IeOLu18ASBiuer0oqmXuCruwJ5WAaF2KvAa9pCRh2": "CEO",
                koP7tEvVel3gfG0gWOjG3bTgrzo1ubzbfsD5vKll2mjVM263aEGPHhIZSIMWNdy1: "Controller",
                Yk7lYm6LaZwpeDW4yJucFVJ5UaqfWbL9Hc9t5SjmgmZXs03HWZQnaBFErTANrFgm:
                    "Cash Management Specialist",
                BB4I3gN8OJZPFfVt4fWuYUYxhvnB0jS6feg0KQCx0u33EIl6aCgbx7qZ1VJOxsm0: "Accountant",
                s0G3UTt79wsL4wgIHHBea7ptulrXpCvxIOYRXBdM5rOIbIdasOhAaKRWSJQG1XrU:
                    "Commerzbank Admin",
            },
            isLoadingProposedActions: false,
            proposedActions: [],
            selectedEvent: null, // Track the selected event for CalendarAction
            selectedCalendarAction: null,
            calendarEventToCalendarActionMap: {}, // Map to store the relationship between calendar events and actions
            shouldDisplayScheduleConsultationsPopover: false,
        };
    },

    computed: {
        currentRole() {
            const token = localStorage.getItem("bearer_token");

            return this.roles[token] || "Unknown";
        },
    },

    methods: {
        switchToCEO() {
            let token = "2rqkCplZPDNNibrXTAyA576IeOLu18ASBiuer0oqmXuCruwJ5WAaF2KvAa9pCRh2";
            window.axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
            localStorage.setItem("bearer_token", token);
            window.location.reload();
        },

        switchToController() {
            let token = "koP7tEvVel3gfG0gWOjG3bTgrzo1ubzbfsD5vKll2mjVM263aEGPHhIZSIMWNdy1";
            window.axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
            localStorage.setItem("bearer_token", token);
            window.location.reload();
        },

        switchToCashManagementSpecialist() {
            let token = "Yk7lYm6LaZwpeDW4yJucFVJ5UaqfWbL9Hc9t5SjmgmZXs03HWZQnaBFErTANrFgm";
            window.axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
            localStorage.setItem("bearer_token", token);
            window.location.reload();
        },

        switchToAccountant() {
            let token = "BB4I3gN8OJZPFfVt4fWuYUYxhvnB0jS6feg0KQCx0u33EIl6aCgbx7qZ1VJOxsm0";
            window.axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
            localStorage.setItem("bearer_token", token);
            window.location.reload();
        },

        switchToCommerzbankAdmin() {
            let token = "s0G3UTt79wsL4wgIHHBea7ptulrXpCvxIOYRXBdM5rOIbIdasOhAaKRWSJQG1XrU";
            window.axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
            localStorage.setItem("bearer_token", token);
            window.location.reload();
        },

        parseFullCalendarEvents() {
            const events = [];

            console.log("Todo Events:", this.todoEvents);

            this.todoEvents.forEach((todo) => {
                if (todo.calendar_events) {
                    todo.calendar_events.forEach((calendarEvent) => {
                        events.push({
                            id: calendarEvent.uuid,
                            title: calendarEvent.title,
                            start: calendarEvent.start_date,
                            end: calendarEvent.end_date,
                            extendedProps: {
                                tags: todo.tags || [],
                                originalEvent: calendarEvent,
                            },
                        });
                    });
                }
            });

            console.log("Full Calendar Events:", events);

            this.allCalendarEvents = events;
        },

        loadProposedActions() {
            this.isLoadingProposedActions = true;

            axios
                .get("/calendar-action-templates", {})
                .then((response) => {
                    this.proposedActions = response.data.data;
                })
                .catch((error) => {
                    console.error("Error loading proposed actions:", error);
                })
                .finally(() => {
                    this.isLoadingProposedActions = false;
                });
        },

        loadUrgentCalendarActions() {
            this.isLoadingUrgentCalendarActions = true;

            axios
                .get("/calendar-actions", {})
                .then((response) => {
                    this.todoEvents = response.data.data;

                    this.buildCalendarEventToCalendarActionMap();
                })
                .catch((error) => {
                    console.error("Error loading proposed actions:", error);
                })
                .finally(() => {
                    this.isLoadingUrgentCalendarActions = false;
                });
        },

        handleCalendarEventClick(eventUuid) {
            const calendarAction = this.calendarEventToCalendarActionMap[eventUuid];

            if (calendarAction) {
                this.selectedCalendarAction = calendarAction.parentCalendarAction;
            } else {
                console.error("Event not found in the map!");
            }
        },

        handleCalendarActionClick(calendarActionUuid) {
            const calendarAction = this.todoEvents.find((todo) => todo.uuid === calendarActionUuid);

            if (calendarAction) {
                this.selectedCalendarAction = calendarAction;
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
                todo.calendar_events.forEach((event) => {
                    this.calendarEventToCalendarActionMap[event.uuid] = {
                        parentCalendarAction: todo,
                    };
                });
            });

            this.parseFullCalendarEvents();
        },

        closePopover() {
            this.selectedCalendarAction = null;
            this.shouldDisplayScheduleConsultationsPopover = false;
        },

        showScheduleConsultationsPopover() {
            this.template = 'consultation';

            this.shouldDisplayScheduleConsultationsPopover = true;
        },

        getLoggedUser() {
            axios.get("/user", {

            }).then((response) => {
                this.$root.loggedUser = response.data.data;
            }) .catch((error) => {
                console.error("Error loading logged user:", error);
            });
        },

        createEmailFromTemplate(template) {
            this.template = template;
            console.log(template)

            this.showScheduleConsultationsPopover();
        }
    },

    mounted() {
        if (localStorage.getItem("bearer_token")) {
            window.axios.defaults.headers.common["Authorization"] = `Bearer ${localStorage.getItem(
                "bearer_token",
            )}`;
        } else {
            this.switchToCEO();
        }

        this.getLoggedUser();

        this.loadProposedActions();
        this.loadUrgentCalendarActions();

        this.buildCalendarEventToCalendarActionMap();
    },
};
</script>

<style>
/* Custom transition for all popovers */
.fade-popover-enter-active,
.fade-popover-leave-active {
    transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
}
.fade-popover-enter, .fade-popover-leave-to /* .fade-popover-leave-active for <2.1.8 */ {
    opacity: 0;
    transform: translateY(-10px); /* Optional: adds a slight slide-up effect */
}
.fade-popover-leave-active {
    opacity: 0;
    transform: translateY(0);
}
</style>
