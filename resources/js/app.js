import "../css/app.css";
import "./bootstrap";
import { createApp } from "vue";

import App from "./App.vue";
import ReportComponent from "./components/ReportComponent.vue";
// import router from "./router";

const app = createApp(App);

app.component("content-component", ReportComponent);

// app.use(router);

app.mount("#app");
