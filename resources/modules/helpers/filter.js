import moment from 'moment'

export default {
    datetime (value, arg1) {
        let date = new Date(value);
        if (arg1 === 'dd/mm/yyyy')
            return (date.toLocaleDateString('vi-VN') + ' ' + date.toLocaleTimeString('vi-VN'));
        return (
            date.getFullYear() + '-' +
            ('0' + (date.getMonth() + 1)).slice(-2) + '-' +
            ('0' + date.getDate()).slice(-2) + ' ' +
            ('0' + date.getHours()).slice(-2) + ':' +
            ('0' + date.getMinutes()).slice(-2)
        );
    },



    time (value, arg1) {
        let date = new Date(value);
        if (arg1 === 'dd/mm/yyyy')
            return (date.toLocaleDateString('vi-VN') + ' ' + date.toLocaleTimeString('vi-VN'));
        return (
            ('0' + date.getHours()).slice(-2) + ':' +
            ('0' + date.getMinutes()).slice(-2)
        );
    },

    date(value) {
        let date = new Date(value);
        return ('0' + (date.getMonth() + 1)).slice(-2) + '/' +
            ('0' + date.getDate()).slice(-2) + '/' +
            date.getFullYear();
    },

    fromNow (value, arg1) {
        return moment(value).fromNow();
    },

    formatByteToMegaByte(value) {
        return (value/1048576).toFixed(2);
    }
}