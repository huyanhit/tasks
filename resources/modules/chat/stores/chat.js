import { defineStore } from "pinia";
import { useCookies } from 'vue3-cookies'
import { socket } from "@/socket";
import storage from "@/helpers/storage.js"

import MessageService from '@/modules/chat/services/message'
import RoomService from '@/modules/chat/services/room'
import ContactService from '@/modules/chat/services/contact.js'
import FileService from '@/modules/chat/services/file.js'
import MemberService from '@/modules/chat/services/member.js'
import ReactionService from '@/modules/chat/services/reaction.js'

const messageService = new MessageService();
const roomService = new RoomService();
const memberService = new MemberService();
const contactService = new ContactService();
const reactionService = new ReactionService();
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
            ROOM_MESSAGE:{},
            ROOM_MEMBER: {},
            MEMBER: {},
            MESSAGE: {},
            MY_FILE: {},
            ROOM_FILE: {},
            FILE: {},
            FILE_RAW: {},
            CONTACT: {},
            UNFRIEND: {},
            REQUESTED: {},
            APPROVE: {},
            GUEST:{},
            REACTION:{},
            MESSAGE_REACTION:{}
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
        members: (state) => {
            return storage.mapObject(state.chat.ROOM_MEMBER, state.chat.USER, state.chat.CURRENT_ROOM.id);
        },
        room_files: (state) => {
            return storage.mapObject(state.chat.ROOM_FILE, state.chat.FILE, state.chat.CURRENT_ROOM.id);
        },
        my_files: (state) => {
            return storage.mapObject(state.chat.MY_FILE, state.chat.FILE, state.chat.CURRENT_USER.id);
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
            socket.on("user_add_contact", (data) => {
                storage.merge(this.chat, data)
            });
            socket.on("user_cancel_contact", (data) => {
                storage.merge(this.chat, data)
            });
            socket.on("user_un_request_contact", (data) => {
                storage.merge(this.chat, data)
            });
            socket.on("user_remove_contact", (data) => {
                storage.merge(this.chat, data)
                socket.emit('leave_channel',{'room_id': parseInt(Object.keys(data.ROOM)[0])})
            });
            socket.on("user_update_member", (data) => {
                storage.merge(this.chat, data);
                if(data.ROOM){
                    if(this.rooms.find((item)=> (item.id === parseInt(Object.keys(data.ROOM)[0])))){
                        socket.emit('join_channel',{'room_id': parseInt(Object.keys(data.ROOM)[0])})
                    }else{
                        socket.emit('leave_channel',{'room_id': parseInt(Object.keys(data.ROOM)[0])})
                        event.emit('change-room', {id: 'my-chat'})
                    }
                }
            });
            socket.on("user_approve_contact", (data) => {
                storage.merge(this.chat, data);
                socket.emit('join_channel',{'room_id': parseInt(Object.keys(data.ROOM)[0])})
            });
            socket.on("room_push_message", (data) => {
                let currentId = this.chat.CURRENT_ROOM.id
                if(storage.last(this.chat.ROOM_MESSAGE[currentId]) > storage.last(data.ROOM_MESSAGE[currentId])){
                    delete(data.ROOM_MESSAGE);
                }
                if(this.chat.MESSAGE[Object.keys(data.MESSAGE)[0]]){
                    event.emit('chat_scroll_bottom');
                }else{
                    storage.mergeList(this.chat.ROOM_MESSAGE)
                }

                storage.merge(this.chat, data);
            });
            socket.on("room_update_message", (data) => {
                storage.merge(this.chat, data);
            });
            socket.on("room_update_info", (data) => {
                storage.merge(this.chat, data);
            });
            socket.on("user_add_room", (data) => {
                storage.merge(this.chat, data);
                socket.emit('join_channel',{'room_id':Object.keys(data.ROOM)[0]})
            });
            socket.on("room_update_reaction", (data) => {
                storage.merge(this.chat, data);
            });
        },
        joinChannel(data) {
            socket.emit('join_channel', data);
        },
        async getGuests(data) {
            const response = await contactService.getGuests(data);
            if(response?.success){
                storage.merge(this.chat, response.data)
            }
        },
        async getUserInfo(id) {
            return await contactService.getUserInfo(id);
        },
        async getContacts() {
            const response = await contactService.getContacts();
            if(response?.success){
                storage.merge(this.chat, response.data)
            }

            return response;
        },
        async getUnfriendContact() {
            const response = await contactService.getUnfriendContact();
            if(response?.success){
                storage.merge(this.chat, response.data)
            }

            return response;
        },
        async getApproveContact() {
            const response = await contactService.getApproveContact();
            if(response?.success){
                storage.merge(this.chat, response.data)
            }

            return response;
        },
        async getRequestedContact(data) {
            const response = await contactService.getRequestedContact();
            if(response?.success){
                storage.merge(this.chat, response.data)
            }

            return response;
        },
        async addContact(data) {
            return await contactService.addContact(data);
        },
        async approveContact(data) {
            return await contactService.approveContact(data);
        },
        async unRequestContact(data) {
            return await contactService.unRequestContact(data);
        },
        async cancelContact(data) {
            return await contactService.cancelContact(data);
        },
        async removeContact(data) {
            return await contactService.removeContact(data);
        },
        async getRooms() {
            const response = await roomService.getRooms();
            if(response?.success){
                storage.merge(this.chat, response.data)
            }
            return response;
        },
        async addRoom(data) {
            const response =  await roomService.addRoom(data);
            if(response?.success){
                storage.merge(this.chat, response.data)
            }

            return response;
        },
        async updateRoom(id, data) {
            const response = await roomService.updateRoom(id, data);
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
        async getMessages(data) {
            const response = await messageService.getMessages(data);
            if(response?.success){
                storage.merge(this.chat, response.data)
            }

            return response;
        },
        async getMessage(id, data) {
            const response = await messageService.getMessage(id, data);
            if(response?.success){
                storage.merge(this.chat, response.data)
            }

            return response;
        },
        async searchMessage(data) {
            return await messageService.searchMessage(data);
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
        async removeFile(id) {
            const response = await fileService.removeFile(id);
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
        async updateReaction(data) {
            return await reactionService.updateReaction(data);
        },
    }
})