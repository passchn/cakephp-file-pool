import {createApp} from "vue";
import FilePool from "./FilePool.vue";
import Translation from "../Utils/Translation";
import FilePoolHelper from "../Network/FilePool/FilePool";

export default function initFilePool(cssSelector: string) {
    document.querySelectorAll(cssSelector).forEach(_el => {
        const el = _el as HTMLDivElement;
        const translations = el.querySelector('[data-translations]') as HTMLScriptElement;
        const app = createApp(FilePool, {
            allowDelete: parseInt(el.dataset.allowDelete || '') === 1,
            allowEdit: parseInt(el.dataset.allowEdit || '') === 1,
            allowUpload: parseInt(el.dataset.allowUpload || '') === 1,
            translations: new Translation(JSON.parse(translations.textContent || '')),
            filePool: new FilePoolHelper(
                el.dataset.ownerSource as string,
                el.dataset.ownerId as string,
            ),
        });
        try {
            app.mount(el);
        } catch (error) {
            console.error('cannot mount FilePool vue app', error);
        }
    });
}

