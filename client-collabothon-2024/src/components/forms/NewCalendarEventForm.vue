<script>

const toIsoFormat = (dateLikeString) => {
  const dateObj = new Date(dateLikeString);

  return dateObj.getFullYear() + '-' +
      String(dateObj.getMonth() + 1).padStart(2, '0') + '-' +
      String(dateObj.getDate()).padStart(2, '0') + ' ' +
      String(dateObj.getHours()).padStart(2, '0') + ':' +
      String(dateObj.getMinutes()).padStart(2, '0') + ':' +
      String(dateObj.getSeconds()).padStart(2, '0');
}

export default {
  name: "NewCalendarEventForm",
  props: {
    action: {
      type: Object,
      required: true,
    },
  },
  methods: {
    createNewDate: (actionUuid) => {
      //console.log("Creating a new date". this.$refs.from_date, this.$refs.to_date);
      console.log("Creating a new date", document.getElementById('calendar_event_start_date').value);
      axios.post(`/calendar-event-create/${actionUuid}`, {
        start_date: toIsoFormat(document.getElementById('calendar_event_start_date').value.replace('T', ' ')) || '',
        end_date: toIsoFormat(document.getElementById('calendar_event_end_date').value.replace('T', ' ')) || '',
        location: document.getElementById('calendar_event_location').value || "ONLINE"
      })
          .then((response) => {
            console.debug('[Calendar event] New date created', response);
            alert('New event created');
            location.reload();
          })
          .catch((error) => {
            console.error("Error accepting the event:", error);
          });
    }
  },
  data() {
    return {
      location: null,
    };
  }
}
</script>

<template>
  <div class="md:flex md:items-center mb-2">
    <div class="md:w-1/3">
      <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
        Location:
      </label>
    </div>
    <div class="md:w-2/3">
      <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" ref="location" id="calendar_event_location" type="text" placeholder="Enter meeting location">
    </div>
  </div>
  <div class="flex flex-wrap -mx-3 mb-2">
    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
      <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2">
        From:
      </label>
      <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" ref="from_date" id="calendar_event_start_date" type="datetime-local" placeholder="From date">
    </div>
    <div class="w-full md:w-1/2 px-3">
      <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2">
        To:
      </label>
      <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" ref="to_date" id="calendar_event_end_date" type="datetime-local" placeholder="To date">
    </div>
  </div>
  <button
      @click="createNewDate(action.uuid)"
      class="w-full p-2 mt-4 text-white rounded"
      :style="{ backgroundColor: '#002d64' }"
  >
    Create New Date
  </button>
</template>

<style scoped>

</style>