import Chat from '@/layouts/Chat.vue';
import { defineAsyncComponent } from 'vue'

export default [
    {
        path: '/chat',
        component: Chat,
        children: [
            {
                path: '',
                name: 'chat',
                component: defineAsyncComponent(()=> import('@/modules/chat/views/Chat.vue')),
                children: [{
                    path: ':id',
                    name: 'chat-userid',
                }]
            },
            {
                path: 'rid-:roomId',
                name: 'chat-room',
                component: defineAsyncComponent(()=> import('@/modules/chat/views/Chat.vue')),
            },
        ]
    }
];

