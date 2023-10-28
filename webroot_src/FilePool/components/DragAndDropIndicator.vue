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
    background-color: #BFDBFE;
    --bg-opacity: 0.6;
    transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
    box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
}

.text {
    font-size: 1.5rem;
    line-height: 2rem;
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: .5;
        }
    }
}
</style>
