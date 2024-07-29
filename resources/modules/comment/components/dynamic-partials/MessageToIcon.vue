<template>
    <span class="message-icon">
        <span class="ico to">TO</span>
        <span class="auth" v-if="(store.chat.USER[props.user_id] !== undefined)">
            <img class="img-to" :src="store.chat.USER[props.user_id].avatar?
            store.chat.USER[props.user_id].avatar: 'https://ui-avatars.com/api/?name='+store.chat.USER[props.user_id].name"/>
            <span>{{store.chat.USER[props.user_id].name}}</span>
        </span>
        <span class="auth" v-else>
            <img class="img-to" src="https://ui-avatars.com/api/?name=a"/> <span> Guest </span>
        </span>
    </span>
</template>

<script setup>
import { useSocketStore } from '@/modules/comment/stores/chat.js'
const store = useSocketStore()
const props = defineProps([ 'user_id'])
</script>
<style></style>