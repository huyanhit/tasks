<template>
    <div id="chat-time-line" class="py-3" style="height: 35vh; overflow-y: auto"
         v-on:scroll="eventScrollMessage"
         v-on:mouseover="onHover"
         v-on:mouseleave="data.hover_time_line = false">
         <div class="message-item mb-3" v-for="(item, index) in store.messages"
             :key="index"
             :id="'message-item-'+item.id">
             <message-item :item="item" :unread="store.member?.position" :reply_comment="data.reply_comment"/>
         </div>
    </div>
</template>

<script setup>

import MessageItem from '@/modules/comment/components/MessageItem.vue'

const LOAD_MORE_MESSAGE_UP   = '1';
const LOAD_MORE_MESSAGE_DOWN = '2';

import Helper from '@/helpers/common';
import { useSocketStore } from '@/modules/comment/stores/chat.js'
import { nextTick, reactive } from 'vue'

const store = useSocketStore();
const event = Helper.useEvent();

event.on('chat_scroll_bottom', () => {
    nextTick(()=>{
        scrollBottom()
    })
})

event.on('reply_comment', (id) => {
    data.reply_comment = id
})

const scrollBottom = () =>{
    let box = document.getElementById('chat-time-line');
    if(box !== null) {
        box.scrollTo({ top: box.scrollHeight, behavior: 'smooth' })
    }
}

const data = reactive({
    hover_time_line: false,
    set_unread: false,
    load_more_message: false,
    scroll_bottom: false,
    scroll_data: null,
    reply_comment: 0
})

const callApiLoadMore = async function(position, type) {
    data.load_more_message = true;
    await store.getMessage({ room_id: store.chat.CURRENT_ROOM.id, position: position, type: type })
    data.load_more_message = false;
}

const addMoreMessages = async function(messageId, type) {
    let roomMessages = [];
    if (type === LOAD_MORE_MESSAGE_UP) {
        let position = messageId - 1
        if (store.chat.MESSAGE[store.chat.CURRENT_ROOM.id + "_" + messageId - 1] === undefined) {
            await callApiLoadMore(position, type);
        }

        roomMessages = store.chat.ROOM_MESSAGE[store.chat.CURRENT_ROOM.id].slice(0, 30);
    }
    if (type === LOAD_MORE_MESSAGE_DOWN) {
        if (store.chat.MESSAGE[store.chat.CURRENT_ROOM.id + "_" + messageId] !== undefined) {
            await callApiLoadMore(messageId, type);
        }
        roomMessages = store.chat.ROOM_MESSAGE[store.chat.CURRENT_ROOM.id].slice(-30);
    }

    if (roomMessages !== null) {
        store.chat.ROOM_MESSAGE[store.chat.CURRENT_ROOM.id] = roomMessages;
        scrollToId(messageId, 'auto', type);
    }
}

const scrollToId = function(id, behavior='auto', type = LOAD_MORE_MESSAGE_UP) {
    if (id !== 0) {
        nextTick(() => {
            let box       = document.getElementById('chat-time-line');
            let element   = document.getElementById('message-item-' + id);
            let boxOffset = box.getBoundingClientRect();
            if(element !== null){
                let position = element.getBoundingClientRect().bottom - boxOffset.top + box.scrollTop;
                if(type === LOAD_MORE_MESSAGE_DOWN){
                    box.scrollTo({top:position - box.offsetHeight - 8, behavior:behavior})
                }else{
                    box.scrollTo({top:position - element.offsetHeight - 16, behavior:behavior})
                }
            }
        })
    }
}

const getPositionById= function(id){
    let box = document.getElementById('chat-time-line');
    let element = document.getElementById('message-item-' + id);
    let boxOffset = box.getBoundingClientRect()
    let top = 0;
    if(element){
        top = element.getBoundingClientRect().bottom - boxOffset.top;
        return top + box.scrollTop;
    }

    return top;
}

const pushListPosition = function(){
    let data = []
    for (let index in store.messages){
        data.push({ id: store.messages[index].id,  position:getPositionById(store.messages[index].id)})
    }

    return data;
}

const onHover = function(){
    data.hover_time_line = true;
    if(!data.load_more_message && !data.set_unread && store.member && store.room){
        if(store.member.position < store.room.total){
            data.set_unread = true;
            data.scroll_data = pushListPosition()
            setScrollData();
        }
    }
}

const setScrollData = async function() {
    let room_id = store.room.id;
    let id = store.member.position;
    let box = document.getElementById('chat-time-line');
    let scroll = (box.scrollTop + box.offsetHeight - 18);
    let readMessage = data.scroll_data.find(message => {
        return Math.round(message.position) >= scroll;
    });
    if (readMessage && readMessage.id >= id) {
        await store.setUnread({ room_id: room_id, position: readMessage.id })
        data.set_unread = false
    }
}

const eventScrollMessage = function(){
    if(data.hover_time_line){
        let box = document.getElementById('chat-time-line');
        if(box !== null){
            if (box.scrollTop <= 0 && !data.load_more_message && store.messages !== null) {
                let firstMessage = store.messages[0];
                let messageId = parseInt(firstMessage.id);
                if(messageId > 0){
                    addMoreMessages(messageId, LOAD_MORE_MESSAGE_UP);
                }
            }
            let top = (box.scrollHeight - box.clientHeight);
            if (box.scrollTop >= top && !data.load_more_message && store.messages !== null) {
                let lastMessage = store.messages.slice(-1)[0];
                let messageId = parseInt(lastMessage.id);
                if(messageId < store.room.total){
                    addMoreMessages(lastMessage.id, LOAD_MORE_MESSAGE_DOWN);
                }
            }
        }
    }
}
</script>