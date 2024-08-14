<template>
    <div class="conversation-list" v-show="item.status > 0" :class="{
        mention: item.mention,
        animation_4: (item.auth === store.chat.CURRENT_USER.id) && !item.effect && item.status === 4,
    }">
        <div class="chat-avatar" v-if="store.chat.USER[item.auth]">
            <img :src="store.chat.USER[item.auth]?.avatar?? 'https://ui-avatars.com/api/?name='+store.chat.USER[item.auth]?.name"
                 :alt="store.chat.USER[item.auth]?.name" v-if="store.chat.CURRENT_USER.id !== store.chat.USER[item.auth]?.auth">
        </div>
        <div class="chat-avatar" v-else-if="store.chat.GUEST[item.auth]">
            <img :src="store.chat.GUEST[item.auth]?.avatar?? 'https://ui-avatars.com/api/?name='+store.chat.USER[item.auth]?.name"
                 :alt="store.chat.GUEST[item.auth]?.name" v-if="store.chat.CURRENT_USER.id !== store.chat.USER[item.auth]?.auth">
        </div>
        <div class="user-chat-content">
            <div class="ctext-wrap" @mouseenter.prevent.stop="item.reaction = true" @mouseleave.prevent.stop="item.reaction = false">
                <div class="ctext-wrap-content position-relative" :class="{edit: item.status === 3}" id="1">
                    <span class="edit-icon-btn" v-if="item.status === 3" @click="event.emit('cancel-edit-message')">
                        <i class="ri ri-close-fill bg-white"></i>
                    </span>
                    <p class="mb-0 ctext-content">
                        <message-render :item="item"/>
                    </p>
                    <div class="position-absolute reaction-message d-flex" v-if="item.reaction">
                        <a class="dropdown-item reply-message cursor-pointer" v-for="id in Emoji.REACTION" :key="id">
                            <span class="position-relative inline-block" @click.prevent.stop="updateReaction(id)">
                                <img :src="Emoji.ICON[id].src" :alt="Emoji.ICON[id].name"/>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="dropdown align-self-start message-box-drop">
                    <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ri-more-2-fill"></i>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item cursor-pointer" v-if="item.auth !== store.chat.CURRENT_USER.id" @click="reply"><i class="ri-reply-line me-2 text-muted align-bottom"></i>Reply</a>
                        <a class="dropdown-item cursor-pointer" @click="copy"><i class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>
                        <a class="dropdown-item cursor-pointer" v-if="item.auth === store.chat.CURRENT_USER.id" @click="edit"><i class="ri-edit-2-line me-2 text-muted align-bottom"></i>Edit</a>
                        <a class="dropdown-item cursor-pointer" @click="setUnread"><i class="ri-book-read-line me-2 text-muted align-bottom"></i>Set Unread</a>
                        <a class="dropdown-item cursor-pointer" @click="getLink"><i class="ri-link me-2 text-muted align-bottom"></i>Get Link</a>
                        <a class="dropdown-item cursor-pointer" @click="remove"><i class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Delete</a>
                    </div>
                </div>
            </div>
            <div class="d-flex" v-if="item.auth === store.chat.CURRENT_USER.id">
                <span class="reaction-choose">
                    <span class="list" v-for="item in listReaction.icon_reaction" @click="updateReaction(item.emoji_id)">
                        <img class="border"
                             :class="{'border-success': parseInt(item.user_id) === store.chat.CURRENT_USER.id}"
                             v-if="Emoji.ICON[item.emoji_id]" :src="Emoji.ICON[item.emoji_id].src" :alt="Emoji.ICON[item.emoji_id].name"/>
                    </span>
                    <span class="list-reaction" v-if="Object.keys(listReaction.icon_reaction).length > 0">
                        <a role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           <i class="ri-list-check"></i>
                        </a>
                        <div class="dropdown-menu reaction-dropdown">
                            <ul class="nav nav-tabs d-flex flex-nowrap reaction-tab">
                                <li v-for="item in listReaction.icon_reaction" class="nav-item">
                                    <a @click.prevent.stop="activeTab = item" class="nav-link"
                                       :class="{active: item.emoji_id === activeTab.emoji_id}">
                                        <img :src="Emoji.ICON[item.emoji_id].src" :alt="Emoji.ICON[item.emoji_id].name"/>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content p-2">
                                <template v-for="(i, index) in listReaction.user_reaction" :key="index">
                                    <div v-if="i.emoji_id === activeTab.emoji_id" class="p-1 border border-bottom-groove">
                                        <tag-auth :id="i.user_id"/>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </span>
                </span>
                <div class="flex-fill conversation-name">
                    <small class="text-muted time" v-if="item.status === 4">
                            {{filters.fromNow(Date.now())}}
                        </small>
                        <small class="text-muted time" v-else> {{ filters.fromNow(item.created_at) }}</small>
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
            <div class="d-flex" v-else>
                <div class="flex-fill conversation-name">
                    <small class="text-muted time" v-if="item.status === 4">
                        {{filters.fromNow(Date.now())}}
                    </small>
                    <small class="text-muted time" v-else> {{ filters.fromNow(item.created_at) }}</small>
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
                <span class="reaction-choose">
                    <span class="list-reaction" v-if="Object.keys(listReaction.icon_reaction).length > 0">
                        <a role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           <i class="ri-list-check"></i>
                        </a>
                        <div class="dropdown-menu reaction-dropdown">
                            <ul class="nav nav-tabs d-flex flex-nowrap reaction-tab">
                                <li v-for="item in listReaction.icon_reaction" class="nav-item">
                                    <a @click.prevent.stop="activeTab = item" class="nav-link"
                                       :class="{active: item.emoji_id === activeTab.emoji_id}">
                                        <img :src="Emoji.ICON[item.emoji_id].src" :alt="Emoji.ICON[item.emoji_id].name"/>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content p-2">
                                <template v-for="(i, index) in listReaction.user_reaction" :key="index">
                                    <div v-if="i.emoji_id === activeTab.emoji_id" class="p-1 border border-bottom-groove">
                                        <tag-auth :id="i.user_id"/>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </span>
                    <span class="list" v-for="item in listReaction.icon_reaction" @click="updateReaction(item.emoji_id)">
                        <img class="border"
                             :class="{'border-success': parseInt(item.user_id) === store.chat.CURRENT_USER.id}"
                             v-if="Emoji.ICON[item.emoji_id]" :src="Emoji.ICON[item.emoji_id].src" :alt="Emoji.ICON[item.emoji_id].name"/>
                    </span>
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>

