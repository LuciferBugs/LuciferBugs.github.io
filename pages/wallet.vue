<template>
    <div class="p-4 h-screen flex flex-col justify-between bg-black text-white">
        <div
            class="bg-gradient-to-r from-gray-950 to-gray-900 p-6 rounded-3xl shadow-xl border border-green-500 w-full max-w-xl mx-auto mb-4">
            <h1 class="text-xl text-center font-bold text-green-500 mb-6">Your Wallet</h1>
            <div class="flex justify-between items-center mb-4 text-green-500">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mr-2" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M18 3H2v18h18v-4h2V7h-2V3zm0 14v2H4V5h14v2h-8v10zm2-2h-8V9h8zm-4-4h-2v2z" />
                    </svg>
                    <p v-if="!address" class="text-sm text-green-300 font-bold">No wallet connected</p>
                    <p v-if="address" class="text-sm text-green-300 font-bold">{{ formattedAddress }}</p>
                </div>
                <button v-if="address" @click="copyToClipboard"
                    class="border border-green-500 text-green-500 py-1 px-2 rounded-lg font-bold hover:bg-green-500 hover:text-black transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 256 256">
                        <path fill="currentColor"
                            d="M184 64H40a8 8 0 0 0-8 8v144a8 8 0 0 0 8 8h144a8 8 0 0 0 8-8V72a8 8 0 0 0-8-8m-8 144H48V80h128Zm48-168v144a8 8 0 0 1-16 0V48H72a8 8 0 0 1 0-16h144a8 8 0 0 1 8 8" />
                    </svg>
                </button>
            </div>
            <button v-if="!address" @click="open"
                class="w-full bg-green-500 text-black py-3 px-6 rounded-lg font-bold transition mb-4">
                Connect Wallet
            </button>
            <button v-if="address" @click="disconnect"
                class="w-full bg-green-500 text-black py-3 px-6 rounded-lg font-bold transition mb-4">
                Disconnect Wallet
            </button>
            <TonConnectButton hidden />
            <button class="w-full border border-green-500 text-green-500 py-3 px-6 rounded-lg font-bold transition"
                @click="openModal">
                Buy Package
            </button>
        </div>

        <div
            class="bg-gradient-to-r from-gray-950 to-gray-900 p-6 rounded-3xl shadow-xl border border-green-500 w-full max-w-xl mx-auto flex-grow overflow-hidden">
            <h2 class="text-xl text-center font-bold text-green-500 mb-4">History</h2>

            <div class="flex justify-between items-center mb-4">
                <button
                    class="bg-gray-900 text-green-500 px-4 py-2 rounded-lg hover:bg-gray-800 border border-green-500 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="currentPage === 1" @click="prevPage">
                    Prev
                </button>
                <p class="text-md text-green-300">Page {{ currentPage }} of {{ totalPages }}</p>
                <button
                    class="bg-gray-900 text-green-500 px-4 py-2 rounded-lg hover:bg-gray-800 border border-green-500 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="currentPage === totalPages" @click="nextPage">
                    Next
                </button>
            </div>

            <div class="overflow-auto h-60">
                <div v-for="transaction in paginatedTransactions" :key="transaction.created_at"
                    class="bg-gray-900 p-4 rounded-lg mb-4">
                    <div class="flex justify-between items-center">
                        <p class="text-lg text-green-300">{{ transaction.type }}</p>
                        <p class="text-green-400 font-bold">
                            + {{ parseFloat(transaction.amount) }}
                            <span v-if="transaction.type === 'Task'">Hour</span>
                            <span v-if="transaction.type === 'Invite'">Hour</span>
                            <span v-else-if="transaction.type === 'Withdraw'">USDT</span>
                            <span v-else-if="transaction.type === 'Upgrade'">Speed</span>
                        </p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-400">{{ transaction.created_at }}</p>
                        <p class="text-sm text-gray-400">{{ transaction.status }}</p>
                    </div>
                </div>
            </div>
        </div>

        <transition name="fade">
            <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50">
                <div class="bg-gray-900 border border-green-500 p-12 rounded-2xl shadow-xl max-w-lg w-auto">
                    <div class="text-center items-center justify-center flex">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16" viewBox="0 0 24 24">
                            <g fill="none">
                                <path stroke="currentColor"
                                    d="M11.5 17.3v1.897c0 .341 0 .643.027.865c.013.113.039.253.101.382c.07.144.203.301.425.362a.67.67 0 0 0 .55-.097a1.2 1.2 0 0 0 .28-.278a8 8 0 0 0 .462-.732l.017-.028l1.296-2.203l.021-.037c.376-.638.686-1.166.861-1.598c.18-.444.273-.924.014-1.378c-.26-.454-.722-.616-1.196-.687c-.461-.068-1.074-.068-1.816-.068H12.5v-1.897c0-.341 0-.643-.027-.865a1.2 1.2 0 0 0-.101-.382a.67.67 0 0 0-.425-.362a.67.67 0 0 0-.55.097a1.2 1.2 0 0 0-.28.278a8 8 0 0 0-.462.732l-.017.028l-1.296 2.203l-.021.037c-.376.638-.686 1.166-.861 1.598c-.18.444-.273.924-.014 1.378c.26.454.722.616 1.196.687c.461.068 1.074.068 1.816.068z" />
                                <path fill="currentColor" fill-rule="evenodd"
                                    d="M12 2.5a5.5 5.5 0 0 0-5.02 3.25c-.209.464-.595.75-.98.75a3.5 3.5 0 0 0-.934 6.874c.072-.337.2-.653.373-.937A2.501 2.501 0 0 1 6 7.5c.895 0 1.577-.637 1.892-1.34a4.501 4.501 0 0 1 8.216 0c.316.703.997 1.34 1.892 1.34a2.5 2.5 0 0 1 .56 4.937c.175.284.302.6.374.937A3.502 3.502 0 0 0 18 6.5c-.385 0-.771-.286-.98-.75A5.5 5.5 0 0 0 12 2.5"
                                    clip-rule="evenodd" />
                            </g>
                        </svg>

                    </div>
                    <div class="text-center">
                        <h2 class="text-3xl font-semibold text-green-500 mb-2">Buy Package</h2>
                        <span class="text-xs mb-6">Unlock the power of speed! Each package you buy will boost your
                            mining time, giving you a faster way to earn rewards. Don’t miss the chance to supercharge
                            your progress!</span>
                        <p class="text-sm text-gray-400 mb-4">Select the number of packages you want to buy.</p>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <button
                            class="border border-green-500 text-green-500 text-xl w-8 h-8 flex justify-center rounded-lg hover:bg-green-400 hover:text-black transition"
                            @click="decrementPackage" :disabled="packageCount === 1">
                            -
                        </button>
                        <span class="text-white text-xl">{{ packageCount }}</span>
                        <button
                            class="border border-green-500 text-green-500 text-xl w-8 h-8 flex justify-center rounded-lg hover:bg-green-400 hover:text-black transition"
                            @click="incrementPackage">
                            +
                        </button>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button
                            class="border border-green-500 text-green-500 px-4 py-2 rounded-lg hover:bg-red-400 transition"
                            @click="closeModal">
                            Cancel
                        </button>
                        <button v-if="address"
                            class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-400 transition"
                            @click="sendTransaction($event)">
                            Buy Package
                        </button>
                        <button v-if="!address"
                            class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-400 transition"
                            @click="open">
                            Connect Wallet
                        </button>
                    </div>
                </div>
            </div>
        </transition>

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

    <div class="h-20"></div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useTonConnectUI, useTonAddress, useTonWallet, useTonConnectModal, useIsConnectionRestored } from "ton-ui-vue";
