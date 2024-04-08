import { createApp } from 'vue';
// @ts-ignore
import App from '@/App.vue';

const app = createApp(App);

// pinia store
import { createPinia } from 'pinia';
const pinia = createPinia();
app.use(pinia);

// @ts-ignore
import router from '@/router';
app.use(router);

// main app css
import '@/assets/css/app.css';

// perfect scrollbar
import PerfectScrollbar from 'vue3-perfect-scrollbar';
app.use(PerfectScrollbar);

//dragula
import { VueDragula } from 'vue3-dragula';
import 'dragula/dist/dragula.min.css'
app.use(VueDragula);

//vue-meta
import { createHead } from '@vueuse/head';
const head = createHead();
app.use(head);

// set default settings
// @ts-ignore
import appSetting from '@/app-setting';
appSetting.init();

//vue-i18n
// @ts-ignore
import i18n from '@/i18n';
app.use(i18n);

// popper
import Popper from 'vue3-popper';
app.component('Popper', Popper);

app.mount('#app');
