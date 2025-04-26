import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from 'ziggy-js'
import MainLayout from './Layout/MainLayout.vue'
import { createPinia } from 'pinia'
import { createVuetify } from 'vuetify'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    const page =  pages[`./Pages/${name}.vue`]
    
    page.default.layout = page.default.layout || MainLayout

    return page
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(createPinia())
      .use(createVuetify())
      .mount(el)
  },
})
