// ~/stores/userStore.ts
import { defineStore } from 'pinia'

export const useUserStore = defineStore('userStore', {
  state: () => ({
    telegram_id: 0 as number,
  }),
  getters: {
    getTelegramId: (state) => state.telegram_id,
  },
  actions: {
    setTelegramId(newId: number) {
      this.telegram_id = newId;
    },

    resetTelegramId() {
      this.telegram_id = 0;
    },
  },
});
