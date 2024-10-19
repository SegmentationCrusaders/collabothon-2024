<template>
    <div class="relative w-full overflow-hidden pt-4">
        <div class="flex transition-transform duration-500" :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
            <div v-for="(item, index) in items" :key="index" class="flex-shrink-0 w-full px-4">
                <div class="relative p-6 rounded-lg shadow-lg border" :style="{ backgroundColor: '#fbb809'}">
                    <p class="mb-4 text-white-700">{{ item.text }}</p>
                    <div class="text-white comarzbank-bg">
                        <button
                            @click="createAppointment(item.template)"
                            class="px-4 py-2 font-semibold commerzbank-color transition bg-white rounded-lg shadow-md hover:bg-yellow-100"
                        >
                        Create Appointment
                        </button>
                    </div>
                    <button @click="prev" class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-gray-800 text-white p-2 rounded-full hover:bg-gray-700">‹</button>
                    <button @click="next" class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-gray-800 text-white p-2 rounded-full hover:bg-gray-700">›</button>
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
            items: [
                {
                    template: 'coworking concepts',
                    text: "70% of the midcap companies see energy cost as the number 1 problem in the future with sustainability close behind. One of the biggest cost factors is office space. Have you thought of coworking concepts for your employees? Need more information?",
                },
                {
                    template: 'vehicle fleet',
                    text: "One of the biggest cost drivers in your cash flow is the vehicle fleet. Have you thought about carsharing in order to save costs and gain in sustainability? Need more information?",
                },
                {
                    template: 'foreign currencies',
                    text: "You have regular payments in foreign currencies - have you thought about securing an advantageous exchange rate to gain in planning security and minimize cost risk? Need more information?",
                }
            ]
        };
    },
    emits: ["onCreate"],
    methods: {
        next() {
            if (this.currentIndex < this.items.length - 1) {
                this.currentIndex++;
            } else {
                this.currentIndex = 0;
            }
        },
        prev() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
            } else {
                this.currentIndex = this.items.length - 1;
            }
        },
        createAppointment(link) {
            this.$emit("onCreate", link);
        },
    },
    mounted() {
        this.currentIndex = Math.floor(Math.random() * this.items.length);
    }
};
</script>

<style scoped>
.carousel {
    display: flex;
    transition: transform 0.5s ease-in-out;
}
</style>