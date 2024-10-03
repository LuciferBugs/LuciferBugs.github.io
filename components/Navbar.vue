<template>
    <nav class="fixed bottom-0 left-0 w-full bg-black border-t-2 border-green-500 rounded-t-lg pt-2">
        <ul class="flex justify-around py-2">
            <!-- Home Button (Index Page) -->
            <li class="flex flex-col items-center">
                <NuxtLink to="/" exact-active-class="active-nav">
                    <button class="flex flex-col items-center focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-1" viewBox="0 0 24 24">
                            <g fill="none" fill-rule="evenodd">
                                <path
                                    d="m12.594 23.258l-.012.002l-.071.035l-.02.004l-.014-.004l-.071-.036q-.016-.004-.024.006l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.016-.018m.264-.113l-.014.002l-.184.093l-.01.01l-.003.011l.018.43l.005.012l.008.008l.201.092q.019.005.029-.008l.004-.014l-.034-.614q-.005-.019-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.003-.011l.018-.43l-.003-.012l-.01-.01z" />
                                <path fill="currentColor"
                                    d="M10.671 2.843a2 2 0 0 1 2.658 0l3.934 3.497l.25-1.504a1 1 0 1 1 1.973.328L19.03 7.91l2.635 2.343a1 1 0 0 1-1.328 1.494l-.464-.412l-.787 7.864A2 2 0 0 1 17.095 21H6.905a2 2 0 0 1-1.99-1.801l-.786-7.864l-.465.412a1 1 0 0 1-1.328-1.494zM5.957 9.71q.028.092.038.191l.91 9.1h10.19l.91-9.1q.01-.1.038-.19L12 4.337z" />
                            </g>
                        </svg>
                        <span class="text-sm">Home</span>
                    </button>
                </NuxtLink>
            </li>

            <!-- Referral Button -->
            <li class="flex flex-col items-center">
                <NuxtLink to="/referral" exact-active-class="active-nav">
                    <button class="flex flex-col items-center focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-1" viewBox="0 0 56 56">
                            <path fill="currentColor"
                                d="M38.446 29.232c4.786 0 8.686-4.263 8.686-9.45c0-5.128-3.88-9.19-8.686-9.19c-4.766 0-8.687 4.122-8.687 9.23c.02 5.167 3.921 9.41 8.687 9.41m-23.164.442c4.142 0 7.54-3.72 7.54-8.284c0-4.464-3.358-8.063-7.54-8.063c-4.142 0-7.56 3.66-7.54 8.103c.02 4.545 3.398 8.244 7.54 8.244m23.164-3.478c-2.936 0-5.45-2.815-5.45-6.374c0-3.5 2.474-6.193 5.45-6.193c2.996 0 5.449 2.654 5.449 6.152c0 3.56-2.473 6.415-5.449 6.415m-23.164.482c-2.453 0-4.544-2.352-4.544-5.248c0-2.835 2.07-5.107 4.544-5.107c2.533 0 4.564 2.232 4.564 5.067c0 2.936-2.091 5.288-4.564 5.288M4.102 48.113h15.785c-.966-.543-1.71-1.75-1.569-2.976H3.6c-.402 0-.603-.16-.603-.543c0-4.986 5.69-9.651 12.266-9.651c2.533 0 4.805.603 6.756 1.749a10.5 10.5 0 0 1 2.272-2.131c-2.594-1.71-5.71-2.594-9.028-2.594C6.837 31.967 0 38.079 0 44.775c0 2.232 1.367 3.338 4.102 3.338m21.716 0h25.256c3.337 0 4.926-1.005 4.926-3.217c0-5.268-6.656-12.89-17.554-12.89c-10.919 0-17.574 7.622-17.574 12.89c0 2.212 1.588 3.217 4.946 3.217m-.965-3.036c-.523 0-.744-.14-.744-.563c0-3.298 5.107-9.47 14.337-9.47c9.21 0 14.316 6.172 14.316 9.47c0 .422-.2.563-.724.563Z" />
                        </svg>
                        <span class="text-sm">Referral</span>
                    </button>
                </NuxtLink>
            </li>

            <!-- Task Button -->
            <li class="flex flex-col items-center">
                <NuxtLink to="/task" exact-active-class="active-nav">
                    <button class="flex flex-col items-center focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-1" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 8h5m0 4h-5m5 4h-5m-5 4h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2m2-4" />
                        </svg>
                        <span class="text-sm">Task</span>
                    </button>
                </NuxtLink>
            </li>

            <!-- Wallet Button -->
            <li class="flex flex-col items-center">
                <NuxtLink to="/wallet" exact-active-class="active-nav">
                    <button class="flex flex-col items-center focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-1" viewBox="0 0 512 512">
                            <rect width="416" height="288" x="48" y="144" fill="none" stroke="currentColor"
                                stroke-linejoin="round" stroke-width="32" rx="48" ry="48" />
                            <path fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"
                                d="M411.36 144v-30A50 50 0 0 0 352 64.9L88.64 109.85A50 50 0 0 0 48 159v49" />
                            <path fill="currentColor" d="M368 320a32 32 0 1 1 32-32a32 32 0 0 1-32 32" />
                        </svg>
                        <span class="text-sm">Wallet</span>
                    </button>
                </NuxtLink>
            </li>
        </ul>
    </nav>
</template>

<script setup>
</script>

<style scoped>
.active-nav {
    color: #00FF00;
    border-bottom: 2px solid #00FF00;
    transition: all 0.3s ease;
}

nav ul {
    margin: 0;
    padding: 0;
}

nav ul li {
    list-style: none;
}

button {
    display: flex;
    flex-direction: column;
    align-items: center;
}

svg {
    display: block;
}

span {
    text-align: center;
}
</style>