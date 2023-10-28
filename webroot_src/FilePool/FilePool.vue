<template>
    <section
        ref="dropzoneElement"
        class="file-pool"
    >
        <div
            class="files-list"
            :class="{'--opacity': isOverDropZone}"
        >
            <PoolFile
                v-for="file in files"
                :allow-edit="allowEdit"
                :allow-delete="allowDelete"
                :file-pool="filePool"
                :translations="translations"
                :file="file"
                :is-deleting="file.isDeleted"
            />
            <p
                v-if="files.length === 0 && !allowUpload"
                v-text="translations.get('noFiles')"
            ></p>
            <AddFileButton
                v-if="allowUpload"
                :on-select-handler="onSelectFile"
                :button-label="translations.get('addFile')"
            />
        </div>
        <DragAndDropIndicator
            v-if="isOverDropZone && allowUpload"
            v-text="translations.get('dropFilesToUpload')"
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
    },
    filePool: {
        required: true,
        type: FilePool,
    }
});

const dropzoneElement = ref<HTMLDivElement>();
const files = useServerFiles(props.filePool);

const onDrop = (files: File[] | null) => {
    if (!props.allowUpload) {
        return;
    }
    files?.forEach((file) => {
        props.filePool.add(file);
    });
}

const onSelectFile = (form: HTMLFormElement) => {
    const formData = new FormData(form);
    const file = formData.get('file');
    props.filePool.add(file as File);
    form.value.reset();
}

const {isOverDropZone} = useDropZone(dropzoneElement, onDrop);

</script>

<style lang="scss">
.file-pool {
    position: relative;
    isolation: isolate;
}

.files-list {
    position: relative;
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    gap: 0.35rem;
    &.--opacity {
        min-height: 6rem;
        opacity: .45;
    }
}
</style>
