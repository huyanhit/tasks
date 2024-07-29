<template>
    <div class="chat-input-section">
        <form id="chatinput-form" enctype="multipart/form-data" v-if="store.chat.CURRENT_ROOM.id">
            <div class="row g-0 align-items-center">
                <div class="col-auto">
                    <div class="chat-input-links">
                        <div class="links-list-item d-flex">
                            <div class="dropdown align-self-start message-box-drop">
                                <a class="text-decoration-none border-0 px-1"
                                   role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bx text-success bx-smile align-middle"></i>
                                </a>
                                <div class="dropdown-menu icon-emoji">
                                    <a class="dropdown-item" v-for="(item, index) in data.icon" href="#" :key="index" @click.prevent="addIcon(item)">
                                        <img :src="item.src">
                                    </a>
                                </div>
                            </div>
                            <div class="dropdown align-self-start message-box-drop">
                                <a class="text-decoration-none border-0 px-1"
                                   role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bx text-success bx-user align-middle"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="dropdown-item" @click.prevent="addContact('all')">
                                        <span class="px-2 py-1 bg-info text-white">TO ALL</span>
                                    </li>
                                    <div class="list-contact">
                                        <template v-for="(item, index) in store.users" :key="index" >
                                            <li class="dropdown-item" v-if="item.id !== store.chat.CURRENT_USER.id" @click.prevent="addContact(item)">
                                                <img :src="item.avatar? item.avatar: 'https://ui-avatars.com/api/?name='+item.name"> {{ item.name }}
                                            </li>
                                        </template>
                                    </div>
                                </ul>
                            </div>
                            <a class="text-decoration-none emoji-btn border-0 px-1" v-on:click="boldText">
                                <i class="bx text-success bx-bold align-middle"></i>
                            </a>
                            <a class="text-decoration-none emoji-btn border-0 px-1" v-on:click="italicText">
                                <i class="bx text-success bx-italic align-middle"></i>
                            </a>
                            <a class="text-decoration-none border-0 px-1" data-bs-toggle="modal" data-bs-target="#upload-image">
                                <i class="bx text-success bx-upload align-middle"></i>
                            </a>
                            <a class="text-decoration-none emoji-btn border-0 px-1" v-on:click="codeText">
                                <i class="bx text-success bx-code-alt align-middle"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-0 align-items-center p-1">
                <div class="col" :class="{edit: data.edit && data.edit.status === 3}">
                    <textarea  ref="content" type="text" class="form-control chat-input bg-light border-light"
                               id="chat-input" placeholder="Type your message..." autocomplete="off"
                               :disabled="store.chat.CURRENT_ROOM.id === null"
                               @keyup.shift.enter="sendMessage"
                               @keyup.ctrl.enter="sendMessage"
                               @keyup.esc="resetInput"
                               v-model="data.content">
                    </textarea>
                </div>
                <div class="col-auto">
                    <div class="chat-input-links ms-2">
                        <div class="links-list-item">
                            <button class="btn btn-success chat-send waves-effect waves-light"
                                    @click.prevent="sendMessage">
                                    <i class="ri-send-plane-2-fill align-bottom"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="upload-image" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" ref="myModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Upload File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 ">
                            <div class="border-1">
                                <img v-for="(item, index) in data.scr_preview"
                                     ref="preview_image_upload"
                                     :src="item" class="mx-1 my-2"
                                     :key="index" alt="preview"/>
                                <span v-for="(item, index) in data.icon_preview" v-html="item" :key="index" class="mx-1 my-2 file-upload"></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="customFile">Choose File</label>
                            <input type="file" multiple class="form-control" ref="fileUpload" v-on:change="displayImage($event)"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-primary" v-on:click="uploadFiles">Upload</button>
                    <button type="button" class="btn btn-primary" v-on:click="resetForm">Reset</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import { useSocketStore } from '@/modules/chat/stores/chat.js'
import Helper from '@/helpers/common.js'
import Emoji from '@/modules/chat/constants/emoji'
import { Modal } from 'bootstrap'
import { nextTick, reactive, ref } from 'vue'

