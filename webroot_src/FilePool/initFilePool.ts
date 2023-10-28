import { createApp } from "vue";
import FilePool from "./FilePool.vue";

export default function initFilePool(cssSelector: string) {
    document.querySelectorAll(cssSelector).forEach(_el => {
        const el = _el as HTMLDivElement;
        const app = createApp(FilePool, {
            owner: {
                id: el.dataset.ownerId,
                source: el.dataset.ownerSource,
            },
            allowDelete: parseInt(el.dataset.allowDelete || '') === 1,
            allowEdit: parseInt(el.dataset.allowEdit || '') === 1,
            allowUpload: parseInt(el.dataset.allowUpload || '') === 1,
        });
        try {
            app.mount(el);
        } catch (error) {
            console.error('cannot mount FilePool vue app', error);
        }
    });
}

