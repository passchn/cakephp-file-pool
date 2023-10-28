<template>
    <div class="file">
        <a
            class="inner"
            :class="{ 'not-uploaded': !file.isUploaded, 'deleted': file.isDeleted }"
            :href="file.downloadLink || '#'"
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
                :title="translations.editFile"
                :href="file.editLink"
                target="_blank"
                class="action action-edit"
                v-if="allowEdit && file.isUploaded"
            >
                <i class="fas fa-pencil"></i>
            </a>
            <button
                type="button"
                @click="filePool.remove(file.id)"
                :title="translations.deleteFile"
                class="action action-delete"
                v-if="file.isUploaded"
            >
                <i class="fas fa-trash"></i>
            </button>
            <button
                type="button"
                @click="file.restore()"
                :title="translations.cancelDeletion"
                class="action action-restore"
                v-if="file.isDeleted"
            >
                <i class="fas fa-trash-restore"></i>
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import ServerFile from "../../Network/FilePool/ServerFile";
import FilePool from "../../Network/FilePool/FilePool";

defineProps({
    file: {
        type: ServerFile,
        required: true,
    },
    translations: {
        type: Object<string, any>,
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
}
.inner {
    border: 1px solid #777;
    border-radius: 1rem;
    padding: .5rem;
    background-color: #fff;

    text-decoration: none;
    color: #333;
    display: block;
}
.not-uploaded {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: .5;
        }
    }
    cursor: wait;
    opacity: 0.8;
}
.deleted {
    border-color: #DC2626;
}
.action {
    aspect-ratio: 1 / 1;
    display: none;
    position: absolute;
    top: -1rem;
    right: 0;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    align-items: center;
    border-radius: 9999px;
    text-align: center;
    color: #ffffff;
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
    :hover {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }

    &.action-edit {
        background-color: yellow;
    }
    &.action-delete {
        background-color: red;
        right: 2.25rem;
    }
    &.action-restore {
        background-color: purple;
        right: 1.25rem;
    }
}
.file:hover .action {
    display: block;
}
.error {
    padding: 0.25rem;
    font-size: 0.75rem;
    line-height: 1rem;
    color: #DC2626;
    background-color: #FEE2E2;
}
</style>
