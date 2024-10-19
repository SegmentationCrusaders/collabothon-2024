<template>
    <div class="relative w-full pt-4 overflow-hidden">
        <div
            class="flex transition-transform duration-1000"
            :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
        >
            <div v-for="(idea, index) in ideas" :key="index" class="flex-shrink-0 w-full">
                <div
                    class="relative p-6 px-8 border rounded-lg shadow-lg h-full"
                    :style="{ backgroundColor: '#fbb809'}"
                >
                    <p class="mb-4 text-white-700 p-2">{{ idea.content }}</p>
                    <div class="absolute bottom-4 right-4 text-white comarzbank-bg">
                        <button
                            @click="createAppointment(idea.calendar_action_template)"
                            class="p-1 font-semibold transition bg-white rounded-lg shadow-md commerzbank-color hover:bg-yellow-100"
                        >
                            Create Appointment
                        </button>
                    </div>
                    <button
                        @click="prev"
                        class="absolute left-2 p-2 text-white transform -translate-y-1/2 bg-gray-800 rounded-full top-1/2 hover:bg-gray-700"
                    >
                        ‹
                    </button>
                    <button
                        @click="next"
                        class="absolute right-2 p-2 text-white transform -translate-y-1/2 bg-gray-800 rounded-full top-1/2 hover:bg-gray-700"
                    >
                        ›
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            currentIndex: 0,
            ideas: [],
            intervalId: null,
        };
    },
    emits: ["onCreate"],
    methods: {
        next() {
            if (this.currentIndex < this.ideas.length - 1) {
                this.currentIndex++;
            } else {
                this.currentIndex = 0;
            }

            this.stopAutoSlide();
            this.startAutoSlide();
        },
        prev() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
            } else {
                this.currentIndex = this.ideas.length - 1;
            }

            this.stopAutoSlide();
            this.startAutoSlide();
        },
        createAppointment(template) {
            this.$emit("onCreate", template);
        },
        startAutoSlide() {
            this.intervalId = setInterval(this.next, 7000);
        },
        stopAutoSlide() {
            clearInterval(this.intervalId);
        },
        loadProposedActions() {
            axios
                .get("/ideas", {})
                .then((response) => {
                    this.ideas = response.data.data;

                    this.currentIndex = Math.floor(Math.random() * this.ideas.length);
                    this.startAutoSlide();
                })
                .catch((error) => {
                    console.error("Error loading ideas:", error);
                });
        },
    },
    mounted() {
        this.loadProposedActions();
    },
    beforeDestroy() {
        this.stopAutoSlide();
    },
};
</script>

<style scoped>
.carousel {
    display: flex;
    transition: transform 0.5s ease-in-out;
}
</style>
