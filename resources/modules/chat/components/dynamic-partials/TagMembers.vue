<template>
    <div class="d-flex flex-wrap">
        <span v-for="id in props.members.split('-')" class="fw-bold mx-1">
            <template v-if="store.chat.USER[id]">
                {{store.chat.USER[id].name}},
            </template>
        </span>
    </div>
</template>

<script setup>
import { useSocketStore } from '@/modules/chat/stores/chat.js'
const store = useSocketStore()
const props = defineProps(['members'])
</script>
<style></style>