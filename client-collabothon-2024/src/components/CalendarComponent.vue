<template>
    <div>
        <FullCalendar :events="events" @event-click="handleEventClick" :options="calendarOptions" />
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
                initialEvents: this.events,
                select: this.handleDateSelect,
                eventClick: this.handleEventClick,
                eventsSet: this.handleEvents,
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

        handleEventClick(info) {
            const clickedEvent = info.event;
            
            if (clickedEvent.id) {
                this.$emit("calendar-event-click", clickedEvent.id);
            }
        },

        handleEvents(events) {
            this.currentEvents = events;
        },
    },
};
</script>

<style scoped></style>
