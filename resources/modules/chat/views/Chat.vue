<template>
    <div class="container-fluid">
        <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
            <div class="chat-left-sidebar">
                <div class="d-flex p-2">
                    <div class="flex-grow-1">
                        <h3 class="mt-2">Chats</h3>
                    </div>
                    <div class="flex-shrink-0">
                        <contact-manager/>
                    </div>
                </div>
                <div class="d-flex p-2">
                    <room-search v-model="data.search"/>
                    <room-add/>
                </div>
                <!-- .p-4 -->
                <room-list :keyword="data.search"/>
                <!-- end tab contact -->
            </div>
            <div class="user-chat w-100 overflow-hidden">
                <div class="chat-content d-lg-flex">
                    <!-- start chat conversation section -->
                    <div class="w-100 overflow-hidden position-relative">
                        <!-- conversation user -->
                        <div class="position-relative">
                            <div class="position-relative" id="users-chat" style="display: block;">
                                <chat-header/>
                                <!-- end chat user head -->
                                <div class="chat-conversation" id="chat-conversation" data-simplebar="init">
                                    <chat-time-line/>
                                </div>
                            </div>
                            <!-- end chat-conversation -->
                            <chat-input/>
                        </div>
                    </div>
                </div>
            </div>
            <room-detail/>
        </div>
        <!-- end chat-wrapper -->
    </div>
</template>
<script setup>
import { useSocketStore } from '@/modules/chat/stores/chat'
import { onMounted } from 'vue'
import { onBeforeRouteUpdate } from 'vue-router'
import Router from '@/router'
import Helper from '@/helpers/common'

import RoomList from '@/modules/chat/components/RoomList.vue'
import RoomSearch from '@/modules/chat/components/RoomSearch.vue'
import ChatInput from '@/modules/chat/components/ChatInput.vue'
import ChatHeader from '@/modules/chat/components/ChatHeader.vue'

import RoomDetail from '@/modules/chat/components/RoomDetail.vue'
import ContactManager from '@/modules/chat/components/ContactManager.vue'
import ChatTimeLine from '@/modules/chat/components/ChatTimeLine.vue'
import RoomAdd from '@/modules/chat/components/RoomAdd.vue'

const store = useSocketStore();
const event = Helper.useEvent();

store.bindEvents(event);

const data = reactive({
    search: ''
})

event.on('change-room', async (id) => {
    if(store.chat.CURRENT_ROOM.id !== id){
        store.chat.CURRENT_ROOM.id = id;
        Router.push('/chat/rid-' + id);
        const position = Router.currentRoute.value.params.position;
        if(position){
            await store.getRoom(store.chat.CURRENT_ROOM.id, {position: position});
            event.emit('chat_scroll_id', position);
        }else {
            await store.getRoom(store.chat.CURRENT_ROOM.id, {});
            event.emit('chat_scroll_bottom');
        }
    }
})

onMounted(  async () => {
    store.getContacts();
    store.getFiles();
    await store.getRooms();
    if (Router.currentRoute.value.params.roomId) {
        if(store.chat.ROOM[Router.currentRoute.value.params.roomId]){
            event.emit('change-room', parseInt(Router.currentRoute.value.params.roomId));
        } else {
            goMyChat()
        }
    } else {
        goMyChat()
    }
})

onBeforeRouteUpdate((to, from, next) => {
    if(to.name === 'chat-room' && to.params.roomId){
        event.emit('change-room', parseInt(to.params.roomId));
        next();
    }
})

const goMyChat = function(){
    if(store.rooms !== null){
        const myRoom = store.rooms.find(item => item.type === 4);
        event.emit('change-room', myRoom.id);
    }
}

</script>
<style scoped>
.chat-left-sidebar {
    min-width: 300px;
    max-width: 300px;
    background-color: var(--vz-secondary-bg);
}
.chat-content{
    background: url(@/assets/images/chat-bg-pattern.png);;
}
</style>