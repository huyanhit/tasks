import ApiService from '@/services/api-service.js'
export default class RoomService extends ApiService {
    getComments(id) {
        return this.callApi({ method: "get", url: '/chat/comment-room/'+id})
    }
    getRoom(id, data) {
        return this.callApi({ method: "get", url: '/chat/room/'+id, param: data })
    }
    setUnreadMessage(id, data) {
        return this.callApi({ method: "put", url: '/chat/update-unread-message/'+id, param: data })
    }
    getModuleRoom(data) {
        return this.callApi({ method: "get", url: '/chat/get-module-room', param: data })
    }
}