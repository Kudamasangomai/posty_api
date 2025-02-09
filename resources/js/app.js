import './bootstrap';
import router from "./router";
import { createApp } from "vue";

import App from "./app.vue";

createApp(App).use(router).mount("#app");
