<template>
    <div class="py-2">
        <span class="font-semibold">{{ event.title }}</span>
        <span class="text-sm text-gray-600"> - {{ event.date }}</span>
        <div v-if="event.accepted" class="text-green-500">Accepted</div>
        <div v-else-if="event.selectionRequired" class="text-yellow-500">Selection Required</div>
    </div>
</template>

<script>
export default {
    props: {
        event: {
            type: Object,
            required: true,
        },
    },
};
</script>

<style scoped></style>
