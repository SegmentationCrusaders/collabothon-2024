<template>
    <div>
        <div @click="handleEventClick" class="p-4 cursor-pointer hover:bg-gray-200">
            <h4 class="text-lg font-semibold">{{ action.title }}</h4>
            <div class="flex flex-wrap">
                <span
                    v-for="tag in action.tags"
                    :key="tag.uuid"
                    class="px-2 py-1 mr-1 text-green-800 bg-green-100 rounded-full"
                >
                    {{ tag.tag }}
                </span>
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
            this.$emit("calendar-action-click", this.action.id);
        },
    },
};
</script>

<style scoped>
/* Add any additional styles here if needed */
</style>
