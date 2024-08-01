import 'simple-notify/dist/simple-notify.css'
import Notify from 'simple-notify'
import i18n  from '@/plugins/i18n'
const t = function(val, arg){
    return i18n.global.t('notification.command.'+val, JSON.parse(arg));
}
const showNotificationWindow = function(data){
    const notification = new Notification(
        t(data.title, data.extra), {
            body: t(data.text, data.extra),
            icon: data.auth_avatar ?? 'https://ui-avatars.com/api/?name=a'+data.auth_name
        }
    );
    notification.onclick = (event) => {
        event.preventDefault();
        window.open('/' + data.url);
    };
}
const showNotificationCustom = function(data){
    new Notify({
        status: 'success',
        title: t(data.title, data.extra),
        text: '<a href="/'+data.url+'">'+t(data.text, data.extra).replace("\n","<br>")+'<a>',
        effect: 'fade',
        speed: 300,
        customClass: null,
        customIcon: '<img class="rounded-circle img-fluid " src="'+ (data.auth_avatar ?? 'https://ui-avatars.com/api/?name='+data.auth_name) + '">',
        showIcon: true,
        showCloseButton: true,
        autoclose: data.auto_close === 1,
        autotimeout: 3000,
        gap: 20,
        distance: 20,
        type: 'outline',
        position: data.position
    })
}

export function showNotification(data) {
    if (!window.Notification) {
        showNotificationCustom(data)
    } else {
        if (Notification.permission === 'granted') {
            showNotificationWindow(data)
        } else {
            Notification.requestPermission().then(function(permission) {
                if (permission === 'granted') {
                    showNotificationWindow(data)
                } else {
                    showNotificationCustom(data)
                }
            })
        }
    }
}