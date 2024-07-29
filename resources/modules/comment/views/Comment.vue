<template>
    <div v-if="data.error">
        <d-errors :response="data.error"></d-errors>
    </div>
    <div v-else>
        <chat-time-line/>
        <div class="mt-3">
            <chat-input/>
        </div>
    </div>
</template>
<script setup>
import { useSocketStore } from '@/modules/comment/stores/chat.js'
import { onMounted } from 'vue'
import Helper from '@/helpers/common.js'

import ChatInput from '@/modules/comment/components/ChatInput.vue'
import ChatTimeLine from '@/modules/comment/components/ChatTimeLine.vue'
import DErrors from '@/components/common/DErrors.vue'

const store = useSocketStore();
const event = Helper.useEvent();
const props = defineProps(['object_id', 'module'])
store.bindEvents(event);

const data = reactive({
    error: null
})

onMounted(async () => {
    let module = await store.getModuleRoom({'object_id':props.object_id, 'module':props.module})
    if (module.success) {
        store.chat.CURRENT_ROOM.id = module.data.room_id;
        store.join_channel({'room_id':module.data.room_id})
        store.getComments(module.data.room_id);
        store.getContacts();
        store.getFiles();
        const response = await store.getRoom(store.chat.CURRENT_ROOM.id, {});
        if(!response.success){
            data.error = response
        }
        event.emit('chat_scroll_bottom');
    }
})

</script>
