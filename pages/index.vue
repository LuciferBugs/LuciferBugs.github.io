<template>
  <div class="relative h-screen w-screen bg-black overflow-hidden">
    <canvas id="matrixCanvas" class="absolute top-0 left-0 w-full h-full"></canvas>

    <div class="absolute top-4 left-1/2 transform -translate-x-1/2 whitespace-nowrap mt-8">
      <p class="text-3xl lg:text-6xl md:text-5xl sm:text-4xl text-green-500 font-bold font-digital">
        {{ formattedTime }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useUserStore } from '~/stores/userStore'

const userStore = useUserStore()
const telegram_id = computed(() => userStore.telegram_id);
const baseUrl = useBaseApiUrl();

const days = ref(0)
const hours = ref(0)
const minutes = ref(0)
const seconds = ref(0)
const speed = ref(0)
let intervalId = null

watch(telegram_id, (newID) => {
  if (newID) {
    fetchTimeData()
  }
});

const formattedTime = computed(() => {
  return `${String(days.value).padStart(2, '0')}:${String(hours.value).padStart(2, '0')}:${String(minutes.value).padStart(2, '0')}:${String(seconds.value).padStart(2, '0')}`
})

const initializeTime = ({ days: d, hours: h, minutes: m, seconds: s }) => {
  days.value = d || 0;
  hours.value = h || 0;
  minutes.value = m || 0;
  seconds.value = s || 0;
}

const startClock = () => {
  intervalId = setInterval(() => {
    seconds.value += speed.value;

    if (seconds.value >= 60) {
      seconds.value = 0;
      minutes.value += 1;
    }

    if (minutes.value >= 60) {
      minutes.value = 0;
      hours.value += 1;
    }

    if (hours.value >= 24) {
      hours.value = 0;
      days.value += 1;
    }

  }, 1000)
}

const fetchTimeData = async () => {
  try {
    const response = await $fetch(`${baseUrl}get_timer/index.php`, {
            method: 'POST',
            body: {
                telegram_id: telegram_id.value
            }
        })

    let parsedResponse;
    if (typeof response === 'string') {
      try {
        parsedResponse = JSON.parse(response)
      } catch (error) {
        console.error("Failed to parse JSON:", error)
        return;
      }
    } else {
      parsedResponse = response
    }
    if (parsedResponse && parsedResponse.elapsed_time) {
      speed.value = parsedResponse.speed
      console.log(parsedResponse.elapsed_time)
      initializeTime(parsedResponse.elapsed_time)
      startClock()
    }
  } catch (error) {
    console.error('Error fetching time data:', error)
  }
}

onMounted(() => {
  fetchTimeData()
  initMatrixEffect()
})

onUnmounted(() => {
  if (intervalId) {
    clearInterval(intervalId)
  }
})

const initMatrixEffect = () => {
  const canvas = document.getElementById("matrixCanvas")
  const ctx = canvas.getContext("2d")

  canvas.width = window.innerWidth
  canvas.height = window.innerHeight

  const characters = "Time Telegram".split("")
  const fontSize = 16
  const columns = canvas.width / fontSize
  const drops = Array(Math.floor(columns)).fill(0).map(() => Math.random() * canvas.height / fontSize)
  const speeds = Array(Math.floor(columns)).fill(0).map(() => Math.random() * 2)

  const columnTextIndex = Array(Math.floor(columns)).fill(0).map(() => Math.floor(Math.random() * characters.length))

  const draw = () => {
    ctx.fillStyle = "rgba(0, 0, 0, 0.05)"
    ctx.fillRect(0, 0, canvas.width, canvas.height)

    ctx.fillStyle = "#0F0"
    ctx.font = `${fontSize}px monospace`

    for (let i = 0; i < drops.length; i++) {
      const text = characters[columnTextIndex[i]]

      ctx.fillText(text, i * fontSize, drops[i] * fontSize)

      columnTextIndex[i] = (columnTextIndex[i] + 1) % characters.length

      if (drops[i] * fontSize > canvas.height) {
        drops[i] = 0
      }

      drops[i] += speeds[i]
    }
  }

  setInterval(draw, 33)
}
</script>

<style scoped>
.text-green-500 {
  text-shadow: 0 0 1px #00FF00, 0 0 6px #00FF00;
}

#matrixCanvas {
  background-color: black;
}
</style>
