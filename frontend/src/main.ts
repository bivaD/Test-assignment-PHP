import './assets/main.css'

import axios from 'axios'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(router)

axios.defaults.baseURL = 'http://127.0.0.1:8000/'

app.mount('#app')
