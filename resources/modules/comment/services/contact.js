import ApiService from '@/services/api-service.js'
export default class UserService extends ApiService {
    getContacts(data) {
        return this.callApi({ method: "get", url: '/chat/contact', param: data })
    }
    addContact(data) {
        return this.callApi({ method: "post", url: '/chat/contact', param: data })
    }
    findContact(data) {
        return this.callApi({ method: "get", url: '/chat/find-contact', param: data })
    }
}