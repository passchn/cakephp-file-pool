<template>
    <form
        class="form"
        ref="selectFileForm"
        @submit.prevent
    >
        <label class="button">
            <span class="button-inner">
                <span class="icon"><i class="fas fa-plus-square"></i></span>
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

<style scoped>
.form {
    display: contents;
}
.button {
    display: flex;
    padding-left: 1.25rem;
    padding-right: 1.25rem;
    align-items: center;
    border-radius: 0.5rem;
    cursor: pointer;
}
.button-inner {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    align-items: center;
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
