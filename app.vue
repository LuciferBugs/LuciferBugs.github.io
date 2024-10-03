<template>
  <div class="relative h-screen bg-black text-green-500">
    <NuxtPage />
    <Navbar />
  </div>
</template>

<script setup>
import { useUserStore } from '~/stores/userStore'

const userStore = useUserStore()

onMounted(async () => {
  loadData()
});

const loadData = () => {
  Telegram.WebApp.CloudStorage.getItem('QueryTimeTelegram', (err, value) => {
    if (err) {
      console.error('Error loading data:', err);
    } else {
      if (value === '') {
        const dataTg = window.Telegram.WebApp.initDataUnsafe;
        const user = dataTg?.user.id;

        userStore.setTelegramId(user);
        saveData(token);
      } else {
        userStore.setTelegramId(value);
      }
      isLoading.value = false;
    }
  });
};

const saveData = (id) => {
  Telegram.WebApp.CloudStorage.setItem('QueryTimeTelegram', id, (err, saved) => {
    if (err) {
      console.error('Error saving data:', err);
    }
  });
};
</script>
