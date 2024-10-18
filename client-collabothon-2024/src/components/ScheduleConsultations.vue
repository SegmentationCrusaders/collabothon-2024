<template>
    <div class="bg-yellow-500 text-white shadow-md p-6 rounded-lg hover:bg-yellow-600 transition">
        <button
            @click="handleScheduleConsultations"
            class="bg-white text-yellow-500 px-4 py-2 font-semibold rounded-lg shadow-md hover:bg-yellow-100 transition"
        >
            Schedule Consultations
        </button>
    </div>
</template>

<script>
export default {
    methods: {
        handleScheduleConsultations() {
            console.log("Schedule Consultations button clicked!");
        },
    },
};
</script>

<style scoped></style>
