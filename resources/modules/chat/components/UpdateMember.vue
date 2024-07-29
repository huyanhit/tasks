<template>
    <BModal @shown="loadData" @close="resetModal" id="update_member_modal" title="Cập nhật thành viên" scrollable size="lg" ref="modal">
        <div class="d-flex flex-wrap" style="max-height: 220px; overflow-y: scroll">
            <template v-for="val in store.chat.ROOM_MEMBER[store.chat.CURRENT_ROOM.id]">
                <div class="d-flex p-2 border border-groove me-2 my-1 rounded"
                    :class="{'bg-light border-success': val === store.chat.CURRENT_USER.id}" v-if="store.chat.USER[val]">
                    <div class="flex-grow-0 avatar-sm me-2">
                        <img :src="store.chat.USER[val].avatar? store.chat.USER[val].avatar:
                             'https://ui-avatars.com/api/?name='+store.chat.USER[val].name"
                             :alt="store.chat.USER[val].name"
                             class="rounded-circle img-fluid">
                    </div>
                    <div class="flex-fill me-2">
                        <div class="me-2 fw-bold text-truncate"> {{store.chat.USER[val].name}}</div>
                        <div class="badge border border-success text-success">{{ getRoleMember(val) }}</div>
                    </div>
                </div>
                <div v-else class="d-flex p-2 border border-groove me-2 my-1 rounded">
                    <div class="flex-grow-0 chat-avatar avatar-sm me-2">
                        <img :src="'https://ui-avatars.com/api/?name=GUEST'" alt="GUEST"  class="rounded-circle img-fluid">
                    </div>
                    <div class="flex-fill mt-3">
                        Khách
                    </div>
                </div>
            </template>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="form-group">
                    <div class="d-flex my-2">
                         <div class="bg-light me-2 p-2 border border-groove rounded">
                            <b-form-checkbox v-model="data.check_all" v-on:change="checkAllMember()" id="check_all_members">
                                Check All
                            </b-form-checkbox>
                         </div>
                         <div class="flex-fill bg-light border border-groove rounded">
                            <input type="text"
                               class="form-control bg-light border-light"
                               placeholder="Search here..."
                               v-model="data.keyword"
                            >
                        </div>
                    </div>
                    <div class="list-contact py-1 px-3 border border-groove rounded" style="max-height: 400px; overflow-y: auto">
                        <template v-for="(item, index) in filterUser" :key="index">
                            <div class="row border-bottom-inset border-1" v-if="item.id !== store.chat.CURRENT_USER.id">
                                <div class="col-md-6 mb-2">
                                    <div class="my-3 pt-1">
                                        <b-form-checkbox v-model="item.check" :id="'check-item-members-'+item.id">
                                            {{item.name}}
                                        </b-form-checkbox>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex my-2" v-if="item.check">
                                    <div class="flex-grow-1 my-1 me-2">
                                        <Field :name="'member_roles'+item.id" v-model="item.role">
                                            <Multiselect
                                                v-model="item.role" value="1" :options="roles">
                                                <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                                    <div class="multiselect-tag is-user" :class="{'is-disabled': disabled}">
                                                        <span>{{ option.label }}</span>
                                                        <span
                                                            v-if="!disabled"
                                                            class="multiselect-tag-remove"
                                                            @click="handleTagRemove(option, $event)">
                                                            <span class="multiselect-tag-remove-icon"></span>
                                                        </span>
                                                    </div>
                                                </template>
                                            </Multiselect>
                                        </Field>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <template #footer>
            <b-button variant="secondary" class="w-sm btn-save-modal" :disabled="data.saving" @click="updateMembers">
                <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true" v-show="data.saving"></span>
                {{ $t('common.form.save') }}
            </b-button>
            <b-button variant="outline-secondary" @click="modal.hide()">
                {{ $t('common.form.cancel') }}
            </b-button>
        </template>
    </BModal>
</template>
<script setup>

import { useSocketStore } from '@/modules/chat/stores/chat.js'
import { Field } from 'vee-validate'
import Helper, { showConfirm, showToast } from '@/helpers/common.js'
import i18n from '@/plugins/i18n'

const store = useSocketStore();
const event = Helper.useEvent();
const modal = ref(null);
const data = reactive({
    check_all: false,
    keyword: '',
    filterUser: []
})

const roles = [
    { value: "1", label: 'Member'},
    { value: "2", label: 'Admin'},
    { value: "3", label: 'Read Only'},
]
const getRoleMember = function(val){
    const type = store.chat.MEMBER[store.chat.CURRENT_ROOM.id+'_'+val].type;
    return (type === 1)? "Member": ( type === 2 ) ? "Admin" : "Read only";
}
const removeItem = function(index){
    filterUser.value[index].check = false;
}
const checkAllMember = function(){
    for (let item of filterUser.value){
        item.check = data.check_all;
    }
}

const checkMember = function(id){
    for (const index in store.chat.ROOM_MEMBER[store.chat.CURRENT_ROOM.id]) {
        if(store.chat.ROOM_MEMBER[store.chat.CURRENT_ROOM.id][index] === id){
            return true;
        }
    }

    return false;
}
const resetModal = function(){
    data.filterUser = []
    data.check_all = false
    data.keyword = ''
}

const loadData = function(){
    store.users.forEach(item => {
        data.filterUser[item.id] = item;
        if (checkMember(item.id)){
            data.filterUser[item.id].check = true
            data.filterUser[item.id].role  =
                store.chat.MEMBER[store.chat.CURRENT_ROOM.id + '_' +data.filterUser[item.id].id].type
        }else{
            data.filterUser[item.id].check = false
            data.filterUser[item.id].role  = 1
        }
    })

    data.filterUser.sort((a, b) => {
        return b.check - a.check
    })
}

const filterUser = computed(()=>{
    return data.filterUser.filter(item => {
        return item.name.includes(data.keyword)
    })
})

const getFormMembers = function(){
    let members = {};
    for (let index in filterUser.value){
        if(filterUser.value[index].check){
            members[filterUser.value[index].id] = filterUser.value[index].role
        }
    }

    return members;
}
const updateMembers = async function() {
    showConfirm({title: 'are you sure save info ?'}).then( async (result) => {
        if(result.isConfirmed){
            data.saving = true
            const response = await store.updateRoom(store.chat.CURRENT_ROOM.id, { members: getFormMembers() })
            if (response.success) {
                showToast({ icon: "success", title: i18n.global.t('common.message.success') });
            }
            data.saving = false
        }
    })
}

</script>