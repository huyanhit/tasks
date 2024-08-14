<template>
        <span class="d-flex align-items-center message-icon" >
            <span class="ico re"> reply </span>
            <tag-auth :id="props.uid"/>
        </span>
        <div class="reply">
            <message-render :item="message"/>
        </div>
</template>

<script setup>
    import { useSocketStore } from '@/modules/chat/stores/chat.js'
    import MessageRender from './MessageRender.vue'
    import TagAuth from '@/modules/chat/components/dynamic-partials/TagAuth.vue'
    const store = useSocketStore()
    const props = defineProps([ 'uid', 'mid', 'rid'])
    const message = store.chat.MESSAGE[props.rid+'_'+props.mid]
</script>
