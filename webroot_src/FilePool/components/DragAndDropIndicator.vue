<template>
    <div class="drag-and-drop">
        <div class="text" v-text="text"></div>
    </div>
</template>

<script setup>
defineProps({
    text: {
        type: String,
        required: true,
    }
});
</script>

<style scoped>
.drag-and-drop {
    display: flex;
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    justify-content: center;
    align-items: center;
    background-color: #d2e8ff;
    border-radius: .5rem;
    opacity: .75;
}

.text {
    font-size: 1.5rem;
    line-height: 2rem;
}
</style>
