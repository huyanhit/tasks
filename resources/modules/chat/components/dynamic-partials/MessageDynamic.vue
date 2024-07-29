<template>
    <v-runtime-template :template="content" />
</template>
<script>
import VRuntimeTemplate from "vue3-runtime-template";
import MessageToIcon from '../dynamic-partials/MessageToIcon.vue';
import MessageReplyIcon from '../dynamic-partials/MessageReplyIcon.vue'
import MessageCopyIcon   from '../dynamic-partials/MessageCopyIcon.vue';
import MessageEmoji  from '../dynamic-partials/MessageEmoji.vue';
import MessageImage  from '../dynamic-partials/MessageImage.vue';
import MessageFile   from '../dynamic-partials/MessageFile.vue';
import TagAuth   from '../dynamic-partials/TagAuth.vue';
import TagRoom   from '../dynamic-partials/TagRoom.vue';
import TagMembers   from '../dynamic-partials/TagMembers.vue';
export default {
    name: 'Dynamic',
    props: {
        content: {
            required: true,
            type: String
        }
    },
    components: {
        VRuntimeTemplate,
        MessageToIcon,
        MessageReplyIcon,
        MessageCopyIcon,
        MessageEmoji,
        MessageImage,
        MessageFile,
        TagAuth,
        TagRoom,
        TagMembers
    }
};
</script>
<style>
.ctext-content pre{
    margin: 0;
    font-family: unset;
    font-size: unset;
}
.ctext-content .ico{
    font-size: 0;
}
.content-message .message-icon{
    height: 30px;
    display: inline-block;
}
.content-message .auth .img-to{
    height: 28px;
    width: 28px;
    border-radius: 50%;
    background: #ccc;
    font-size: 0;
    position: relative;
    top: -1px;
}

.ctext-content .auth{
    margin-right: 5px;
}
.ctext-content .re::before{
    content: 'RE';
}
.ctext-content .to::before{
    content: 'TO';
}
.ctext-content .to-all{
    height: 20px;
    width: 55px;
    display: inline-block;
}
.ctext-content .to-all::before{
    content: 'TOALL';
}
.ctext-content .ico::before{
    background: #0ab39c;
    font-size: 13px;
    padding: 2px 6px;
    padding-bottom: 3px;
    margin-right: 3px;
    font-weight: 600;
    font-family: system-ui;
    line-height: 10px;
    color: #fff;
    border-radius: 5px;
    position: relative;
    top: 2px;
}

.message-image{
    height: 100px;
    margin-bottom: 5px;
    display: inline-block;
}

.reply{
    margin-top: 2px;
    padding: 5px;
    border-left: 3px #0c9eb9 solid;
    padding-left: 10px;
    background: #eee;
}

.chat-code{
    margin-top: 2px;
    background: #292D3E !important;
    color: #BFC7D5;
    padding: 5px;
    border-left: 3px #04AA6D solid;
    padding-left: 10px;
    font-size: 14px;
    font-family: source-code-pro,ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,Liberation Mono,Courier New,
    monospacesource-code-pro,ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,Liberation Mono,Courier New,monospace;
}
.code-copy-icon{
    position: absolute;
    top: 0;
    right: 0;
    color: #3d78e3;
    font-size: 11px;
    cursor: pointer;
    text-transform: uppercase;
}

</style>