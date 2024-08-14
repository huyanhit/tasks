<template>
    <BModal hide-footer v-model="data.show" size="xl" title="Image">
        <b-overlay :show="data.overlay">
            <div class="b-overlay-wrap text-center" style="min-height: 300px" >
                <img src="" alt="" ref="img" class="inline-block min-w-40">
            </div>
        </b-overlay>
    </BModal>
</template>
<script setup>
import FileService from '@/modules/chat/services/file.js'
import Helper from '@/helpers/common'
import { useSocketStore } from '@/modules/chat/stores/chat.js'
import { ref } from 'vue'

const fileService = new FileService();
const store = useSocketStore();
const props = defineProps(['item'])
const event = Helper.useEvent();
const img   = ref(null);

const data = reactive({
    show: false,
    overlay: false
})

event.on('show-image-modal', (id) =>{
    img.value.src = '';
    data.show = true
    data.overlay = true
    loadImage(id)
})

const loadImage = function(id) {
    if (store.chat.FILE_RAW && store.chat.FILE_RAW[id] && store.chat.FILE_RAW[id]['blob'] && img.value) {
        img.value.src = store.chat.FILE_RAW[id]['blob'];
        data.overlay = false
    } else {
        fileService.getImageRaw({ id: id }).then(res => res.blob()).then(blob => {
            if (store.chat.FILE_RAW && store.chat.FILE_RAW[id]) {
                store.chat.FILE_RAW[id]['blob'] = URL.createObjectURL(blob);
            } else {
                store.chat.FILE_RAW = { ...store.chat.FILE_RAW, ...{ [id]: { 'blob': URL.createObjectURL(blob) } } }
            }
            if(img.value)
                img.value.src = store.chat.FILE_RAW[id]? store.chat.FILE_RAW[id]['blob']: null

            data.overlay = false
        })
    }
}

</script>