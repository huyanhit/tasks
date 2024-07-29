import ApiService from '@/services/api-service.js'
export default class MessageService extends ApiService {
    getMessages(data) {
        return this.callApi({ method: "get", url: '/chat/comment', param: data })
    }
    sendThread(data) {
        return this.callApi({ method: "post", url: '/chat/thread', param: data })
    }
    sendMessage(data) {
        return this.callApi({ method: "post", url: '/chat/comment', param: data })
    }
    editMessage(id, data) {
        return this.callApi({ method: "put", url: '/chat/comment/'+id, param: data })
    }
    deleteMessage(id, data) {
        return this.callApi({ method: "delete", url: '/chat/comment/'+id, param: data })
    }
}