<template>
    <div>
        <div @click="togglePopover" class="cursor-pointer p-4 hover:bg-gray-200">
            <h4 class="text-lg font-semibold">{{ action.title }}</h4>
            <div class="flex flex-wrap">
                <span
                    v-for="tag in action.tags"
                    :key="tag"
                    class="bg-green-100 text-green-800 py-1 px-2 rounded-full mr-1"
                >
                    {{ tag }}
                </span>
            </div>
        </div>

        <CalendarEventsPopover
            v-if="isPopoverVisible"
            :events="action.events"
            @eventAccepted="handleEventAccepted"
            @close="togglePopover"
        />
    </div>
</template>

<script>
import CalendarEventsPopover from "./popovers/CalendarEventsPopover.vue";

export default {
    props: {
        action: {
            type: Object,
            required: true,
        },
    },
    components: {
        CalendarEventsPopover,
    },
    data() {
        return {
            isPopoverVisible: false,
        };
    },
    methods: {
        togglePopover() {
            this.isPopoverVisible = !this.isPopoverVisible;
        },
        handleEventAccepted(event) {
            console.log(`Accepted event: ${event.title} on ${event.date}`);
            this.togglePopover(); // Close the popover after accepting
        },
    },
};
</script>

<style scoped>
/* Add any additional styles here if needed */
</style>
