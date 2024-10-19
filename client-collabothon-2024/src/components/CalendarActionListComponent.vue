<template>
    <div>
        <div @click="handleEventClick" class="p-4 cursor-pointer hover:bg-gray-200">
            <h4 class="text-lg font-semibold">{{ action.title }}</h4>
            <p class="text-gray-600">{{ action.description }}</p>
            <div class="flex flex-wrap">
                <span
                    v-for="tag in action.tags"
                    :key="tag.uuid"
                    class="px-2 py-1 mr-1 text-green-800 bg-green-100 rounded-full"
                >
                    {{ tag.tag }}
                </span>
            </div>
            <p class="mt-2 text-sm text-gray-500">Status: {{ action.status?.name }}</p>
            <div class="mt-4">
                <h5 class="font-medium">Calendar Events:</h5>
                <ul>
                    <li
                        v-for="event in action.calendar_events"
                        :key="event.uuid"
                        class="p-2 mb-2 border rounded-md bg-gray-50"
                    >
                        <h6 class="font-semibold">{{ event.title }}</h6>
                        <p>Location: {{ event.location }}</p>
                        <p>Start: {{ event.start_date }}</p>
                        <p>End: {{ event.end_date }}</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        action: {
            type: Object,
            required: true,
        },
    },
    methods: {
        handleEventClick() {
            // Emit the action's ID when the action is clicked
            this.$emit("calendar-action-click", this.action.uuid);
        },
    },
};
</script>

<style scoped>
/* Add any additional styles here if needed */
</style>
