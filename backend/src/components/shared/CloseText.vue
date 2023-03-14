<script setup>
import { computed } from 'vue';


const props = defineProps({
    textName : String,
    modelValue : Boolean,
});
const isOpen = computed(()=> props.modelValue);
const textName = computed(()=> props.textName);

</script>

<template>
    <span :class="{'close':isOpen}">{{ textName }}</span>
</template>

<style lang="scss" scoped>
    span{
        font-size: 15px;
        &.close{
            display: none;
        }
    }
</style>