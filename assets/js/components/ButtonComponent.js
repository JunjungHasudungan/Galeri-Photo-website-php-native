// assets/js/component/ButtonComponent.js
import { defineComponent } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';

export default defineComponent({
    template: `
        <button @click="handleClick">Click Me!</button>
    `,
    methods: {
        handleClick() {
            alert('Button clicked!');
        }
    }
});
