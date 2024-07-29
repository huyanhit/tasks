<template>
    <div class="conversation-list" v-show="item.status > 0" :class="{mention: item.mention}">
        <div class="chat-avatar" :set="user = getUser(item.auth)">
            <img :src="user?.avatar? user?.avatar: 'https://ui-avatars.com/api/?name='+item.name" :alt="user?.name" v-if="store.chat.CURRENT_USER.id !== item.auth">
        </div>
        <div class="user-chat-content" >
            <div class="ctext-wrap">
                <div class="ctext-wrap-content" :class="{edit: item.status === 3}" id="1">
                    <p class="mb-0 ctext-content ">
                        <message-render :item="item"/>
                    </p>
                </div>
                <div class="dropdown align-self-start message-box-drop ">
                    <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ri-more-2-fill"></i>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item reply-message" @click="reply"><i class="ri-reply-line me-2 text-muted align-bottom"></i>Reply</a>
                        <a class="dropdown-item copy-message"  @click="copy"><i class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>
                        <a class="dropdown-item edit-item" @click="edit"><i class="ri-edit-2-line me-2 text-muted align-bottom"></i>Edit</a>
                        <a class="dropdown-item delete-item" @click="remove"><i class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Delete</a>
                    </div>
                </div>
            </div>
            <div class="conversation-name"><span class="d-none name"></span>
                <small class="text-muted time" v-if="item.status === 4">
                    {{filters.datetime(Date.now()/1000)}}
                </small>
                <small class="text-muted time" v-else> {{ item.updated_at }}</small>
                <span class="text-success check-message-icon" v-if="item.status === 4">
                    <i class="bx bx-loader bx-spin"></i>
                </span>
                <span class="text-success check-message-icon" v-else-if="unread >= item.id && item.status !== 4">
                    <i class="bx bx-check-double"></i>
                </span>
                <span class="text-success check-message-icon" v-else>
                    <i class="bx bx-border-circle"></i>
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>

import MessageRender from '@/modules/chat/components/dynamic-partials/MessageRender.vue'
import { useSocketStore } from '@/modules/chat/stores/chat.js'
import Helper from '@/helpers/common.js'
import filters from '@/helpers/filter.js'

const props = defineProps(['item', 'unread'])
const store = useSocketStore();
const event = Helper.useEvent();

const getUser = function(user_id) {
    return store.chat.USER[user_id];
}

const reply = function() {
    event.emit('change-content', { content: '[reply mid:'+props.item.id+' to:'+props.item.auth+' rid:'+props.item.room_id+']'})
}

const copy = function() {
    navigator.clipboard.writeText(props.item.content);
}

const edit = function() {
    event.emit('edit-message', props.item);
    event.emit('change-content', { content: props.item.content })
}

const remove = function() {
    if(showConfirm('Are you sure delete message?')){
        store.deleteMessage(props.item.id, {room_id: props.item.room_id});
    }
}

</script>

<style scoped>
.ctext-wrap .ctext-wrap-content {
    text-align: left !important;
}
.edit{
    border: #0c9eb9 1px solid;
}
.edit::before{
    content: 'edit';
    position: absolute; right: 3px; top: 0;
    font-size: 8px;
    color: #0c9eb9;
}
.mention .ctext-wrap-content{
    background: #fff !important;
    border: 1px #0ab39c solid;
}
</style>