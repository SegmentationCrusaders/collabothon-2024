<template>
    <div>
        <FullCalendar
            ref="fullCalendar"
            :events="currentEvents"
            @event-click="handleEventClick"
            :options="calendarOptions"
        />
    </div>
</template>

<script>
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";

export default {
    components: {
        FullCalendar,
    },
    props: {
        events: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            selectedEvent: null,
            currentEvents: [],

            calendarOptions: {
                plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
                initialView: "dayGridMonth",
                headerToolbar: {
                    left: "prev,next today",
                    center: "title",
                    right: "dayGridMonth,timeGridWeek,timeGridDay",
                },
                editable: true,
                selectable: true,
                select: this.handleDateSelect,
                eventClick: this.handleEventClick,
                timeZone: "local",
            },
        };
    },

    methods: {
        handleDateSelect(selectInfo) {
            let title = prompt("Please enter a new title for your event");
            let calendarApi = selectInfo.view.calendar;

            calendarApi.unselect(); // clear date selection

            if (title) {
                calendarApi.addEvent({
                    id: createEventId(),
                    title,
                    start: selectInfo.startStr,
                    end: selectInfo.endStr,
                    allDay: selectInfo.allDay,
                });
            }
        },

        convertToLocalDate(dateString) {
            if (!dateString) {
                console.error("Invalid date string:", dateString);
                return null; // Return null for invalid date strings
            }

            const date = new Date(dateString);

            if (isNaN(date)) {
                console.error("Invalid date value:", dateString);
                return null; // Return null for invalid date values
            }

            return date.toISOString(); // Format: YYYY-MM-DDTHH:mm:ss.sssZ
        },

        fullCalendarEvents(events) {
            return events.map((event) => {
                const start = this.convertToLocalDate(event.start); // Use the correct field names
                const end = this.convertToLocalDate(event.end);

                // Ensure valid dates before returning the event
                return {
                    id: event.id || event.uuid, // Ensure we use a valid ID
                    title: event.title,
                    start: start || undefined, // Set to undefined if invalid
                    end: end || undefined, // Set to undefined if invalid
                    extendedProps: {
                        tags: event.tags || [],
                        originalEvent: event, // Pass the original event for more details
                        clientEmployees: event.client_employees || [],
                        bankEmployees: event.bank_employees || [],
                    },
                };
            });
        },

        handleEventClick(info) {
            const clickedEvent = info.event;

            if (clickedEvent.id) {
                this.$emit("calendar-event-click", clickedEvent.id);
            }
        },
    },

    watch: {
        events: {
            immediate: true,
            handler(newEvents) {
                this.currentEvents = this.fullCalendarEvents(newEvents);
                this.$nextTick(() => {
                    const calendarApi = this.$refs.fullCalendar.getApi();
                    calendarApi.removeAllEvents(); // Clear existing events
                    calendarApi.addEventSource(this.currentEvents); // Add new events
                });
            },
        },
    },
};
</script>

<style scoped></style>
