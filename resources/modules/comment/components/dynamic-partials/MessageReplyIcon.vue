<template>
    <span class="message-icon" >
        <span class="ico re"> reply </span>
        <span class="auth" v-if="(store.chat.USER[props.uid] !== undefined)">
            <img class="img-to" :src="store.chat.USER[props.uid].avatar?
            store.chat.USER[props.uid].avatar: 'https://ui-avatars.com/api/?name='+store.chat.USER[props.uid].name"/>
            <span>{{store.chat.USER[props.uid].name}}</span>
        </span>
        <span class="auth" v-else>
            <img class="img-to" src="https://ui-avatars.com/api/?name=Guest"/> <span> Guest </span>
        </span>
    </span>
    <div class="reply">
        <message-render :item="message"/>
    </div>
</template>

<script setup>
    import { useSocketStore } from '@/modules/comment/stores/chat.js'
    import MessageRender from './MessageRender.vue'
    const store = useSocketStore()
    const props = defineProps([ 'uid', 'mid', 'rid'])
    const message = store.chat.MESSAGE[props.rid+'_'+props.mid]
</script>
