<template>
    <div @click="handleEventClick" class="p-4 cursor-pointer hover:bg-gray-200">
        <div class="flex items-center justify-between">
            <h4 class="text-lg font-semibold">{{ action.title }}</h4>

            <div v-if="action.status">
                <i
                    v-if="action.status.name === 'CREATED'"
                    class="text-yellow-500 fas fa-file-alt"
                ></i>

                <i
                    v-if="action.status.name === 'AWAITING'"
                    class="text-blue-500 fas fa-hourglass-half"
                ></i>

                <i
                    v-if="action.status.name === 'COMPLETED'"
                    class="text-green-500 fas fa-check-circle"
                ></i>

                <i
                    v-if="action.status.name === 'CANCELLED'"
                    class="text-red-500 fas fa-times-circle"
                ></i>

                <span class="ml-2 text-sm text-gray-500">{{ action.status?.name }}</span>
            </div>
        </div>
        <p class="mt-1 text-gray-600">{{ action.description }}</p>

        <!-- Tags with icons -->
        <div class="flex flex-wrap mt-2">
            <span
                v-for="tag in action.tags"
                :key="tag.uuid"
                class="flex items-center px-2 py-1 mr-1 text-green-800 bg-green-100 rounded-full"
            >
                <i class="mr-1 fas fa-tag"></i>
                {{ tag.tag }}
            </span>
        </div>

        <div class="flex items-center mt-2 text-sm text-gray-500">
            <span>View Details</span>
            <i class="ml-2 fas fa-chevron-right"></i>
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
