<template>
    <div class="link-preview">
        <template v-if="local >= 0">
            <a class="domain text-info text-truncate cursor-pointer" @click="gotoLink">{{ url }}</a>
            <div class="flex-grow-1" v-if="data.room_id && data.message_id">
                <template v-if="store.chat.MESSAGE[data.room_id+'_'+data.message_id]">
                    <message-render :item="store.chat.MESSAGE[data.room_id+'_'+data.message_id]"/>
                </template>
                <template v-else>
                   <span class="spinner-border spinner-border-sm mr-2"></span> loading..
                </template>
            </div>
            <div class="flex-grow-1" v-else-if="data.room_id">
                <tag-room :id="data.room_id"/>
            </div>
        </template>
        <template v-else>
            <div v-if="data.html === null" >
                <a :href="url" class="domain text-info">{{ url }}</a>
                <div class="link-preview-section text-center mt-1">
                    <div class="d-flex justify-content-between">
                        <div class="flex-fill">
                            <Skeletor height="15" class="mb-2" />
                            <Skeletor height="15" class="mb-2" />
                            <Skeletor height="15" class="mb-2"/>
                        </div>
                        <div class="flex-shrink-0">
                            <Skeletor height="60" width="60" class="mx-2" />
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="d-flex link-preview-section" >
                <div class="flex-fill justify-content-end me-2 link-description">
                    <div class="link-data">
                        <a :href="url" class="domain text-info">{{ url }}</a>
                        <div class="link-title ">{{data.html.title}}</div>
                        <div class="link-description">{{data.html.description}}</div>
                    </div>
                </div>
                <div class="flex-grow-1 link-image" v-if="data.html.images">
                    <img v-if="data.html.images[0]" class="avatar-lg" :src="data.html.images[0]" alt="data.html.title"/>
                </div>
            </div>
        </template>
    </div>
</template>
<script setup>
    import { useSocketStore } from '@/modules/chat/stores/chat.js'
    import Helper from '@/helpers/common'
    import TagRoom from '@/modules/chat/components/dynamic-partials/TagRoom.vue'
    import MessageRender from '@/modules/chat/components/dynamic-partials/MessageRender.vue'
    import "vue-skeletor/dist/vue-skeletor.css";
    import { Skeletor } from "vue-skeletor";

    const store    = useSocketStore()
    const props    = defineProps(['url', 'local'])
    const event    = Helper.useEvent();
    const url_meta = `https://jsonlink.io/api/extract?url=`
    const api_key  = `pk_00347f8ed2a7f269cb3b71e37a9f85cd16700c51`;
    const data     = reactive({
        show: false,
        room_id: 0,
        message_id: 0,
        html: null
    })

    onMounted(async () => {
        if(props.local >= 0){
            const pathLink = props.url.match(/rid[-\d+]+[-\d+]/i);
            const arrPath = pathLink[0].split('-')
            if (arrPath[1] && arrPath[2]) {
                data.room_id = arrPath[1]
                data.message_id = arrPath[2]
                await store.getMessage(data.message_id, {room_id: data.room_id })
            } else if (arrPath[1]) {
                data.room_id = arrPath[1]
            }
        } else {
            const apiUrl = url_meta+props.url+'&api_key='+api_key;
            data.html = await fetch(apiUrl).then(response => {
                 return response.json();
            })
        }
    })
    const gotoLink = function(){
        if(data.room_id && data.message_id && store.chat.CURRENT_ROOM.id === data.room_id){
            event.emit('chat_scroll_id', {id: data.message_id});
        }else if(data.room_id && data.message_id){
            event.emit('change-room', {id: data.room_id, position: data.message_id});
        }
    }
</script>
<style>
.link-preview{
    max-width: 700px;
    height: 100px;
    overflow: auto;
    margin-top: 0;
}
.link-preview-section {
    border-radius: 5px;
    line-height: 1.5;
    cursor: pointer;
    white-space: break-spaces;

}

.link-preview-section .link-image{
    height: 100%;
    padding-top: 2px;
}

</style>