const store = useSocketStore();
const event = Helper.useEvent();
const content = ref('');
const myModal = ref('');
const fileUpload = ref('');

const data = reactive({
    content: '',
    sending: false,
    edit: {},
    old_edit_status: 0,
    icon: Emoji.ICON,
    files: [],
    scr_preview: [],
    icon_preview: [],
    waiting_upload: false
})

event.on('change-content', (param) => {
    data.content = param.content;
    if(param.focus){
        nextTick(()=>{
            content.value.setSelectionRange(param.focus, param.focus);
        })
    }

    content.value.focus();
})

event.on('edit-message', (item) => {
    data.old_edit_status = item.status
    data.edit = item
    data.edit.status = 3
})

const sendMessage = async function() {
    if(data.content.trim() !== ''){
        const message = { content: data.content, room_id: store.chat.CURRENT_ROOM.id };
        data.sending = true
        data.content = ''
        if(data.edit && data.edit.status === 3){
            await store.editMessage(data.edit.id, message);
        } else {
            await store.pushMessageTemp(message);
            event.emit('chat_scroll_bottom');
            await store.sendMessage(message);
        }

        data.sending = false
    }
}

const displayImage = function(e){
    data.scr_preview = [];
    for(let item of e.target.files){
        if(item.type.substring(0, 5) === 'image'){
            data.scr_preview.push(URL.createObjectURL(item));
        } else {
            data.icon_preview.push('<i class="ri-folder-zip-line"></i>');
        }
        data.files.push(item)
    }
}

const resetForm = function(){
    data.files          = [];
    data.scr_preview    = [];
    data.waiting_upload = false;
    fileUpload.value.value = "";
}
const resetInput = function(){
    data.edit.status = data.old_edit_status
}

const italicText =  function() {
    event.emit('change-content', { content:'[i]' +  data.content + '[/i]', focus: 3})
}

const boldText =  function() {
    event.emit('change-content',{ content: '[b]' +  data.content + '[/b]', focus: 3})
}
const codeText =  function() {
    event.emit('change-content',{ content: '[code]' +  data.content + '[/code]', focus: 6})
}

const addIcon = function(item){
    event.emit('change-content',{ content: data.content += item.code +' '})
}
const addContact = function(item){
    if(item === 'all'){
        event.emit('change-content',{ content: data.content += '[toall] \n'})
    }else{
        event.emit('change-content',{ content: data.content += '[to:'+item.id+'] \n'})
    }
}

const uploadFiles = async function() {
    if (data.files !== null) {
        let formData = new FormData()
        data.waiting_upload = true
        for (let index = 0; index < data.files.length; index++) {
            if (data.files[index].name !== undefined) {
                formData.append('file[]', data.files[index])
            }
        }

        formData.append('room_id', store.chat.CURRENT_ROOM.id)
        appendFileContent(await store.uploadFile(formData))
    }
}

const appendFileContent = function(response){
    let file = response.data.FILE
    for (let index in file) {
        if(file[index].type === 'image'){
            data.content += '[img:'+ index +'] '
        }else{
            data.content += '[file:'+ index +'] '
        }
    }
    data.files          = [];
    data.scr_preview    = [];
    data.waiting_upload = false;
    fileUpload.value.value = "";
    Modal.getInstance(myModal.value)?.hide();
}
</script>

<style scoped>
.links-list-item a > i{
    font-size: 18px;
    padding: 0 5px;
}
textarea {
    height: 105px
}
.icon-emoji{
    margin-top: 10px;
    min-width: 400px;
}
.icon-emoji .dropdown-item{
    display: inline-block;
    width: 40px;
    padding: 0;
}
.icon-emoji .dropdown-item img{
    margin: 5px;
    height: 25px;
    width: 25px;
    text-align: center;
}
.list-contact{
    min-width: 400px;
    margin-top: 10px;
}
.list-contact img{
    height: 25px;
    width: 25px;
    text-align: center;
}
.file-upload{
    font-size: 40px;
}

.chat-input-links .list-contact{
    max-height: 200px;
    overflow: auto;
}
.edit{
    border: #0c9eb9 1px solid;
}
</style>