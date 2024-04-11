<template #content="{ close }">
    <form class="max-w-sm mx-auto bg-gray-50 px-10 py-3 border-2 min-w-[1000px] relative" >
        <div v-show="data.process_update">
            <div role="status" class="absolute left-0 right-0 bottom-0 top-0 bg-opacity-50 bg-gray-400 z-50 text-center align-middle">
                <svg aria-hidden="true" class="absolute top-[50%] inline-block w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                </svg>
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="flex flex-row">
            <div class="basis-2/3 p-2">
                <div :class="{ error: vETask.project.$errors.length }" class="mt-2">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Project</label>
                    <select v-model="data.form_edit.project" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option v-for="(project, i) in data.project" :key="i" :value="project.id">{{project.title}}</option>
                    </select>
                    <div class="input-errors" v-for="error of vETask.project.$errors" :key="error.$uid">
                        <div class="error-msg text-red-500 text-xs">{{ error.$message }}</div>
                    </div>
                </div>
                <div :class="{ error: vETask.title.$errors.length }" class="mt-2">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                    <input type="text" v-model="data.form_edit.title" id="title"
                           class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <div class="input-errors" v-for="error of vETask.title.$errors" :key="error.$uid">
                        <div class="error-msg text-red-500 text-xs">{{ error.$message }}</div>
                    </div>
                </div>
                <div :class="{ error: vETask.content.$errors.length }" class="mt-2">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Content</label>
                    <textarea v-model="data.form_edit.content" id="title" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"/>
                    <div class="input-errors" v-for="error of vETask.content.$errors" :key="error.$uid">
                        <div class="error-msg text-red-500 text-xs">{{ error.$message }}</div>
                    </div>
                </div>
                <div  class="mt-2">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Comment</label>
                    <textarea v-model="data.form_edit.comment" id="title" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"/>
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
                <div :class="{ error: vETask.date_start.$errors.length }" class="mt-2">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date Start</label>
                    <vue-date-picker
                        v-model="data.form_edit.date_start" inline auto-apply timezone="UTC"
                        class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    />
                    <div class="input-errors" v-for="error of vETask.date_start.$errors" :key="error.$uid">
                        <div class="error-msg text-red-500 text-xs">{{ error.$message }}</div>
                    </div>
                </div>
                <div :class="{ error: vETask.date_end.$errors.length }" class="mt-2">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date End</label>
                    <vue-date-picker
                        v-model="data.form_edit.date_end" inline auto-apply timezone="UTC"
                        class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    />
                    <div class="input-errors" v-for="error of vETask.date_end.$errors" :key="error.$uid">
                        <div class="error-msg text-red-500 text-xs">{{ error.$message }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 text-center">
            <button @click.prevent="updateTask()" class="mr-2 bg-green-500 hover:bg-green-700 px-3 py-2 text-sm leading-5 font-semi text-white">
                Update Task
            </button>
        </div>
    </form>
</template>
<script setup>

import {computed, onMounted, reactive} from "vue";
import {callApi} from "@/composables/use-api";
import {useAppStore} from "@/stores";
import {helpers, required} from '@vuelidate/validators'
import useVuelidate from "@vuelidate/core"
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { useRoute } from 'vue-router'

const store = useAppStore();

const data = reactive({
    process_update: false,
    id:null,
    user:{},
    project:{},
    role:{},
    form_edit:{},
    form_edit_rules:{
        title: { required },
        content: { required },
        project: { required },
        date_start: { required },
        date_end: { required, v:  helpers.withMessage(
            () => 'End date has to more than start date',
            value => {
                return  new Date(value) >= new Date(data.form_edit.date_start);
            }
        )},
    }
})
const vETask = useVuelidate(data.form_edit_rules, computed(() => data.form_edit));
const updateTask = async () => {
    data.form_edit.assign = data.user.map(user => {
        if(user.active){
            return {
                user_id: user.id,
                role_id: user.role,
            }
        }
    }).filter(user => user !== undefined);
    if(await vETask.value.$validate()){
        data.process_update = true;
        callApi('task/'+ data.id, 'put', JSON.stringify(data.form_edit)).then((response) =>{
            if(response.status === 1){
                data.process_update = false;
                setFormEdit(response.data);
            }
        });
    }
}

const setFormEdit = (task) => {
    data.form_edit = {
        title: task.title,
        content: task.content,
        project: task.project_id,
        comment: task.comment,
        assign: data.user.map(item => {
            const index = task.members_info.findIndex(user => user.id === item.id);
            item.active = index !== -1
        }),
        date_start: task.date_start,
        date_end:  task.date_end,
    };
}

onMounted(async () => {
    const route = useRoute();
    let auth = JSON.parse(localStorage.getItem('auth'));
    let user = await callApi('user', 'get');
    let role = await callApi('role', 'get');
    let project = await callApi('project', 'get');
    let task = await callApi('task/'+route.params.id+'/edit', 'get');

    data.id = route.params.id;
    data.role = role.data;
    data.project = project.data
    data.user = user.data.filter(user => user.id !== auth?.id)
    data.user.map((user) => {
        user.active = false;
        user.role = 1;

        return user;
    })

    setFormEdit(task.data) ;
})
</script>
