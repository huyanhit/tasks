<template>
    <div class="simplebar-content content-message  mt-2" :set="user = getUser(item.auth)">
        <div class="d-flex" :class="{reply: reply_comment === item.ids}">
            <div class="flex-shrink-0">
                <img :src="user?.avatar? user?.avatar: 'https://ui-avatars.com/api/?name='+user?.name" :alt="user?.name" class="avatar-xs rounded-circle">
            </div>
            <div class="flex-grow-1 ms-1">
                <h5 class="fs-13 my-0">
                    <a href="pages-profile.html">{{user?.name}}</a>
                    <small class="text-muted time" v-if="item.status === 4">
                        {{filters.datetime(Date.now()/1000)}}
                    </small>
                    <small class="text-muted time" v-else> {{ item.updated_at }}</small>
                    <span class="text-success check-message-icon px-2 pt-1" v-if="item.status === 4">
                        <i class="bx bx-loader bx-spin"></i>
                    </span>
                    <span class="text-success check-message-icon px-2 pt-1" v-else-if="unread >= item.id && item.status !== 4">
                        <i class="bx bx-check-double"></i>
                    </span>
                    <span class="text-success check-message-icon px-2 pt-1" v-else>
                        <i class="bx bx-border-circle 1"></i>
                    </span>
                </h5>

                <div class="text-muted">
                    <message-render :item="item"/>
                </div>

                <a href="javascript: void(0);"
                   class="badge text-muted bg-light"
                   :class="{reply: reply_comment === item.ids}"
                   @click="replyComment(item)">
                    <i class="mdi mdi-reply"></i> Reply
                </a>

                <template v-for="(thread, index) in store.chat.MESSAGE_THREAD[item.ids]" :key="index">
                    <message-thread :item="store.chat.THREAD[item.ids+'_'+thread]" :reply_comment="reply_comment"/>
                </template>
            </div>
        </div>
    </div>
</template>

<script setup>

import MessageRender from '@/modules/comment/components/dynamic-partials/MessageRender.vue'
import { useSocketStore } from '@/modules/comment/stores/chat.js'
import Helper from '@/helpers/common.js'
import filters from '@/helpers/filter.js'
import MessageThread from '@/modules/comment/components/MessageThread.vue'

const props = defineProps(['item', 'unread', 'reply_comment'])
const store = useSocketStore();
const event = Helper.useEvent();

const getUser = function(user_id) {
    return store.chat.USER[user_id];
}

const replyComment = function(item) {
    event.emit('reply_comment', item.ids)
    event.emit('focus-input')
}
</script>

<style scoped>
.ctext-wrap .ctext-wrap-content {
    text-align: left !important;
}
.mention .ctext-wrap-content{
    background: #fff !important;
    border: 1px #0ab39c solid;
}
.content-message .active{
    border: 1px #0ab39c solid;
    background: #fff !important;
    padding: 0 5px;
}
</style>