import { beginCell, toNano, Address } from '@ton/ton';
import TonWeb from 'tonweb';
import { useUserStore } from '~/stores/userStore'

const userStore = useUserStore()
const telegram_id = computed(() => userStore.telegram_id);

const baseUrl = useBaseApiUrl();
const tonweb = new TonWeb();
const jettonWalletAddress = ref('');
const jettonWalletData = ref(null);
const jettonMinterAddress = 'EQCxE6mUtQJKFnGfaROTKOt1lZbDiiX1kCixRv7Nw2Id_sDs';
const adminWallet = ref(null);
const linkTMA = ref(null);

const getAdminWallet = async () => {
    try {
        const response = await $fetch(`${baseUrl}config/index.php`);

        if (response) {
            adminWallet.value = response.adminWallet;
            linkTMA.value = response.linkTMA;
        }
    } catch (error) {
        console.log('Error fetching admin wallet:', error);
    }
};

async function fetchJettonWalletAddress(walletAddress) {
    try {
        const jettonMinter = new TonWeb.token.jetton.JettonMinter(tonweb.provider, {
            address: jettonMinterAddress
        });
        const jettonWalletAddressResult = await jettonMinter.getJettonWalletAddress(new TonWeb.utils.Address(walletAddress));
        const jettonWallet = new TonWeb.token.jetton.JettonWallet(tonweb.provider, {
            address: jettonWalletAddressResult
        });
        const jettonData = await jettonWallet.getData();
        if (jettonData.jettonMinterAddress.toString(false) !== jettonMinter.address.toString(false)) {
            throw new Error('Jetton Minter address from Jetton Wallet doesn\'t match config');
        }
        jettonWalletAddress.value = jettonWalletAddressResult.toString(true, true, true);
        jettonWalletData.value = jettonData;
        console.log('Jetton balance:', jettonData.balance.toString());
        console.log('Jetton Wallet Address:', jettonWalletAddress.value);

    } catch (error) {
        console.error('Error getting Jetton Wallet Address:', error);
    }
}

