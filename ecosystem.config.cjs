module.exports = {
    apps: [
      {
        name: 'TapTon', // Ganti dengan nama aplikasi Anda
        port: '3000',        // Ganti jika Anda ingin menggunakan port lain
        exec_mode: 'cluster', // Menggunakan cluster mode untuk skalabilitas
        instances: 'max',    // Menjalankan di semua core CPU yang tersedia
        script: './.output/server/index.mjs', // Script utama Nuxt 3 setelah build
        env: {
          NODE_ENV: 'production',  // Environment variable untuk production
          NITRO_PORT: 3000,        // Port yang akan digunakan
          NITRO_HOST: '0.0.0.0'    // Alamat host (default: 0.0.0.0)
        }
      }
    ]
  }
  