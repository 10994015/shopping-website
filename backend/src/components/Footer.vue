<script setup>
import { computed } from 'vue';

const props = defineProps({
    text:String,
});
const text = computed(()=>props.text);
</script>

<template>
<footer>
    <span>{{text}}</span>
    <span>2023 © <router-link :to="{name:'app.dashboard'}">{{text}}.</router-link> </span>
</footer>
</template>

<style lang="scss" scoped>
footer{
    background-color: #242A30;
    color:#adb5bd;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
    padding: 25px 40px;
    box-shadow: -1px -1px 1px #393d4180;
}
</style>