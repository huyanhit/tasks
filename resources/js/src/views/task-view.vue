<template>
    <div class="container">
        <article class="flex max-w-xl flex-col items-start w-full bg-gray-100 justify-between p-2  m-2 relative" v-if="data.task !== null">
            <div class="flex items-center">
                <img :src="data.task.auth_info.avatar" :title="data.task.auth_info.name" class="h-6 w-6 bg-gray-50 rounded-full inline-block mr-2">
                <span class="mr-2">Create By </span> <span>{{data.task.auth_info.name}}</span>
            </div>
            <div class="flex items-center text-2xl">
                <a href="#" class="relative py-2 font-medium text-gray-600 hover:bg-gray-100">{{data.task.title}}</a>
            </div>
            <div class="group relative">
                <h3 class="mt-3 text-lg font-semibold leading-6 text-gray-900 group-hover:text-gray-600">
                    <a href="#">
                        <span class="absolute inset-0"></span>
                        {{data.task.content}}
                    </a>
                </h3>
                <p class="mt-5 line-clamp-3 text-sm leading-6 text-gray-600">Comment: {{data.task.comment}}</p>
            </div>
            <div class="mt-2">
                <span class="border-b-black">Project: {{ data.task.project_name }}</span>
            </div>
            <div class="mt-2 flex flex-col py-2 w-full">
                <span class="border-b-black">Members</span>
                <div class="flex-col p-2 bg-gray-200" v-for="(member, index) in data.task.members_info">
                    <img  :key="index" :src="member.avatar" :title="member.name"
                         class="h-8 w-8 rounded-full bg-gray-50 inline-block">
                    <span class="border-b-black p-2">{{ member.name }}</span>
                </div>
            </div>

            <div class="my-2 flex flex-col w-full">
                <time :datetime="data.task.date_start" class="text-gray-500 text-xs basis-1/2"> START {{data.task.date_start}}</time>
            </div>
        </article>
    </div>
</template>
<script setup>

import {onMounted, reactive} from "vue";
import {callApi} from "@/composables/use-api";
import {useAppStore} from "@/stores";
import { useRoute } from 'vue-router'

const store = useAppStore();
const data = reactive({
    task: null,
})

onMounted(async () => {
    const route = useRoute();
    let task = await callApi('task/'+route.params.id, 'get');
    data.task = task.data;
})
</script>
