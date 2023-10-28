<template>
    <section
        ref="dropzoneElement"
        class="file-pool"
    >
        <div class="files-list">
            <PoolFile
                v-for="file in files"
                :allow-edit="allowEdit"
                :allow-delete="allowDelete"
                :file-pool="filePool"
                :translations="translations"
                :file="file"
            />
            <AddFileButton
                v-if="allowUpload"
                :on-select-handler="onSelectFile"
                :button-label="translations.get('addFile')"
            />
            <div v-if="files.length === 0 && !allowUpload" v-text="translations.get('noFiles')"></div>
        </div>
        <DragAndDropIndicator
            v-if="isOverDropZone && allowUpload"
            v-text="translations.get('dragAndDropText')"
        />
    </section>
</template>

<script lang="ts" setup>

import {ref} from "vue";
import {useDropZone} from "@vueuse/core";
import FilePool from "../Network/FilePool/FilePool";
import useServerFiles from "../Network/FilePool/useServerFiles";
import DragAndDropIndicator from "./components/DragAndDropIndicator.vue";
import AddFileButton from "./components/AddFileButton.vue";
import PoolFile from "./components/PoolFile.vue";
import Translation from "../Utils/Translation";

const props = defineProps({
    owner: {
        required: true,
        type: Object,
    },
    allowDelete: {
        required: true,
        type: Boolean,
    },
    allowEdit: {
        required: true,
        type: Boolean,
    },
    allowUpload: {
        required: true,
        type: Boolean,
    },
    translations: {
        required: true,
        type: Translation,
    }
});

const dropzoneElement = ref<HTMLDivElement>();
const filePool = new FilePool(props.owner as { source: string, id: string });
const files = useServerFiles(filePool);

const onDrop = (files: File[] | null) => {
    if (!props.allowUpload) {
        return;
    }
    files?.forEach((file) => {
        filePool.add(file);
    });
}

const onSelectFile = (form: HTMLFormElement) => {
    const formData = new FormData(form);
    const file = formData.get('file');
    filePool.add(file as File);
    form.value.reset();
}

const {isOverDropZone} = useDropZone(dropzoneElement, onDrop);

</script>

<style lang="scss">
.file-pool {
    position: relative;
    isolation: isolate;
    background-color: #f6f6f6;
    border: 1px solid #dcdcdc;
    border-radius: .25rem;
}

.files-list {
    position: relative;
    padding: .45rem;
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    gap: 0.45rem;
}
</style>
