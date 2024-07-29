import { defineStore } from "pinia";
import { useCookies } from 'vue3-cookies'
import { socket } from "@/socket";
import storage from "@/helpers/storage.js"

import MessageService from '@/modules/comment/services/message'
import RoomService from '@/modules/comment/services/room'
import ContactService from '@/modules/comment/services/contact.js'
import FileService from '@/modules/comment/services/file.js'
import MemberService from '@/modules/comment/services/member.js'

const messageService = new MessageService();
const roomService = new RoomService();
const memberService = new MemberService();
const contactService = new ContactService();
const fileService = new FileService();

const { cookies }  = useCookies();
const auth = cookies.get('auth');

export const useSocketStore = defineStore("socket", {
    state: () => ({
        is_connected: false,
        chat: {
            CURRENT_USER:  {id: auth.id},
            CURRENT_ROOM:  {id: null},
            MY_USER: {},
            USER: {},
            MY_ROOM: {},
            ROOM: {},
            ROOM_MESSAGE: {},
            MEMBER: {},
            MESSAGE: {},
            UNFRIEND: {},
            MY_FILE: {},
            FILE: {},
            THREAD: {},
            MESSAGE_THREAD: {},
        },
    }),
    getters: {
        messages: (state) => {
            return storage.mapObject(state.chat.ROOM_MESSAGE, state.chat.MESSAGE, state.chat.CURRENT_ROOM.id);
        },
        rooms: (state) => {
            return storage.mapObject(state.chat.MY_ROOM, state.chat.ROOM, state.chat.CURRENT_USER.id);
        },
        users: (state) => {
            return storage.mapObject(state.chat.MY_USER, state.chat.USER, state.chat.CURRENT_USER.id);
        },
        member: (state) => {
            return state.chat.MEMBER[state.chat.CURRENT_ROOM.id + '_' + state.chat.CURRENT_USER.id]
        },
        room: (state) => {
            return state.chat.ROOM[state.chat.CURRENT_ROOM.id]
        },
    },
    actions: {
        bindEvents(event) {
            socket.on("connect", () => {
                this.is_connected = true;
            });
            socket.on("disconnect", () => {
                this.is_connected = false;
            });
            socket.on("re-connect", () => {
                this.is_connected = true;
            });
            socket.on("room_push_thread", (data) => {
                storage.merge(this.chat, data);
            });
            socket.on("room_push_message", (data) => {
                let currentId = this.chat.CURRENT_ROOM.id
                if(storage.last(this.chat.ROOM_MESSAGE[currentId]) > storage.last(data.ROOM_MESSAGE[currentId])){
                    delete(data.ROOM_MESSAGE);
                }
                storage.merge(this.chat, data);
                event.emit('chat_scroll_bottom');
            });
            socket.on("room_update_message", (data) => {
                storage.merge(this.chat, data);
            });
            socket.on("user_add_room", (data) => {
                storage.merge(this.chat, data);
                this.join_channel({'room_id':Object.keys(data.ROOM)[0]})
            });
        },
        join_channel(data) {
            socket.emit('join_channel', data);
        },
        async getModuleRoom($data) {
            return await roomService.getModuleRoom($data);
        },
        async getContacts() {
            const response = await contactService.getContacts();
            if(response?.success){
                storage.merge(this.chat, response.data)
            }
            return response;
        },
        async getComments(id) {
            const response = await roomService.getComments(id);
            if(response?.success){
                storage.merge(this.chat, response.data)
            }

            return response;
        },
        async setUnread(data) {
            const response = await memberService.setUnread(data);
            if(response?.success){
                storage.merge(this.chat, response.data)
            }

            return response;
        },
        async getRoom(id, data) {
            const response = await roomService.getRoom(id, data);
            if(response?.success){
                storage.merge(this.chat, response.data)
            }

            return response;
        },
        async sendThread(data) {
            return await messageService.sendThread(data);
        },
        async sendMessage(data) {
            return await messageService.sendMessage(data);
        },
        async editMessage(id, data) {
            return await messageService.editMessage(id, data);
        },
        async deleteMessage(id, data) {
            return await messageService.deleteMessage(id, data);
        },
        async uploadFile(data) {
            const response = await fileService.uploadFile(data);
            if(response?.success){
                storage.merge(this.chat, response.data)
            }

            return response;
        },
        async getFiles(data) {
            const response = await fileService.getFiles(data);
            if(response?.success){
                storage.merge(this.chat, response.data)
            }

            return response;
        },
        async pushMessageTemp(data){
            let messageIdAdd = 1;
            let message = this.chat.ROOM_MESSAGE[this.chat.CURRENT_ROOM.id];
            if(message){
                messageIdAdd = parseInt(message.slice(-1)[0]) + 1
                this.chat.ROOM_MESSAGE[this.chat.CURRENT_ROOM.id].push(messageIdAdd);
            }else {
                this.chat.ROOM_MESSAGE[this.chat.CURRENT_ROOM.id] = [messageIdAdd]
            }
            storage.merge(this.chat,{
                MESSAGE:{[this.chat.CURRENT_ROOM.id + '_' + messageIdAdd]: {
                        content: data['content'],
                        auth: this.chat.CURRENT_USER.id,
                        status: 4
                    }
                }
            })
        }
    }
})