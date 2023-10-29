<template>
    <div class="file-editor">
        <form
            @submit.prevent="onSubmit"
            ref="editForm"
            :class="{'--saving': isSaving}"
        >
            <fieldset :disabled="isSaving">
                <label>
                    {{ translations.get('title') }} <br>
                    <input type="text" name="title" :value="file.title" minlength="1">
                </label>
                <label>
                    {{ translations.get('category') }} <br>
                    <input type="text" name="category" :value="file.category">
                </label>
                <label>
                    {{ translations.get('description') }} <br>
                    <textarea cols="4" name="description" v-text="file.description"></textarea>
                </label>
                <button :disabled="isSaving" type="submit">{{ translations.get('save') }}</button>
            </fieldset>
        </form>
        <div class="error" v-text="error" v-if="error"></div>
    </div>
</template>

<script setup lang="ts">
import Translation from "../../Utils/Translation";
import ServerFile from "../../Network/FilePool/ServerFile";
import {Ref, ref} from "vue";
import FilePool from "../../Network/FilePool/FilePool";

const editForm = ref(null);
const isSaving = ref(false);

const props = defineProps({
    translations: {
        type: Translation,
        required: true,
    },
    file: {
        type: ServerFile,
        required: true,
    },
    filePool: {
        type: FilePool,
        required: true,
    },
});

const emit = defineEmits(['saved']);
const error: Ref<string|null> = ref(null);

const onSubmit = () => {
    if (isSaving.value) {
        return;
    }
    const form = editForm.value as HTMLFormElement | null;
    if (form === null) {
        return;
    }
    const formData = new FormData(form);
    const filePool = props.filePool as FilePool;
    const id = props.file?.id;
    if (!id) {
        return;
    }
    isSaving.value = true;
    filePool
        .edit(id, {
            title: formData.get('title'),
            description: formData.get('description'),
            category: formData.get('category'),
        })
        .then(isSaved => {
            if (isSaved) {
                emit('saved');
                return;
            }
            error.value = 'could not save file';
            isSaving.value = false;
        });
}

</script>

<style lang="scss" scoped>
.file-editor {
    padding: .5rem 1rem;
}

form {
    fieldset {
        display: flex;
        flex-direction: column;
        gap: .65rem;
    }

    label {
        display: block;
    }

    input, textarea {
        display: block;
        width: 100%;
        padding: .5rem;
        border: 1px solid #555;
    }

    button {
        display: inline-block;
        border: 1px solid #555;
        padding: .25rem 1rem;
        cursor: pointer;
        background-color: #fff;
    }

    &.--saving {
        opacity: .65;
    }
}

.error {
    margin-top: .5rem;
    padding: 0.25rem .5rem;
    display: flex;
    align-items: center;
    gap: .5rem;
    line-height: 1rem;
    border-radius: .25rem;
    color: #DC2626;
    background-color: #fff0f0;
}
</style>
