
<template>
    <img @click.prevent.stop="showImage()" :class="props.class" :src="getImage(img_id)" ref="img" @error.once="imageDefault()" class="cursor-pointer"/>
</template>
<script setup>
import { ref } from 'vue'
import { useSocketStore } from '@/modules/chat/stores/chat.js'
import FileService from '@/modules/chat/services/file.js'
import Helper from '@/helpers/common'
import defaultImage from  '@/assets/images/users/user-dummy-img.jpg'

const img = ref({});
const fileService = new FileService();
const store = useSocketStore();
const event = Helper.useEvent();
const props = defineProps(['img_id', 'class'])

const showImage = function() {
    event.emit('show-image-modal', props.img_id)
}

const imageDefault = function() {
    if(img.value)
        img.value.src = defaultImage
}

const getImage = function(id) {
    if (store.chat.FILE[id] && store.chat.FILE[id]['blob'] && img.value) {
        img.value.src = store.chat.FILE[id]['blob'];
    } else {
        fileService.getImage({ id: id }).then(res => res.blob()).then(blob => {
            if (store.chat.FILE[id]) {
                store.chat.FILE[id]['blob'] = URL.createObjectURL(blob);
            } else {
                store.chat.FILE = { ...store.chat.FILE, ...{ [id]: { 'blob': URL.createObjectURL(blob) } } }
            }
            if(img.value)
                img.value.src = store.chat.FILE[id]? store.chat.FILE[id]['blob']: defaultImage
        })
    }
}
</script>