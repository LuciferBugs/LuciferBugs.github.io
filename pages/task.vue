<template>
  <div class="p-4 h-screen flex flex-col">
    <h1 class="text-3xl text-green-500 mb-6 font-bold">Complete Tasks</h1>
    <p class="mb-4 text-lg">
      Earn rewards by completing daily tasks! Each completed task will give you additional hours.
    </p>

    <div class="flex justify-between items-center mb-4">
      <h2 class="text-2xl text-green-500 font-semibold">Available Tasks</h2>
      <p class="text-lg text-green-300">Tasks Remaining: <span class="font-semibold">{{ incompleteTasks }}</span></p>
    </div>

    <div class="flex-grow overflow-auto">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div v-for="task in tasks" :key="task.id"
          class="bg-gradient-to-r from-gray-950 to-gray-900 p-4 rounded-lg shadow-lg border border-green-500 transition transform hover:border-green-300 hover:shadow-xl">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xl font-semibold text-green-300">{{ task.description }}</p>
              <p class="text-sm text-gray-400">Reward: <span class="text-green-500">+{{ task.reward }} hours</span></p>
            </div>
            <div>
              <a v-if="task.link && !task.completed && !task.claimed" :href="task.link" target="_blank"
                @click="completeTask(task.id)" class="bg-green-500 text-black px-4 py-2 rounded-lg font-bold">
                Start
              </a>
              <!-- Jika task sudah complete, tombol "Claim" -->
              <button v-else-if="task.completed && !task.claimed"
                class="border-green-500 border text-green-500 px-4 py-2 rounded-lg font-bold"
                @click="claimReward(task.id)">
                Claim
              </button>
              <!-- Jika task sudah di-claim -->
              <span v-else-if="task.claimed" class="text-green-500 font-semibold">Completed</span>
              <!-- Jika task belum complete -->
              <button v-else class="border-green-500 border text-green-500 px-4 py-2 rounded-lg font-bold">
                Not Completed
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Alert modal -->
    <div v-if="alert" class="fixed top-0 left-0 w-full h-full flex items-center justify-center z-[100]">
      <div :class="{ 'text-green-500': success, 'text-red-500': failed }"
        class="bg-gray-900/90 fixed top-2 w-[95%] px-2  flex flex-row rounded-md z-[100]">
        <span class="p-2" v-if="failed">
          <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19.875 6.27c.7.398 1.13 1.143 1.125 1.948v7.284c0 .809-.443 1.555-1.158 1.948l-6.75 4.27a2.27 2.27 0 0 1-2.184 0l-6.75-4.27A2.23 2.23 0 0 1 3 15.502V8.217c0-.809.443-1.554 1.158-1.947l6.75-3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98zM12 8v4m0 4h.01" />
          </svg>
        </span>
        <span class="p-2" v-if="success">
          <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M18 6L7 17l-5-5m20-2l-7.5 7.5L13 16" />
          </svg>
        </span>
        <span class="mt-2 text-xs">{{ alertMessage }}</span>
      </div>
      <div class="bg-white fixed w-[95%] top-7 h-1 mt-2 rounded z-[100]">
        <div :class="{ 'bg-green-500': success, 'bg-red-500': failed }" class="h-full rounded"
          :style="{ width: progressBarWidth + '%' }"></div>
      </div>
    </div>

    <div class="h-40"></div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useUserStore } from '~/stores/userStore'

const userStore = useUserStore()
const telegram_id = computed(() => userStore.telegram_id);
const baseUrl = useBaseApiUrl();

const tasks = ref([])
const alert = ref(false)
const alertMessage = ref(false)
const success = ref(false)
const failed = ref(false)

watch(telegram_id, (newID) => {
  if (newID) {
    fetchTasks()
  }
});

const fetchTasks = async () => {
  try {
    const response = await $fetch(`${baseUrl}get_tasks/index.php`, {
      method: 'POST',
      body: {
        telegram_id: telegram_id.value
      }
    })
    tasks.value = response.tasks
  } catch (error) {
    console.error('Error fetching tasks:', error)
  }
}

onMounted(() => {
  fetchTasks()
})

const completeTask = async (taskId) => {
  const task = tasks.value.find(t => t.id === taskId)
  if (task) {
    try {
      const response = await $fetch(`${baseUrl}complete_tasks/index.php`, {
        method: 'POST',
        body: {
          taskId,
          telegram_id: telegram_id.value
        }
      })
      if (response.success) {
        task.completed = true
        alertMessage.value = `Task has been successfully completed.`;
        alert.value = true;
        success.value = true;
        progressBarWidth.value = 100;
        startTimeout();
      } else {
        alert('Failed to complete task')
      }
    } catch (error) {
      console.error('Error completing task:', error)
    }
  }
}

const claimReward = async (taskId) => {
  const task = tasks.value.find(t => t.id === taskId)
  if (task) {
    try {
      const response = await $fetch(`${baseUrl}claim_tasks/index.php`, {
        method: 'POST',
        body: {
          taskId,
          telegram_id: telegram_id.value
        }
      })
      if (response.success) {
        task.claimed = true
        alertMessage.value = `Task has been successfully claimed.`;
        alert.value = true;
        success.value = true;
        progressBarWidth.value = 100;
        startTimeout();
      } else {
        alert('Failed to claim reward')
      }
    } catch (error) {
      console.error('Error claiming reward:', error)
    }
  }
}

const incompleteTasks = computed(() => {
  return tasks.value.filter(task => !task.completed).length
})

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
</script>

<style>
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-thumb {
  background-color: #00FF00;
  border-radius: 10px;
}
</style>
