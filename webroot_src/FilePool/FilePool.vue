<template>
    <section
        ref="dropzoneElement"
        class="relative bg-neutral-100 shadow border rounded-xl"
    >
        <div
            class="absolute inset-0 transition-colors flex justify-center items-center"
            :class="{
                'bg-blue-200 shadow-inner bg-opacity-60': allowUpload && isOverDropZone,
                'sr-only': allowUpload && !isOverDropZone
            }"
        >
            <span v-if="allowUpload && isOverDropZone" class="text-2xl animate-pulse">
                Datei(en) hinzufügen
            </span>
        </div>
        <div>
            <div class="p-4 flex flex-wrap gap-3">
                <div
                    v-for="file in files"
                    class="relative group"
                >
                    <a
                        class="badge rounded-lg w-40 grid gap-1"
                        :class="{ 'opacity-80 animate-pulse cursor-wait': !file.isUploaded, 'border-red-600': file.isDeleted }"
                        :href="file.downloadLink || '#'"
                        target="_blank"
                    >
                        <div class="text-sm">
                            {{ file.title }}
                        </div>
                        <div
                            v-if="file.type && file.size"
                            class="text-xs"
                        >
                            {{ file.type }},
                            {{ file.size }}
                        </div>
                        <div
                            class="bg-red-100 text-red-600 p-1 text-xs"
                            v-if="file.getError()"
                        >
                            {{ file.getError() }}
                        </div>
                    </a>
                    <div class="contents" v-if="allowDelete">
                        <a
                            title="Datei bearbeiten"
                            :href="file.editLink"
                            target="_blank"
                            class="absolute hidden group-hover:flex right-5 -top-4 bg-orange-500 text-white px-2 aspect-square rounded-full items-center text-center transition-all hover:-rotate-12 hover:shadow"
                            v-if="allowEdit && file.isUploaded"
                        >
                            <span class="text-sm">
                                <i class="fas fa-pencil"></i>
                            </span>
                        </a>
                        <button
                            type="button"
                            @click="filePool.remove(file.id)"
                            title="Datei löschen"
                            class="absolute hidden group-hover:flex -right-3 -top-2 bg-red-500 text-white px-2 aspect-square rounded-full items-center text-center transition-all hover:-rotate-12 hover:shadow"
                            v-if="file.isUploaded"
                        >
                            <span class="text-sm">
                                <i class="fas fa-trash"></i>
                            </span>
                        </button>
                        <button
                            type="button"
                            @click="file.restore()"
                            title="Löschen abbrechen"
                            class="absolute flex -right-1 -top-1 bg-orange-500 text-white px-2 aspect-square rounded-full items-center text-center transition-all hover:scale-110 hover:shadow"
                            v-if="file.isDeleted"
                        >
                            <span class="text-sm animate-spin-slower">
                                <i class="fas fa-trash-restore"></i>
                            </span>
                        </button>
                    </div>
                </div>
                <form
                    v-if="allowUpload"
                    class="contents"
                    ref="selectFileForm"
                    @submit.prevent
                >
                    <label class="badge rounded-lg px-5 flex items-center cursor-pointer">
                        <span class="flex flex-col items-center gap-1">
                            <span class="text-2xl">
                                <i class="fas fa-plus-square"></i>
                            </span>
                            <small>
                                Hinzufügen
                            </small>
                        </span>
                        <input
                            name="file"
                            type="file"
                            @change="onSelectFile"
                            class="sr-only"
                        >
                    </label>
                </form>
                <div v-if="files.length === 0 && !allowUpload">
                    Keine Dateien
                </div>
            </div>
        </div>
    </section>
</template>

<script lang="ts" setup>

import { ref } from "vue";
import { useDropZone } from "@vueuse/core";
import FilePool from "../Network/FilePool/FilePool";
import useServerFiles from "../Network/FilePool/useServerFiles";

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
    }
});

const dropzoneElement = ref<HTMLDivElement>();
const selectFileForm = ref<HTMLFormElement>();
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

const onSelectFile = () => {
    const formData = new FormData(selectFileForm.value);
    const file = formData.get('file');
    filePool.add(file as File);
    selectFileForm.value.reset();
}

const {isOverDropZone} = useDropZone(dropzoneElement, onDrop);

</script>