import MessageRender from '@/modules/chat/components/dynamic-partials/MessageRender.vue'
import { useSocketStore } from '@/modules/chat/stores/chat.js'
import Helper, { showConfirm } from '@/helpers/common'
import filters from '@/helpers/filter'
import Emoji from '@/modules/chat/constants/emoji.js'
import TagAuth from '@/modules/chat/components/dynamic-partials/TagAuth.vue'

const props = defineProps(['item', 'unread'])
const store = useSocketStore();
const event = Helper.useEvent();
const activeTab = ref(null)
const updateReaction = function(emoji_id) {
    store.updateReaction({room_id:store.chat.CURRENT_ROOM.id, user_id:store.chat.CURRENT_USER.id, message_id:props.item.id, emoji_id:emoji_id})
}

onMounted(() => {
    setTimeout(() => props.item.effect = true, 1000)
})

const listReaction = computed(()=>{
    let key = store.chat.CURRENT_ROOM.id+'_'+props.item.id;
    let reactions = {
        icon_reaction: {},
        user_reaction: {},
    };
    if(store.chat.MESSAGE_REACTION[key]){
        store.chat.MESSAGE_REACTION[key].forEach(item => {
            let reaction = store.chat.REACTION[key+'_'+item]
            if(activeTab.value === null){
                activeTab.value = reaction
            }
            reactions['icon_reaction'][reaction.emoji_id] = reaction
            reactions['user_reaction'][reaction.user_id+'_'+reaction.emoji_id] = reaction
        })
    }

    return reactions;
})

