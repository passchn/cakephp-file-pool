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
                v-if="files.length"
                v-for="file in files"
                :allow-edit="allowEdit"
                :allow-delete="allowDelete"
                :file-pool="filePool"
                :translations="translations"
                :file="file"
                :is-deleting="file.isDeleted"
                :error="file.error"
                :is-uploading="!file.isUploaded && !file.error && !file.isDeleted"
                :sort-update-handler="onUpdateSort"
                :total-files-count="files.length"
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
        <div
            v-if="isUpdatingSort"
            class="updating-sort-indicator"
        >
            <i class="icon" v-html="icon(faSpinner).html"></i>
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
import PoolFile from "./components/PoolFile.vue";
import Translation from "../Utils/Translation";
import {icon} from "@fortawesome/fontawesome-svg-core";
import {faSpinner} from "@fortawesome/free-solid-svg-icons";

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
const isUpdatingSort = ref(false);

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
    form.reset();
    const file = formData.get('file');
    const filePool = props.filePool as FilePool;
    filePool.add(file as File);
}

const onUpdateSort = (fileId: string, newSort: number) => {
    const filePool = props.filePool as FilePool;
    isUpdatingSort.value = true;
    filePool
        .edit(fileId, {
            sort: newSort,
        })
        .then(() => {
            setTimeout(() => {
                isUpdatingSort.value = false;
            }, 1000);
        });
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

.updating-sort-indicator {
    position: absolute;
    inset: 0;
    background-color: rgba(#555, .65);
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 2rem;
    color: #fff;
    border-radius: .6rem;

    .icon {
        display: block;
        animation: spin 1.5s linear infinite;
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    }
}
</style>
