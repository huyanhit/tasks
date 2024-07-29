<template>
    <div class="d-flex mt-4" :set="user = getUser(item.auth)"  :class="{reply: reply_comment === item.ids}">
        <div class="flex-shrink-0">
            <img :src="user?.avatar? user?.avatar: 'https://ui-avatars.com/api/?name='+user?.name" :alt="user?.name" class="avatar-xs rounded-circle">
        </div>
        <div class="flex-grow-1 ms-1">
            <h5 class="fs-13 my-0">
                <a href="#">{{user?.name}}</a>
                <small class="text-muted time" v-if="item.status === 4">
                    {{filters.datetime(Date.now()/1000)}}
                </small>
                <small class="text-muted time" v-else> {{ item.updated_at }}</small>
                <span class="text-success check-message-icon" v-if="item.status === 4">
                    <i class="bx bx-loader bx-spin"></i>
                </span>
                <span class="text-success check-message-icon px-1" v-else-if="unread >= item.id && item.status !== 4">
                    <i class="bx bx-check-double"></i>
                </span>
                <span class="text-success check-message-icon px-1" v-else>
                    <i class="bx bx-border-circle 1"></i>
                </span>
            </h5>
            <div class="text-muted">
                <message-render :item="item"/>
            </div>
            <span
               class="badge text-muted bg-light inline-block"
               :class="{active: reply_comment === item.ids}"
               @click="replyComment(item)"><i class="mdi mdi-reply"></i> Reply
            </span>

            <template v-for="(thread, index) in store.chat.MESSAGE_THREAD[item.ids]" :key="index">
                <message-thread :item="store.chat.THREAD[item.ids+'_'+thread]" :reply_comment="reply_comment"/>
            </template>
        </div>
    </div>
</template>

<script setup>

import MessageRender from '@/modules/comment/components/dynamic-partials/MessageRender.vue'
import { useSocketStore } from '@/modules/comment/stores/chat.js'
import Helper from '@/helpers/common.js'
import filters from '@/helpers/filter.js'

const props = defineProps(['item', 'unread', 'reply_comment'])
const store = useSocketStore();
const event = Helper.useEvent();

const getUser = function(user_id) {
    return store.chat.USER[user_id];
}

const replyComment = function(item) {
    event.emit('reply_comment', item.ids);
    event.emit('focus-input')
}

</script>

<style scoped>
.ctext-wrap .ctext-wrap-content {
    text-align: left !important;
}
.mention .ctext-wrap-content {
    background: #fff !important;
    border: 1px #0ab39c solid;
}
</style>