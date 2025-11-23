import "../css/app.css";
import "./bootstrap";
import { createApp } from "vue";

import App from "./App.vue";
import ChatComponent from "./components/ChatComponent.vue";
import ContentComponent from "./components/ContentComponent.vue";
// import router from "./router";

const app = createApp(App);

app.component("content-component", ContentComponent);
app.component("chat-component", ChatComponent);

// app.use(router);

app.mount("#app");
