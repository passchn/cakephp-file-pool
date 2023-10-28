<template>
    <form
        class="form"
        ref="selectFileForm"
        @submit.prevent
    >
        <label class="button">
            <span class="button-inner">
                <span class="icon" v-html="icon(faPlusSquare).html"></span>
                <small v-text="buttonLabel"></small>
            </span>
            <input
                name="file"
                type="file"
                @change="onSelectHandler($refs.selectFileForm)"
                class="invisible"
            >
        </label>
    </form>
</template>

<script setup>
import {icon} from "@fortawesome/fontawesome-svg-core";
import {faPlusSquare} from "@fortawesome/free-solid-svg-icons";

defineProps({
    buttonLabel: {
        type: String,
        required: true,
    },
    onSelectHandler: {
        type: Function,
        required: true,
    }
});
</script>

<style lang="scss" scoped>
.form {
    display: contents;
}

.button {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    cursor: pointer;
    border: 1px solid #c9c9c9;
    background-color: #f5f5f5;
    padding: .45rem 2rem;
    transition: background-color .1s ease-out;

    &:hover {
        background-color: #ececec;
        border: 1px solid #c9c9c9;
    }
}

.button-inner {
    display: flex;
    gap: 0.45rem;
    align-items: center;
    max-width: max-content;
}

.invisible {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
}

.icon {
    font-size: 1.5rem;
    line-height: 2rem;
}
</style>
