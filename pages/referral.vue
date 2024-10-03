<template>
    <div class="p-4 bg-black">
        <h1 class="text-3xl text-green-500 mb-6 font-bold">Referral Program</h1>
        <p class="mb-4 text-lg">
            Invite friends to earn rewards! For every successful referral, you will receive
            <span class="text-green-500 font-semibold">+1 hour</span> added to your timer.
        </p>

        <div class="bg-gradient-to-r from-gray-950 to-gray-900 p-4 rounded-lg border border-green-500 mb-6">
            <div class="flex justify-between items-center mb-2">
                <p class="text-lg text-green-500">Your referral link:</p>
                <div class="flex space-x-2">
                    <button @click="copyToClipboard"
                        class="bg-green-500 text-black py-1 px-3 rounded-lg font-bold hover:bg-green-400 transition">
                        Copy
                    </button>
                    <button @click="shareToTelegram"
                        class="border-green-500 border text-green-500 py-1 px-3 rounded-lg font-bold hover:border-blue-400 transition">
                        Share
                    </button>
                </div>
            </div>
            <p class="text-green-500 text-sm text-center">{{ linkTMA }}?start={{ telegram_id }}</p>
            <div class="w-4/4 mx-auto border-t border-green-500 mb-4"></div>
            <div class="flex justify-between items-center">
                <p class="text-lg text-green-500">Total Referrals:</p>
                <p class="text-green-300 text-lg font-semibold">{{ totalReferrals }}</p>
            </div>
        </div>

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl text-green-500 font-semibold">Your Referrals</h2>
            <div class="flex space-x-4">
                <button
                    class="bg-gradient-to-r from-gray-950 to-gray-900 text-green-500 py-2 px-4 rounded-lg border border-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="currentPage === 1" @click="prevPage">
                    Prev
                </button>
                <button
                    class="bg-gradient-to-r from-gray-950 to-gray-900 text-green-500 py-2 px-4 rounded-lg border border-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="currentPage === totalPages" @click="nextPage">
                    Next
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div v-for="referral in paginatedReferrals" :key="referral.referral_id"
                class="bg-gradient-to-r from-gray-950 to-gray-900 p-4 rounded-lg shadow-lg border border-green-500 transition transform hover:scale-105 hover:border-green-300 hover:shadow-xl">
                <div class="flex items-center space-x-4">
                    <div
                        class="w-12 h-12 bg-green-500 text-black flex justify-center items-center rounded-full font-bold text-lg">
                        {{ referral.firstname.charAt(0).toUpperCase() }}
                    </div>
                    <span class="absolute right-4 text-semibold text-sm">+ 1 Hour</span>
                    <div>
                        <p class="text-xl font-semibold text-green-300">
                            {{ referral.firstname.charAt(0).toUpperCase() + referral.firstname.slice(1) }}
                            {{ referral.lastname.charAt(0).toUpperCase() + referral.lastname.slice(1) }}</p>
                        <p class="text-sm text-gray-400">Referred on: <span class="text-green-300">{{
                            referral.created_at }}</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="alert" class="fixed top-0 left-0 w-full h-full flex items-center justify-center z-[100]">
            <div :class="{ 'text-green-500': success, 'text-red-500': failed }"
                class="bg-gray-900/90 fixed top-2 w-[95%] px-2  flex flex-row rounded-md z-[100]">
                <span class="p-2" v-if="failed">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M19.875 6.27c.7.398 1.13 1.143 1.125 1.948v7.284c0 .809-.443 1.555-1.158 1.948l-6.75 4.27a2.27 2.27 0 0 1-2.184 0l-6.75-4.27A2.23 2.23 0 0 1 3 15.502V8.217c0-.809.443-1.554 1.158-1.947l6.75-3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98zM12 8v4m0 4h.01" />
                    </svg>
                </span>
                <span class="p-2" v-if="success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M18 6L7 17l-5-5m20-2l-7.5 7.5L13 16" />
                    </svg>
                </span>
                <span class="mt-2 text-xs">{{ alertMessage }}</span>
            </div>
            <div class="bg-white fixed w-[95%] top-7 h-1 mt-2 rounded z-[100]">
                <div :class="{ 'bg-green-500': success, 'bg-red-500': failed }" class="h-full rounded"
                    :style="{ width: progressBarWidth + '%' }"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useUserStore } from '~/stores/userStore'

const userStore = useUserStore()
const telegram_id = computed(() => userStore.telegram_id);
const baseUrl = useBaseApiUrl();
const referrals = ref([])
const currentPage = ref(1)
const itemsPerPage = 3
const link = ref(null)
watch(telegram_id, (newID) => {
    if (newID) {
        fetchReferrals()
    }
});

const fetchReferrals = async () => {
    try {
        const response = await $fetch(`${baseUrl}get_referrals/index.php`, {
            method: 'POST',
            body: {
                telegram_id: telegram_id.value
            }
        })
        if (response.success) {
            referrals.value = response.referrals
        }
    } catch (error) {
        console.error('Error fetching referrals:', error)
    }
}

const linkTMA = ref(null)
const getLink = async () => {
    try {
        const response = await $fetch(`${baseUrl}config/index.php`);

        if (response) {
            linkTMA.value = response.linkTMA;
            link.value = `${response.linkTMA}?start=${telegram_id.value}`
        }
    } catch (error) {
        console.log('Error fetching admin wallet:', error);
    }
};

onMounted(() => {
    fetchReferrals()
    getLink()
})

const totalReferrals = computed(() => referrals.value.length)

const totalPages = computed(() => Math.ceil(referrals.value.length / itemsPerPage))

const paginatedReferrals = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    return referrals.value.slice(start, start + itemsPerPage)
})

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++
    }
}

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--
    }
}

const shareToTelegram = () => {
    if (link.value) {
        const telegramUrl = `https://t.me/share/url?text=${encodeURIComponent('Start Profit')}&url=${encodeURIComponent(link.value)}`;
        window.open(telegramUrl, '_blank');
    }
};
const alert = ref(false)
const alertMessage = ref(false)
const success = ref(false)
const failed = ref(false)
const copyToClipboard = async () => {
    try {
        await navigator.clipboard.writeText(link.value);
        alert.value = true;
        success.value = true;
        alertMessage.value = "Referral copied to clipboard.";
        progressBarWidth.value = 100;
        startTimeout();
    } catch (err) {
        console.error('Failed to copy: ', err);
    }
};

const progressBarWidth = ref(100);
const startTimeout = () => {
    const intervalDuration = 50;
    const decrementAmount = (100 / 2000) * intervalDuration;
    const interval = setInterval(() => {
        progressBarWidth.value -= decrementAmount;
        if (progressBarWidth.value <= 0) {
            clearInterval(interval);
            alert.value = false;
        }
    }, intervalDuration);
};
</script>

<style scoped>
body {
    background-color: black;
}
</style>