const { tonConnectUI } = useTonConnectUI();
const { state, open, isOpen } = useTonConnectModal();
const address = useTonAddress();
const wallet = useTonWallet();
const restored = useIsConnectionRestored();

const showModal = ref(false)
const packageCount = ref(1);
const currentPage = ref(1)
const itemsPerPage = 5
const history = ref([])

const alert = ref(false)
const alertMessage = ref(false)
const success = ref(false)
const failed = ref(false)

watch(telegram_id, (newID) => {
  if (newID) {
    fetchHistory()
  }
});

const fetchHistory = async () => {
    try {
        const response = await $fetch(`${baseUrl}history/index.php`, {
            method: 'POST',
            body: {
                telegram_id: telegram_id.value
            }
        })
        if (response.success) {
            history.value = response.history
        }
    } catch (error) {
        console.error('Error fetching history:', error)
    }
}

onMounted(() => {
    fetchHistory()
    getAdminWallet()
})

const totalPages = computed(() => Math.ceil(history.value.length / itemsPerPage))
const paginatedTransactions = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    return history.value.slice(start, start + itemsPerPage)
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

const openModal = () => {
    showModal.value = true
}

const incrementPackage = () => {
    packageCount.value++;
};

const decrementPackage = () => {
    if (packageCount.value > 1) {
        packageCount.value--;
    }
};

const closeModal = () => {
    showModal.value = false
}

