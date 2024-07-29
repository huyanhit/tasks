<template>
    <div class="fw-bold mx-1" v-if="store.chat.ROOM[id]">
       {{store.chat.ROOM[id].name}}
    </div>
</template>

<script setup>
import { useSocketStore } from '@/modules/chat/stores/chat.js'
const store = useSocketStore()
const props = defineProps(['id'])
</script>
<style></style>