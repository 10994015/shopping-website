import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  // server:{
  //   proxy:{
  //     '/api':'http://3.1.217.108:80/api',
  //   },
  // }
})
