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

                <CalendarActionStatus :action="action" />
                
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
                        v-for="event in action.calendar_events.filter(evt => !isApproversListIsEmpty(evt))"
                        :key="event.id"
                        class="flex items-center justify-between p-2 rounded"
                    >
                        <CalendarEvent @eventClicked="eventClicked" :event="event" />

                        <div class="flex space-x-2 text-4xl" v-if="isApprovedByCurrentUser(event)">
                            Approved <i class="fa-solid fa-circle-check"></i>
                        </div>
                      <div class="flex space-x-2 text-4xl" v-else-if="isDeclinedByAll(event)"></div>
                      <div class="flex space-x-2 text-4xl" v-else-if="isDeclinedByCurrentUser(event)">
                        Declined <i class="fa-solid fa-circle-check"></i>
                      </div>
                        <div class="flex space-x-2" v-else>
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
              <fieldset>
                <legend>Location:</legend>
                <input type="text"/>
              </fieldset>
              <fieldset>
                <legend>From:</legend>
                <input ref="from_date" id="calendar_event_start_date" type="datetime-local"><br/>
<!--                <input type="date"><br/>-->
              </fieldset>
              <fieldset>
                <legend>To:</legend>
                <input ref="to_date" id="calendar_event_end_date" type="datetime-local"><br/>
<!--                <input type="date"><br/>-->
              </fieldset>
                <button
                    @click="createNewDate(action.uuid)"
                    class="w-full p-2 mt-4 text-white bg-blue-500 rounded"
                >
                    Create New Date
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import CalendarActionStatus from "../CalendarActionStatus.vue";
import CalendarEvent from "../CalendarEventComponent.vue";
import {all} from "axios";

const isApprovedByCurrentUser = (event) => {
  const allApprovals = [...event.bank_employees, ...event.client_employees];

  console.log('approvals', allApprovals);

  return allApprovals.some((person) => person.accepted === 1).length > 1;
};

const isDeclinedByCurrentUser = (event) => {
  const allApprovals = [...event.bank_employees, ...event.client_employees];

  return allApprovals.some((person) => person.accepted === 1).length > 1;
};

const isDeclinedByAll = (event) => {
  const allApprovals = [...event.bank_employees, ...event.client_employees];

  return allApprovals.some((person) => person.accepted === 1).length === allApprovals.length;
};

const isApproversListIsEmpty = (event) => {
  const allApprovals = [...event.bank_employees, ...event.client_employees];

  return allApprovals.length === 0;
}

export default {
    props: {
        action: {
            type: Object,
            required: true,
        },
    },
    components: {
        CalendarEvent,
        CalendarActionStatus,
    },
    data() {
        return {
            selectedEvent: null,
        };
    },
    methods: {
        acceptEvent(event) {
            axios.post(`/calendar-event-accept/${event.uuid}`)
              .then((response) => {
                 console.debug('[Calendar event] Accepted event with id', response);
                 //location.reload();
                // TODO: Reload component
              })
              .catch((error) => {
                console.error("Error accepting the event:", error);
              });
            console.log(`Accepted meeting date: ${event.date}`, event);
            this.$emit("eventAccepted", event);
        },
        rejectEvent(event) {
          axios.post(`/calendar-event-decline/${event.uuid}`)
              .then((response) => {
                  console.debug('[Calendar event] Declined event with id', response);
                  location.reload();
                  // TODO: Reload component
              })
              .catch((error) => {
                console.error("Error accepting the event:", error);
              });
            console.log(`Rejected meeting date: ${event.date}`);
            this.$emit("eventRejected", event);
        },
        createNewDate: (actionUuid) => {

            //console.log("Creating a new date". this.$refs.from_date, this.$refs.to_date);
            console.log("Creating a new date", document.getElementById('calendar_event_start_date').value);
            axios.post(`/calendar-event-decline/${actionUuid}`, {
                start_date: document.getElementById('calendar_event_start_date').value || '',
                end_date: document.getElementById('calendar_event_end_date').value || ''
            })
                .then((response) => {
                    console.debug('[Calendar event] New date created', response);
                })
                .catch((error) => {
                  console.error("Error accepting the event:", error);
                });
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
        isApprovedByCurrentUser,
        isDeclinedByCurrentUser,
        isDeclinedByAll,
        isApproversListIsEmpty
    },
};
</script>

<style scoped>
/* Add your custom styling here */
</style>
