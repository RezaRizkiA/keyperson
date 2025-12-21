import "../css/app.css";
import "./bootstrap";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "ziggy-js";
import VCalendar from "v-calendar";
import "v-calendar/dist/style.css";

createInertiaApp({
  title: title => `${title} - KeyPerson`,
  // Ini memberitahu Vue untuk mencari komponen halaman di folder Pages
  resolve: name =>
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob("./Pages/**/*.vue")
    ),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(VCalendar, {})
      .mount(el);
  },
  progress: {
    color: "#7c3aed", // Warna Violet untuk loading bar
    showSpinner: true,
  },
});
