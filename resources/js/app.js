import './bootstrap';

import { createApp } from 'vue';
import App from "./App.vue";
import Navbar from './Navbar.vue';
// import YearChart from './chart-section/YearChart.vue';

createApp(App).mount("#app");
createApp(Navbar).mount("#navbar");
// createApp(YearChart).mount("#year-chart");
