import { pluginConfigOptions } from "./types/pluginConfigOptions";

export default function initPlugin(pluginOptions: pluginConfigOptions) {
    loadFilePool(pluginOptions.filePool.cssSelector).catch(error => console.error(error));
}

const loadFilePool = async (cssSelector: string) => {
    if (!document.querySelector(cssSelector)) {
        return;
    }
    const { default: initFilePool } = await import("./FilePool/initFilePool");
    initFilePool(cssSelector);
};
