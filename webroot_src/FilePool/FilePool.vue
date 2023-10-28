<template>
    <section
        ref="dropzoneElement"
        class="file-pool"
    >
        <DragAndDropIndicator
            v-if="isOverDropZone && allowUpload"
            v-text="translations.dragAndDropText"
        />
        <div class="files-list">
            <File
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
                :button-label="translations.addFile"
            />
            <div v-if="files.length === 0 && !allowUpload" v-text="translations.noFiles"></div>
        </div>
    </section>
</template>

<script lang="ts" setup>

import {ref} from "vue";
import {useDropZone} from "@vueuse/core";
import FilePool from "../Network/FilePool/FilePool";
import useServerFiles from "../Network/FilePool/useServerFiles";
import DragAndDropIndicator from "./components/DragAndDropIndicator.vue";
import AddFileButton from "./components/AddFileButton.vue";
import File from "./components/File.vue";

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
        type: Object<string, string>,
    }
});

const dropzoneElement = ref<HTMLDivElement>();
const filePool = new FilePool(props.owner as { source: string, id: string });
const files = useServerFiles(filePool);

const onDrop = (files: File[] | null) => {
    if (!props.allowUpload) {
        return;
    }
    files?.forEach(file => {
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
    background-color: #f5f5f5;
    border: 1px solid #dcdcdc;
    border-radius: .5rem;
}

.files-list {
    display: flex;
    padding: 1rem;
    flex-wrap: wrap;
    gap: 0.75rem;
}
</style>
