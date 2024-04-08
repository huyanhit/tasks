<template>
    <div class=" w-full">
        <div class="m-2 p-2 bg-gray-500">
            <button class="bg-sky-400 hover:bg-sky-700 px-3 py-2 text-sm leading-5 font-semi text-white mr-2">
                Add Task
            </button>
            <button class="bg-green-500 hover:bg-green-700 px-3 py-2 text-sm leading-5 font-semi text-white">
                Add Status
            </button>
        </div>
    </div>
    <div class="flex flex-row w-full">
        <template v-for="(item, index) in data.status" :key="index">
            <div class="container">
                <div class="bg-gray-400 font-medium text-white uppercase text-center mx-2 p-2"> {{item.title}}</div>
            </div>
        </template>
    </div>
    <div class="flex flex-row">
        <template v-for="(item, index) in data.task_list" :key="index">
            <div class="container" v-dragula="item.task" bag="bag-one" :sid="item.id">
                <div class="bg-blue-300 text-center m-1 px-2" > {{item.name}} </div>
                <article v-for="(task, index) in item.task" :tid="task?.id"
                         class="flex max-w-xl flex-col items-start justify-between p-2 border-2 m-2 bg-gray-100 relative">
                    <div class="flex items-center text-2xl">
                        <img :src="task.auth_info.avatar" :title="task.auth_info.name"
                             class="h-6 w-6 bg-gray-50 inline-block mr-2">
                        <a href="#" class="relative rounded-full bg-gray-50 py-2 font-medium text-gray-600 hover:bg-gray-100">{{task.title}}</a>
                    </div>
                    <div class="absolute right-4 top-4 z-40">
                        <span class="text-muted cursor-pointer h-5 w-5 border-b-black" @click="task.menu = !task.menu">...</span>
                        <ul class="absolute bg-gray-50 p-2 right-3 top-0" v-show="task.menu" >
                            <li class="p-2"><a>View</a></li>
                            <li class="p-2"><a>Edit</a></li>
                            <li class="p-2"><a>Delete</a></li>
                        </ul>
                    </div>
                    <div class="group relative">
                        <h3 class="mt-3 text-lg font-semibold leading-6 text-gray-900 group-hover:text-gray-600">
                            <a href="#">
                                <span class="absolute inset-0"></span>
                                {{task.content}}
                            </a>
                        </h3>
                        <p class="mt-5 line-clamp-3 text-sm leading-6 text-gray-600">{{task.comment}}</p>
                    </div>
                    <div class="mt-2 flex flex-auto bg-gray-200 p-1 w-full">
                        <div class="basis-2/3 mt-2">
                            <span class="border-b-black p-2">{{ task.project_name }}</span>
                        </div>
                        <div class="basis-1/3 text-right">
                            <img v-for="(member, index) in task.members_info" :key="index" :src="member.avatar" :title="member.name"
                                 class="h-8 w-8 rounded-full bg-gray-50 inline-block ">
                        </div>
                    </div>

                    <div class="my-2 flex flex-row w-full">
                        <time :datetime="task.date_start" class="text-gray-500 text-xs basis-1/2"> START {{task.date_start}}</time>
                        <time :datetime="task.date_end" class="text-gray-500 text-xs basis-1/2 text-right">END {{task.date_end}}</time>
                    </div>
                </article>
            </div>
        </template>
    </div>
</template>
<script setup>

import {onMounted, reactive} from "vue";
import {VueDragulaGlobal} from "vue3-dragula";
import {callApi} from "@/composables/use-api";

const data = reactive({
    task_list: [],
    status:[],
    task:[],
})

const getTaskList = () => {
    data.task.sort((a, b) => a.index - b.index);
    return data.status.map(item => {
        item.task = data.task.filter(task => task.status_id === item.id);
        item.task.menu = false;

        return item;
    })
}

const getTasksIndex = (statusIdAppend, taskIdMove, taskIdOver)=> {
    const listTaskUpdate = [];
    // get list tasks have status_id append
    const status = data.task_list.find(item => item.id === statusIdAppend);
    if(status !== null){
        // insert task append into before task mouseover
        for (const task of status.task) {
            if (taskIdOver !== 0 && task.id === taskIdOver){
                listTaskUpdate.push(taskIdMove);
            }
            listTaskUpdate.push(task.id);
        }
        // append into bottom list task if element mouseover null
        if (taskIdOver === 0) {
            listTaskUpdate.push(taskIdMove);
        }
        // update screen
        updateScreen(listTaskUpdate, taskIdMove, statusIdAppend);
    }

    return listTaskUpdate;
}

const updateScreen = (listTaskUpdate, taskIdMove, statusIdAppend) => {
    data.task.find(item => item.id === taskIdMove).status_id = statusIdAppend;
    for (const index in listTaskUpdate) {
        let taskUpdate = data.task.find(item => item.id === listTaskUpdate[index]);
        if(taskUpdate){
            taskUpdate.index = index;
        }
    }

    data.task_list = getTaskList();
}

const getIdAgr = (arg, attr) => {
    if(arg !== null){
        return parseInt(arg.getAttribute(attr));
    }

    return 0;
}

const updateTask = async(id, data)=>{
    await callApi('task/'+id, 'PUT', JSON.stringify(data));
}

VueDragulaGlobal.options('bag-one', {
    direction: 'horizontal',
    copy: false,
});

VueDragulaGlobal.eventBus.on('drop', (args) => {
    const taskIdMove = getIdAgr(args[1], 'tid');
    const statusIdAppend = getIdAgr(args[2], 'sid');
    const taskIdOver = getIdAgr(args[4],'tid');
    const listTaskIndex = getTasksIndex(statusIdAppend, taskIdMove, taskIdOver);

    updateTask(taskIdMove, {
        status: statusIdAppend,
        indexs: listTaskIndex,
    });
});

onMounted(async () => {
    let status = await callApi('status', 'get');
    let task = await callApi('task', 'get');

    data.status = status.data;
    data.task = task.data;
    data.task_list = getTaskList();
})
</script>
