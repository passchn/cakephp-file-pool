export default function initPlugin() {
    loadFilePool('[data-file-pool]').catch(error => console.error(error));
}

const loadFilePool = async (cssSelector: string) => {
    if (!document.querySelector(cssSelector)) {
        return;
    }
    const {default: initFilePool} = await import("./FilePool/initFilePool");
    initFilePool(cssSelector);
};
