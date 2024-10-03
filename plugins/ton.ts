// plugins/ton.ts
import {
    createTonConnectUIProvider,
    TonConnectUIContext,
    TonConnectUIOptionsContext,
    TonConnectButton
} from "ton-ui-vue";

export default defineNuxtPlugin((nuxtApp) => {
    const { tonConnectUI, setOptions } = createTonConnectUIProvider({
        manifestUrl: "https://time-api.prabowo.zip/manifest.json",
        restoreConnection: true
    });

    nuxtApp.vueApp.component("TonConnectButton", TonConnectButton);
    nuxtApp.vueApp.provide(TonConnectUIContext, tonConnectUI);
    nuxtApp.vueApp.provide(TonConnectUIOptionsContext, setOptions);
});