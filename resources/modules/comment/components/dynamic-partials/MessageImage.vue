
<template>
    <img v-on:click="showImage()" class="message-image mr-2" :src="getImage(img_id)" ref="img"/>
</template>
<script setup>
import { ref } from 'vue'
import FileService from '@/modules/comment/services/file.js'
import { useSocketStore } from '@/modules/comment/stores/chat.js'

const img = ref(null);
const fileService = new FileService();

defineProps(['img_id'])
const showImage = function() {
    alert("tính năng đang thực hiện")
}

const store = useSocketStore();

const getImage = function(id) {
    if (store.chat.FILE[id] && store.chat.FILE[id]['blob'] && img.value) {
        img.value.src = store.chat.FILE[id]['blob'];
    } else {
        fileService.getImage({ id: id }).then(res => res.blob()).then(blob => {
            if(store.chat.FILE[id]){
                store.chat.FILE[id]['blob'] = URL.createObjectURL(blob);
            }else {
                store.chat.FILE = {...store.chat.FILE, ...{[id]:{ 'blob': URL.createObjectURL(blob) }}}
            }
            img.value.src = store.chat.FILE[id]['blob'];
        })
    }
}
</script>