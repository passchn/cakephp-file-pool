<template>
    <div class="file">
        <a
            class="file-info"
            :class="{ 'not-uploaded': !file.isUploaded, 'deleted': file.isDeleted }"
            :href="file.downloadLink || '#'"
            :title="translations.get('openFile')"
            target="_blank"
        >
            <div>
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
                class="error"
                v-if="file.getError()"
            >
                {{ file.getError() }}
            </div>
        </a>
        <div class="actions" v-if="allowDelete">
            <a
                :title="translations.get('editFile')"
                :href="file.editLink"
                target="_blank"
                class="action action-edit"
                v-if="allowEdit && file.isUploaded"
            >
                <i v-html="icon(faPencil).html"></i>
            </a>
            <button
                type="button"
                @click="filePool.remove(file.id)"
                :title="translations.get('deleteFile')"
                class="action action-delete"
                v-if="file.isUploaded"
            >
                <i v-html="icon(faTrash).html"></i>
            </button>
            <button
                type="button"
                @click="file.restore()"
                :title="translations.get('cancelDeletion')"
                class="action action-restore"
                v-if="file.isDeleted"
            >
                <i v-html="icon(faTrashRestore).html"></i>
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import ServerFile from "../../Network/FilePool/ServerFile";
import FilePool from "../../Network/FilePool/FilePool";
import Translation from "../../Utils/Translation";
import {icon} from "@fortawesome/fontawesome-svg-core";
import {faPencil, faTrash, faTrashRestore} from "@fortawesome/free-solid-svg-icons";

defineProps({
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
    }
});
</script>

<style scoped>
.file {
    position: relative;
    display: flex;
    justify-content: space-between;
    gap: 3rem;
    border-radius: .65rem;
    border: 1px solid #777;
    background-color: #fff;
    &:hover {
        background-color: #e9f6ff;
        border-color: dodgerblue;
    }
}
.file-info {
    display: block;
    text-decoration: none;
    color: #333;
    padding: .5rem 1rem;
    flex-grow: 1;
}
.deleted {
    border-color: #DC2626;
}
.actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: .25rem;
    padding: .15rem .25rem;

    .action {
        background-color: whitesmoke;
        border: 1px solid #eaeaea;
        border-radius: .65rem;
        padding: .15rem;
        aspect-ratio: 1 / 1;
        display: flex;
        height: 100%;
        align-items: center;
        justify-content: center;
        cursor: pointer;

        &.action-delete {
            &:hover {
                color: #fff;
                background-color: tomato;
            }
        }
        &.action-restore {
            color: #fff;
            background-color: rebeccapurple;
            &:hover {
                background-color: purple;
            }
        }
        &.action-edit {
            &:hover {
                background-color: #ffffc0;
                color: orange;
            }
        }
    }
}
.error {
    padding: 0.25rem;
    font-size: 0.75rem;
    line-height: 1rem;
    color: #DC2626;
    background-color: #FEE2E2;
}
</style>
