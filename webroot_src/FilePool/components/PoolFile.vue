<template>
    <div
        class="file"
        :class="{'--deleting': isDeleting}"
    >
        <div class="file-header">
            <div
                v-if="file.type && file.size"
                class="file-meta"
            >
                {{ file.type }},
                {{ file.size }}
            </div>
            <div class="sorting">
                <button
                    :disabled="sort <= 1"
                    :title="translations.get('sortUp')"
                    v-html="icon(faArrowUp).html"
                    @click="updateSort('up')"
                ></button>
                <button
                    :disabled="sort >= totalFilesCount"
                    :title="translations.get('sortDown')"
                    v-html="icon(faArrowDown).html"
                    @click="updateSort('down')"
                ></button>
            </div>
        </div>
        <div class="file-inner">
            <a
                class="file-info"
                :class="{ 'not-uploaded': !file.isUploaded}"
                :href="file.downloadLink || '#'"
                :title="translations.get('openFile')"
                target="_blank"
            >
                <div>
                    <strong>{{ file.title }}</strong>
                </div>
                <div
                    class="error"
                    v-if="error"
                >
                    <i v-html="icon(faCircleXmark).html"></i>
                    <span v-text="error"></span>
                </div>
            </a>
            <div class="actions" v-if="!error && (allowDelete || allowEdit)">
                <button
                    :title="translations.get('editFile')"
                    class="action action-edit"
                    :class="{'--is-editing': isEditing}"
                    v-if="allowEdit && file.isUploaded"
                    @click="isEditing = !isEditing"
                >
                    <i v-html="icon(faPencil).html"></i>
                </button>
                <button
                    type="button"
                    @click="filePool.remove(file.id)"
                    :title="translations.get('deleteFile')"
                    class="action action-delete"
                    v-if="allowDelete && file.isUploaded && !isDeleting"
                >
                    <i v-html="icon(faTrash).html"></i>
                </button>
                <button
                    type="button"
                    @click="file.restore()"
                    :title="translations.get('cancelDeletion')"
                    class="action action-restore"
                    v-if="isDeleting"
                >
                    <i v-html="icon(faTrashRestore).html"></i>
                </button>
            </div>
        </div>
        <FileEditor
            :translations="translations"
            :file="file"
            v-if="isEditing"
            :file-pool="filePool"
            @saved="isEditing = false"
        />
        <div
            v-if="isUploading"
            class="uploading-hint"
        >
            <i class="icon" v-html="icon(faSpinner).html"></i>
        </div>
    </div>
</template>

<script setup lang="ts">
import ServerFile from "../../Network/FilePool/ServerFile";
import FilePool from "../../Network/FilePool/FilePool";
import Translation from "../../Utils/Translation";
import {icon} from "@fortawesome/fontawesome-svg-core";
import {
    faArrowDown,
    faArrowUp,
    faCircleXmark,
    faPencil,
    faSpinner,
    faTrash,
    faTrashRestore
} from "@fortawesome/free-solid-svg-icons";
import {computed, onMounted, onUpdated, ref} from "vue";
import FileEditor from "./FileEditor.vue";

const isEditing = ref(false);
const sort = computed({
    get() {
        return props.file?.sort || -1;
    },
    set(sort: number) {
        props.sortUpdateHandler(props.file?.id, sort);
    }
});

const updateSort = (direction: 'up' | 'down') => {
    if (direction === 'up') {
        sort.value = sort.value - 1;
    } else {
        sort.value = sort.value + 1;
    }
};

const props = defineProps({
    file: {
        type: ServerFile,
        required: true,
    },
    translations: {
        type: Translation,
        required: true,
    },
    filePool: {
        type: FilePool,
        required: true,
    },
    allowDelete: {
        type: Boolean,
        required: true,
    },
    allowEdit: {
        type: Boolean,
        required: true,
    },
    isDeleting: {
        type: Boolean,
        required: true,
    },
    error: {
        type: String,
        required: false,
        default: null,
    },
    isUploading: {
        type: Boolean,
        required: true,
    },
    sortUpdateHandler: {
        type: Function,
        required: true,
    },
    totalFilesCount: {
        type: Number,
        required: true,
    }
});

</script>

<style lang="scss" scoped>
.file {
    position: relative;
    isolation: isolate;
    border-radius: .65rem;
    border: 1px solid #c9c9c9;
    background-color: #fcfcfc;
    transition: background-color .1s ease-out;

    &:hover {
        background-color: #f5fbff;
        border-color: #b4dbff;
    }
}

.file-header {
    display: flex;
    justify-content: space-between;
    padding: .25rem .6rem;
    background-color: #f5f5f5;
    border-radius: .6rem .6rem 0 0;
    transition: background-color .2s ease-out;
}

.file:hover .file-header {
    background-color: #daf4ff;
}

.file-meta {
    font-size: .75rem;
    color: #444;
}

.sorting {
    display: flex;
    align-items: center;
    gap: .25rem;

    button {
        padding: 0 .25rem;
        font-size: .75rem;
        &:not(:disabled):hover {
            color: dodgerblue;
        }
        &:disabled {
            color: #777;
            cursor: not-allowed;
        }
    }
}

.file-inner {
    display: flex;
    justify-content: space-between;
    gap: 3rem;
}

.file-info {
    display: block;
    text-decoration: none;
    color: #333;
    padding: .5rem .6rem;
    flex-grow: 1;
    max-width: max-content;
    &:hover {
        color: dodgerblue;
    }
}

.actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: .25rem;
    padding: .25rem .3rem;

    .action {
        background-color: #fff;
        border: 1px solid #c9c9c9;
        border-radius: .65rem;
        padding: .15rem;
        aspect-ratio: 1 / 1;
        display: flex;
        height: 100%;
        max-height: 2.75rem;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-sizing: border-box;

        &.action-delete {
            &:hover {
                color: tomato;
                background-color: #fff6f4;
                border-color: tomato;
            }
        }

        &.action-restore {
            color: rebeccapurple;
            border-color: rebeccapurple;
            background-color: #eddcff;

            &:hover {
                border-color: purple;
                background-color: #f6c6f6;
                color: purple;
            }
        }

        &.action-edit {
            &:hover, &.--is-editing {
                border-color: orange;
                color: orange;
                background-color: #fffcf6;
            }
        }
    }
}

.file.--deleting, .file.--deleting:hover {
    background-color: #faf5ff;
    border-color: #ccaaf1;

    .file-info {
        color: #ba92e5;
    }
}

.error {
    padding: 0.25rem;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: .5rem;
    line-height: 1rem;
    border-radius: .25rem;
    color: #DC2626;
    background-color: #fff0f0;
}

.uploading-hint {
    position: absolute;
    cursor: wait;
    inset: 0;
    border-radius: .6rem;
    background-color: rgba(#555, .65);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #fff;

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