const setUnread = function() {
    event.emit('set_unread', props.item.id)
}

const reply = function() {
    event.emit('change-content', { content: '[reply mid:'+props.item.id+' to:'+props.item.auth+' rid:'+props.item.room_id+'] '})
}
const getLink = function() {
    navigator.clipboard.writeText(window.location.protocol+'//'+window.location.host+'/chat/rid-'+
        store.chat.CURRENT_ROOM.id+'-'+props.item.id);
}

const copy = function() {
    navigator.clipboard.writeText(props.item.content);
}

const edit = function() {
    event.emit('edit-message', props.item);
    event.emit('change-content', { content: props.item.content })
}

const remove = function() {
    showConfirm({title: 'Are you sure delete message?'}).then( async (result) => {
        if (result.isConfirmed) {
            await store.deleteMessage(props.item.id, { room_id: props.item.room_id })
        }
    })
}

</script>

<style>
.ctext-wrap .ctext-wrap-content {
    text-align: left !important;
}
.edit{
    border: #0c9eb9 1px solid;
}
.edit-icon-btn{
    position: absolute; right: 3px; top: 0;
    font-size: 8px;
    color: #0c9eb9;
}
.mention .ctext-wrap-content{
    background: #fff !important;
    border: 1px #0ab39c solid;
}
.reaction-message{
    bottom: -31px;
    background: #f3f6f9;
    border: 1px #ccc solid;
    padding: 5px;
    border-radius: 3px;
    right: calc(50% - 92px);
    z-index: 99;
}

.reaction-message a{
    height: 30px;
    width: 35px;
    display: inline-block;
    text-align: center;
}

.reaction-message img{
    height: 25px;
    width: 25px;
    transition: .2s;
    top: 3px;
    position: relative;
}

.reaction-message img:hover{
    height: 30px;
    width: 30px;
    transition: .5s;
    top: 0px;
}
.reaction-choose{
    top: -2px;
    margin-left: 25px;
    position: relative;
    cursor: pointer;
}
.left .reaction-choose{
    margin-left: 0;
    margin-right: 25px;
}
.reaction-choose img{
    margin: auto;
    height: 22px;
    width: 22px;
}
.reaction-choose .list img{
    padding: 2px;
    border-radius: 5px;
    margin: 0 1px;
}

.ctext-wrap .ctext-wrap-content{
    min-width: 200px;
}
.list-reaction{
    padding: 0 3px;
    background: #ffffff;
    border-radius: 5px;
    margin: 0 5px;
    height: 24px;
    border: 1px #67b173 solid;
    cursor: pointer;
    position: relative;
    top: 1px;
}
.icon-emoji.icon-scale-x img{
    transform: rotate(180deg);
}
.icon-emoji.icon-scale-y img{
    transform: rotate(90deg);
}
.reaction-dropdown{
    padding: 2px;
}
.reaction-tab{
    gap: 0 !important;
}
.reaction-tab .nav-item .nav-link{
    padding: 2px 10px;
}
.animation_4 {
    position: relative;
    animation-name: animation_4;
    animation-duration: 1s;
}

.animation_2 {
    position: relative;
    animation-name: animation_4;
    animation-duration: 1s;
}

@keyframes animation_4 {
    0%   {
        opacity: 0.2;
        right: 200px;
        bottom: -50px;
        transform: rotateX(90deg);
    }
    100% {
        opacity: 1;
        right: 0;
        bottom: 0;
        transform: unset;
    }
}

@keyframes animation_2 {
    0%   {
        opacity: 0.2;
        right: -200px;
        bottom: -50px;
        transform: rotateX(90deg);
    }
    100% {
        opacity: 1;
        right: 0;
        bottom: 0;
        transform: unset;
    }
}
</style>