<template>
    <div class="p-6 text-white transition comarzbank-bg rounded-lg shadow-md hover:bg-yellow-600">
        <button
            @click="handleScheduleConsultations"
            class="px-4 py-2 font-semibold commerzbank-color transition bg-white rounded-lg shadow-md hover:bg-yellow-100"
        >
            Schedule Consultations
        </button>
    </div>
</template>

<script>
export default {
    methods: {
        handleScheduleConsultations() {
            console.log("Show consultation scheduling popover form.");
            this.$emit("show-schedule-consultations-popover");
        },
    },
};
</script>

<style scoped></style>
