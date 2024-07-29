<template>
    <div class="text-truncate simple-message" >
        <message-render :item="item"/>
    </div>
</template>

<script setup>

import MessageRender from '@/modules/chat/components/dynamic-partials/MessageRender.vue'
const props = defineProps(['item'])
</script>
<style>
.simple-message{
    height: 20px;
    margin-right: 20px;
}
.simple-message pre{
    font-size: unset;
    font-family: unset;
}
</style>