const addr = ref(0)
const sendTransaction = async (event) => {
    if (event) {
        event.preventDefault();
    }

    if (!wallet) {
        console.error('Wallet is not defined.');
        return;
    }

    if (!jettonWalletAddress) {
        console.error('Jetton wallet is not defined.');
        return;
    }

    if (!adminWallet) {
        console.error('Admin wallet is not defined.');
        return;
    }

    const url = `${wallet.value.universalLink}`;
    window.open(url, '_blank');

    if (!tonConnectUI.value) {
        console.log("TonConnect UI is not initialized.");
        return;
    }

    const amountUSDT = packageCount.value * 1000000;

    const forwardPayload = beginCell()
        .storeUint(0, 32)
        .storeStringTail('TimeTelegram')
        .endCell();

    const body = beginCell()
        .storeUint(0xf8a7ea5, 32)
        .storeUint(0, 64)
        .storeCoins(amountUSDT)
        .storeAddress(Address.parse(adminWallet.value))
        .storeAddress(Address.parse(wallet.value.account.address))
        .storeBit(0)
        .storeCoins("1")
        .storeBit(1)
        .storeRef(forwardPayload)
        .endCell();

    const transactionPayload = body.toBoc().toString('base64');
    try {
        const result = await tonConnectUI.value.connector.sendTransaction(
            {
                validUntil: Math.floor(Date.now() / 1000) + 360, // Validitas transaksi (menambahkan waktu yang cukup, misalnya 600 detik)
                messages: [
                    {
                        address: jettonWalletAddress.value,
                        amount: toNano("0.064741023").toString(),
                        payload: transactionPayload
                    }
                ],
                twaReturnUrl: linkTMA.value,
                returnStrategy: 'back'
            }
        );

        const boc = result.boc;
        const cell = await TonWeb.boc.Cell.oneFromBoc(TonWeb.utils.base64ToBytes(boc)).hash();
        const hashTransaction = await TonWeb.utils.bytesToHex(cell);

        if (result) {
            await $fetch(`${baseUrl}upgrade_package/index.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    telegram_id: telegram_id.value,
                    amount: packageCount.value,
                    hash: hashTransaction,
                }),
            });

            alertMessage.value = `Package has been successfully upgrade.`;
            alert.value = true;
            success.value = true;
            progressBarWidth.value = 100;
            startTimeout();
        }

    } catch (err) {
        if (err?.message?.includes("User rejects the action")) {
            alertMessage.value = new Error("Transaction has been rejected.");
            alert.value = true;
            failed.value = true;
            progressBarWidth.value = 100;
            startTimeout();
        } else {
            alertMessage.value = new Error("Transaction has been canceled.");
            alert.value = true;
            failed.value = true;
            progressBarWidth.value = 100;
            startTimeout();
        }
    }
};

const copyToClipboard = () => {
    if (address.value !== null) {
        const textToCopy = `${address.value}`;
        navigator.clipboard.writeText(textToCopy).then(
            () => {
                alert.value = true;
                success.value = true;
                alertMessage.value = "Wallet address copied to clipboard.";
                progressBarWidth.value = 100;
                startTimeout();
            },
            (err) => {
                console.error("Failed to copy Address:", err);
            }
        );
    }
};

const formattedAddress = computed(() => {
    return truncateAddress(address.value || '');
});

const truncateAddress = (address) => {
    if (address.length <= 10) {
        return address;
    }
    return `${address.slice(0, 8)}...${address.slice(-8)}`;
};

const progressBarWidth = ref(100);
const timeoutDuration = 3000;
const startTimeout = () => {
    const intervalDuration = 50;
    const decrementAmount = (100 / timeoutDuration) * intervalDuration;

    const interval = setInterval(() => {
        progressBarWidth.value -= decrementAmount;
        if (progressBarWidth.value <= 0) {
            clearInterval(interval);
            alert.value = false;
            success.value = false;
            failed.value = false;
            alertMessage.value = null;
        }
    }, intervalDuration);
};

const disconnect = () => {
    tonConnectUI.value.disconnect();
};

watch(restored, (isRestored) => {
    console.log('Connection restored:', isRestored);
});
watch(state, (newState) => {
    console.log('Modal state:', newState);
});
watch(wallet, (newWallet) => {
    if (newWallet && newWallet.account && newWallet.account.address) {
        console.log('Wallet connected:', newWallet);

        fetchJettonWalletAddress(newWallet.account.address);
    }
});
watch(address, (newAddress) => {
    console.log('address:', newAddress);
});
</script>

<style>
body {
    background: black;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-thumb {
    background-color: #00FF00;
    border-radius: 10px;
}
</style>