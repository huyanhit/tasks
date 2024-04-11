<template>
    <div class=" w-full">
        <div class="m-2 p-2 bg-gray-500">
            <Popper :show="data.add_task" :placement="store.rtlClass === 'rtl' ? 'bottom-end' : 'bottom-start'" offsetDistance="8">
                <button class="bg-sky-400 hover:bg-sky-700 px-3 py-2 text-sm leading-5 font-semi text-white mr-2" @click="data.add_task = !data.add_task">
                    Add Task
                </button>
                <template #content="{ close }">
                    <form class="max-w-sm mx-auto bg-gray-50 px-10 py-3 border-2 min-w-[1000px]" >
                        <div class="flex flex-row">
                            <div class="basis-2/3 p-2">
                                <div :class="{ error: vTask.project.$errors.length }" class="mt-2">
                                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Project</label>
                                    <select v-model="data.form_add.project" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option v-for="(project, i) in data.project" :key="i" :value="project.id">{{project.title}}</option>
                                    </select>
                                    <div class="input-errors" v-for="error of vTask.project.$errors" :key="error.$uid">
                                        <div class="error-msg text-red-500 text-xs">{{ error.$message }}</div>
                                    </div>
                                </div>

                                <div :class="{ error: vTask.title.$errors.length }" class="mt-2">
                                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                                    <input type="text" v-model="data.form_add.title" id="title"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <div class="input-errors" v-for="error of vTask.title.$errors" :key="error.$uid">
                                        <div class="error-msg text-red-500 text-xs">{{ error.$message }}</div>
                                    </div>
                                </div>
                                <div :class="{ error: vTask.content.$errors.length }" class="mt-2">
                                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Content</label>
                                    <textarea v-model="data.form_add.content" id="title" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"/>
                                    <div class="input-errors" v-for="error of vTask.content.$errors" :key="error.$uid">
                                        <div class="error-msg text-red-500 text-xs">{{ error.$message }}</div>
                                    </div>
                                </div>

                                <label for="title" class="mt-2 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Assign</label>
                                <div class="border-2 p-3 overflow-auto max-h-[200px]">
                                    <div class="flex  items-center cursor-pointer  w-full" v-for="(item, index) in data.user" :key="index">
                                        <input v-model="item.active" :id="'assign_'+index" type="checkbox" class="flex-none  w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" >
                                        <label :for="'assign_'+index" class="basis-2/3 flex-1 relative top-1 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Assign to {{item.name}}</label>
                                        <select v-model="item.role" class="basis-1/3 flex-1 bg-gray-50 border w-[100px] m-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option v-for="(role, i) in data.role" :key="i" :value="role.id">{{role.title}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="basis-1/3 p-2 flex flex-row">
                                <div :class="{ error: vTask.date_start.$errors.length }" class="mt-2">
                                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date Start</label>
                                    <vue-date-picker
                                        v-model="data.form_add.date_start" inline auto-apply timezone="UTC"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    />
                                    <div class="input-errors" v-for="error of vTask.date_start.$errors" :key="error.$uid">
                                        <div class="error-msg text-red-500 text-xs">{{ error.$message }}</div>
                                    </div>
                                </div>

                                <div :class="{ error: vTask.date_end.$errors.length }" class="mt-2">
                                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date End</label>
                                    <vue-date-picker
                                        v-model="data.form_add.date_end" inline auto-apply timezone="UTC"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    />
                                    <div class="input-errors" v-for="error of vTask.date_end.$errors" :key="error.$uid">
                                        <div class="error-msg text-red-500 text-xs">{{ error.$message }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <button @click.prevent="addTask" class="mr-2 bg-green-500 hover:bg-green-700 px-3 py-2 text-sm leading-5 font-semi text-white">
                                Add Task
                            </button>
                            <button @click.prevent="data.add_task = false" class="bg-blue-500 hover:bg-blue-700 px-3 py-2 text-sm leading-5 font-semi text-white">
                                Close
                            </button>
                        </div>
                    </form>
                </template>
            </Popper>
            <Popper :show="data.add_status" :placement="store.rtlClass === 'rtl' ? 'bottom-end' : 'bottom-start'" offsetDistance="8">
                <button class="bg-green-500 hover:bg-green-700 px-3 py-2 text-sm leading-5 font-semi text-white" @click="data.add_status = !data.add_status">
                    Add Status
                </button>
                <template #content="{ close }">
                    <form class="max-w-sm mx-auto bg-gray-50 px-10 py-3 border-2 min-w-[600px]" >
                        <div :class="{ error: vStatus.title.$errors.length }" class="mt-2">
                            <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                            <input type="text" v-model="data.form_status.title" id="title"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <div class="input-errors" v-for="error of vStatus.title.$errors" :key="error.$uid">
                                <div class="error-msg text-red-500 text-xs">{{ error.$message }}</div>
                            </div>
                        </div>
                        <label for="title" class="mt-2 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Position</label>
                        <div class="flex">
                            <select v-model="data.form_status.position" class="mr-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option v-for="(position, i) in data.positions" :key="i" :value="position">{{position}}</option>
                            </select>
                            <select v-model="data.form_status.index" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option v-for="(status, i) in data.status" :key="i" :value="status.index">{{status.title}}</option>
                            </select>
                        </div>

                        <div class="mt-4 text-center">
                            <button @click.prevent="addStatus" class="mr-2 bg-green-500 hover:bg-green-700 px-3 py-2 text-sm leading-5 font-semi text-white">
                                Add Status
                            </button>
                            <button @click.prevent="data.add_status = false" class="bg-blue-500 hover:bg-blue-700 px-3 py-2 text-sm leading-5 font-semi text-white">
                                Close
                            </button>
                        </div>
                    </form>
                </template>
            </Popper>
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
        <div v-show="data.process">
            <div role="status" class="absolute left-0 right-0 bottom-0 top-0 bg-opacity-50 bg-gray-400 z-50 text-center align-middle">
                <svg aria-hidden="true" class="absolute top-[50%] inline-block w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                </svg>
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <template v-for="(item, index) in data.task_list" :key="index">
            <div class="container" v-dragula="item.task" bag="bag-one" :sid="item.id">
                <div class="bg-blue-300 text-center m-1 px-2" > {{item.name}} </div>
                <article v-for="(task, index) in item.task" :tid="task?.id"
                         class="flex max-w-xl flex-col items-start justify-between p-2 border-2 m-2 relative"
                         :class="colorTaskDeadline(task.date_start, task.date_end)">
                    <div class="flex items-center text-2xl">
                        <img :src="task.auth_info.avatar" :title="task.auth_info.name"
                             class="h-6 w-6 bg-gray-50 inline-block mr-2">
                        <a href="#" class="relative py-2 font-medium text-gray-600 hover:bg-gray-100">{{task.title}}</a>
                    </div>
                    <div class="absolute right-4 top-4 z-40">
                        <span class="text-muted cursor-pointer h-20 w-20 border-b-black" @click="task.menu = !task.menu">...</span>
                        <ul class="absolute bg-gray-50 p-2 right-3 top-0" v-show="task.menu" >
                            <li class="p-2"><a :href="'/task-view/'+task.id">View</a></li>
                            <li class="p-2"><a :href="'/task-edit/'+task.id">Edit</a></li>
                            <li class="p-2"><a @click="deleteTask(task)">Delete</a></li>
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
                        <div class="basis-1/3 mt-2">
                            <span class="border-b-black p-2">{{ task.project_name }}</span>
                        </div>
                        <div class="basis-2/3 text-right">
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
import {useAppStore} from "@/stores";
import {helpers, required} from '@vuelidate/validators'
import useVuelidate from "@vuelidate/core"
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const store = useAppStore();

const data = reactive({
    process: false,
    add_task: false,
    add_status: false,
    task_list: [],
    status:[],
    task:[],
    task_bk: [],
    user:[],
    role:[],
    project:[],
    positions: ['before','after'],
    form_add:{
        title: '',
        content: '',
        project: '',
        assign: [],
        date_start: new Date(),
        date_end: new Date(),
    },
    form_add_rules:{
        title: { required },
        content: { required },
        project: { required },
        date_start: { required },
        date_end: { required, v:  helpers.withMessage(
            () => 'End date has to more than start date',
            value => {
                return value >= data.form_add.date_start;
            }
        )},
    },
    form_status:{
        title: '',
        position: 'after',
        index: 1
    },
    form_status_rules:{
        title: { required },
    },
})
const vTask = useVuelidate(data.form_add_rules, data.form_add);
const vStatus = useVuelidate(data.form_status_rules, data.form_status);
const addStatus = async () => {
    if(await vStatus.value.$validate()){
        callApi('status', 'post', JSON.stringify(data.form_status)).then((response) =>{
            data.add_status = false;
            data.status = response.data;
            data.task_list = getTaskList();
        });
    }
}

const colorTaskDeadline = (dateStart, dateEnd)=>{
    let start = new Date(dateStart).getDate();
    let end = new Date(dateEnd).getDate();
    let waring = start + 1

    if(start >= end){
        return 'bg-red-100'
    }else if(waring >= end){
        return 'bg-yellow-100'
    }else{
        return 'bg-green-100'
    }
}
const addTask = async () => {
    data.form_add.assign = data.user.map(user => {
        if(user.active){
            return {
                user_id: user.id,
                role_id: user.role,
            }
        }
    }).filter(user => user !== undefined);
    if(await vTask.value.$validate()){
        callApi('task', 'post', JSON.stringify(data.form_add)).then((response) =>{
            data.add_task = false;
            data.task.push(response.data);
            data.task_list = getTaskList();
        });
    }
}

const getTaskList = () => {
    if(data.task.length > 0){
        data.status.sort((a, b) => a.index - b.index);
        data.task.sort((a, b) => a.index - b.index);
        return data.status.map(item => {
            item.task = data.task.filter(task => task.status_id === item.id);
            item.task.menu = false;
            item.task.edit = false;
            return item;
        })
    }
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
    }
    updateScreen(listTaskUpdate, taskIdMove, statusIdAppend);

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

const moveTask = (id, data)=>{
    return callApi('task-move/'+id,'PUT', JSON.stringify(data));
}
const deleteTask = (task)=>{
    data.process = true;
    callApi('task/'+task.id,'DELETE').then((response)=>{
        if(response.status === 1){
            const index = data.task.findIndex((item => item.id === task.id))
            delete(data.task[index]);
            data.process = false;
            data.task_list = getTaskList();
        }
    });
}

VueDragulaGlobal.options('bag-one', {
    direction: 'horizontal',
    copy: false,
});

VueDragulaGlobal.eventBus.on('drop', (args) => {
    data.process = true;
    const taskIdMove = getIdAgr(args[1], 'tid');
    const statusIdAppend = getIdAgr(args[2], 'sid');
    const taskIdOver = getIdAgr(args[4], 'tid');
    const listTaskIndex = getTasksIndex(statusIdAppend, taskIdMove, taskIdOver);
    moveTask(taskIdMove, {
        status_id: statusIdAppend,
        list_index: listTaskIndex,
    }).then((response)=>{
        if(response.status === 1){
            data.process = false;
        }
    });
});

onMounted(async () => {
    let auth = JSON.parse(localStorage.getItem('auth'));
    let status = await callApi('status', 'get');
    let task = await callApi('task', 'get');
    let user = await callApi('user', 'get');
    let role = await callApi('role', 'get');
    let project = await callApi('project', 'get');

    data.status = status.data;
    data.task = task.data;
    data.role = role.data;
    data.project = project.data
    data.user = user.data.filter(user => user.id !== auth?.id)
    data.user.map((user) => {
        user.active = false;
        user.role = 1;

        return user;
    })

    data.task_list = getTaskList();
})
</script>
