
<template>
    <img v-on:click="showImage()" class="message-image mr-2" :src="getImage(img_id)" ref="img"/>
</template>
<script setup>
import { ref } from 'vue'
import { useSocketStore } from '@/modules/chat/stores/chat.js'
import FileService from '@/modules/chat/services/file.js'
import Helper from '@/helpers/common'

const store = useSocketStore();
const img = ref({});
const fileService = new FileService();
const event = Helper.useEvent();

const props = defineProps(['img_id'])
const showImage = function() {
    event.emit('show-image-modal', props.img_id)
}
const getImage = function(id) {
    if (store.chat.FILE[id] && store.chat.FILE[id]['blob'] && img.value) {
        if (store.chat.FILE[id].type === 'image') {
            img.value.src = store.chat.FILE[id]['blob'];
        }
    } else {
        fileService.getImage({ id: id }).then(res => {
            if (res.status === 200) {
                res.blob().then(blob => {
                    if (store.chat.FILE[id] && store.chat.FILE[id]['blob']) {
                        store.chat.FILE[id]['blob'] = URL.createObjectURL(blob);
                    } else {
                        store.chat.FILE = { ...store.chat.FILE, ...{ [id]: { 'blob': URL.createObjectURL(blob) } } }
                    }
                    if (img.value)
                        img.value.src = store.chat.FILE[id] ? store.chat.FILE[id]['blob'] : null
                });
            }
        })
    }
}
</script>