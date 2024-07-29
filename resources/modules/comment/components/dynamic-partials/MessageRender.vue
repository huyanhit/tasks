<template>
    <message-dynamic :content="formatMessage()"/>
</template>

<script setup>

import Regex from '@/modules/comment/constants/regex';
import Emoji from '@/modules/comment/constants/emoji';
import MessageDynamic from '../dynamic-partials/MessageDynamic.vue'
import { useSocketStore } from '@/modules/comment/stores/chat.js'

const props = defineProps(['item'])

const formatMessage = function() {
    if(props.item){
        // Chuyển một ký tự dặt biệt sang mã hiển thị
        let codeData = [];
        let content  = props.item.content.replace(/<|>|&/g, function (matches) {
            switch (matches) {
                case '<':
                    return '&lt;';
                case '>':
                    return '&gt;';
                case '&':
                    return '&amp;';
            }
        });

        // Escape ký tự bind của vue
        content = content.replace(Regex.TAG.TAG_BIND_VUE_OPEN,  '&#123;&#173;&#123;&#173;');
        content = content.replace(Regex.TAG.TAG_BIND_VUE_CLOSE, '&#125;&#173;&#125;&#173;');

        if (props.item.status !== 0) {

            // Phân tích tag code
            content = processTagCode(content, codeData);

            // Bắt case TO ALL
            content = processToAllMessage(content, props.item);

            //Bắt case Reply
            content = processReplyMessage(content, props.item);

            // Bắt case TO
            content = processToMessage(content, props.item);

            // Gắn tag bold
            content = content.replace(Regex.TAG.TAG_BOLD, function (matchs) {
                matchs = matchs.replace(Regex.TAG.TAG_B_RM_PR, '<b>');
                return matchs.replace(Regex.TAG.TAG_B_RM_EN, '</b>');
            });

            // Gắn tag italic
            content = content.replace(Regex.TAG.TAG_ITALIC, function (matchs) {
                matchs = matchs.replace(Regex.TAG.TAG_I_RM_PR, '<i>');
                return matchs.replace(Regex.TAG.TAG_I_RM_EN, '</i>');
            });

            // Xử lý preview image
            content = processFile(content);

            // Xử lý preview image
            content = processImage(content);

            //Xử lý emoji
            for (let index in Emoji.ICON) {
                const icon = Emoji.ICON[index];
                content = content.split('-' + icon.code).join(`<message-emoji scale="ui_emoticon icon-scale-x" src=${icon.src}></message-emoji>`);
                content = content.split('+' + icon.code).join(`<message-emoji scale="ui_emoticon icon-scale-y" src=${icon.src}></message-emoji>`);
                content = content.split(icon.code).join(`<message-emoji scale="ui_emoticon" src=${icon.src}></message-emoji>`);
            }

            // xử lý tag code
            if (codeData.length > 0) {
                for (let index in codeData) {
                    if (content.indexOf(Regex.REPLACE_MESSAGE.CODE) !== -1) {
                        codeData[index] = codeData[index].replace('[code]',  '<div class="chat-code"><pre class="code_message_format">');
                        codeData[index] = codeData[index].replace('[/code]', '</pre><message-copy-icon class_name="tag-code"/></div>');
                        codeData[index] = codeData[index].replace(Regex.REPLACE_MESSAGE.CODE, Regex.REPLACE_MESSAGE.UN_CODE);
                        content = content.replace(Regex.REPLACE_MESSAGE.CODE, codeData[index]);
                    } else {
                        codeData[index] = codeData[index].replace(Regex.REPLACE_MESSAGE.CODE, Regex.REPLACE_MESSAGE.UN_CODE);
                        content = content.replace(Regex.REPLACE_MESSAGE.UN_CODE, codeData[index]);
                    }
                }
            }

            // Gắn class và thẻ root cho dynamic
            content = '<div class="content-message">' + content + '</div>';
        } else {
            content = '<div class="msg-deleted">Message deleted.</div>';
        }

        return content;
    }
}

const processTagCode = function(content, codeData) {
    if (content.match(Regex.TAG.TAG_CODE)) {
        let frontCode = content.lastIndexOf('[code]');
        let message = content.substring(frontCode);
        let endCode = message.indexOf('[/code]');
        if (frontCode !== -1 && endCode !== -1) {
            endCode += '[/code]'.length;
            message = message.substring(0, endCode);
            content = content.substring(0, frontCode) + Regex.REPLACE_MESSAGE.CODE +
                content.substring(frontCode + endCode, content.length);
            codeData.unshift(message);
        } else {
            content = '';
        }
        content = processTagCode(content, codeData);
    }

    return content
}

const processToAllMessage = function(content, item) {
   return content.replace(Regex.TAG.TO_ALL, function (){
       item.mention = true
       return Regex.HTML.TO_ALL;
   });
}

const processToMessage = function(content, item) {
    return content.replace(Regex.TAG.TO, function (matchs) {
        let toId = matchs.match(Regex.TAG.TO_ID);
        if(parseInt(toId[1]) === useSocketStore().chat.CURRENT_USER.id){
            item.mention = true
        }
        return `<message-to-icon :user_id="${parseInt(toId[1])}"/>`;
    });
}
const processReplyMessage = function(content, item) {
    return content.replace(Regex.TAG.REPLY, function (matchs) {
        let uId = matchs.match(Regex.TAG.REPLY_TO_ID);
        let mId = matchs.match(Regex.TAG.REPLY_MESSAGE_ID);
        let rId = matchs.match(Regex.TAG.REPLY_ROOM_ID);
        if(parseInt(uId[1]) === parseInt(useSocketStore().chat.CURRENT_USER.id)){
            item.mention = true
        }
        return `<message-reply-icon :uid="${parseInt(uId[1])}" :mid="${parseInt(mId[1])}" :rid="${parseInt(rId[1])}"/>`;
    });
}
const processImage = function(content) {
    return content.replace(Regex.TAG.IMG, function (matchs) {
        let image = matchs.match(Regex.TAG.IMG_ID);
        return `<message-image :img_id="${parseInt(image[1])}"/>`;
    });
}
const processFile = function(content) {
    return content.replace(Regex.TAG.FILE, function (matchs) {
        let file = matchs.match(Regex.TAG.FILE_ID);
        return `<message-file :file_id="${parseInt(file[1])}"/>`;
    });
}
</script>
