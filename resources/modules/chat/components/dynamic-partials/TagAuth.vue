<template>
    <span class="fw-bold mx-1" v-if="store.chat.USER[id]">
         {{store.chat.USER[id].name}}
    </span>
</template>

<script setup>
import { useSocketStore } from '@/modules/chat/stores/chat.js'
const store = useSocketStore()
const props = defineProps(['id'])
</script>
<style></style>