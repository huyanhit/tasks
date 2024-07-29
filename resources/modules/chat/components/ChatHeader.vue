<template>
    <div class="px-3 py-2 user-chat-topbar">
        <div class="row align-items-center">
            <div class="col-sm-4 col-8">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 d-block d-lg-none me-3">
                        <a href="javascript: void(0);" class="user-chat-remove fs-18 p-1"><i
                                class="ri-arrow-left-s-line align-bottom"></i></a>
                    </div>
                    <div class="flex-grow-1 overflow-hidden" v-if="store.room">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 chat-user-img online user-own-img align-self-center me-2 ms-0">
                                <div class="avatar-sm">
                                    <ImageFile v-if="store.room.icon" :img_id="store.room.icon" class="rounded-circle img-fluid userprofile"/>
                                    <img v-else class="rounded-circle img-fluid userprofile"
                                         :src="'https://ui-avatars.com/api/?name='+store.room.name"/>
                                </div>
                                <span v-if="store.room.type === 3" class="user-status"></span>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="text-truncate mb-0 fs-20">
                                    <a class="text-reset username" >{{ store.room?.name }}</a>
                                </h5>
                                <p class="text-truncate text-muted fs-14 mb-0 userStatus" v-b-modal.update_member_modal
                                    v-if="store.chat.ROOM_MEMBER[store.chat.CURRENT_ROOM.id]">
                                    {{ store.chat.ROOM_MEMBER[store.chat.CURRENT_ROOM.id].length }} Thành viên
                                </p>
                                <UpdateMember/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8 col-4">
                <ul class="list-inline user-chat-nav text-end mb-0">
                    <li class="list-inline-item m-0">
                        <div class="d-flex">
                            <div class="py-2 me-2">
                                <div class="search-box">
                                    <input type="text" class="form-control bg-light border-light"
                                           placeholder="Search here..."  id="searchMessage">
                                    <i class="ri-search-2-line search-icon"></i>
                                </div>
                            </div>
                            <div class="py-2">
                                <button class="btn btn-ghost-success btn-icon" type="button" >
                                    <i class="ri ri-folder-6-line"></i>
                                </button>
                            </div>
                            <div class="py-2">
                                <button class="btn btn-ghost-success btn-icon" type="button" >
                                    <i class="ri ri-task-line"></i>
                                </button>
                            </div>
                            <div class="py-2">
                                <button class="btn btn-ghost-success btn-icon" type="button" >
                                    <i class="ri ri-information-line"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                    <li class="list-inline-item m-0">
                        <div class="dropdown">
                            <button class="btn btn-ghost-success btn-icon" type="button" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="ri ri-menu-2-line"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item d-block d-lg-none user-profile-show" href="#"><i
                                        class="ri-user-2-fill align-bottom text-muted me-2"></i> View Profile</a>
                                <a class="dropdown-item" href="#"><i
                                        class="ri-inbox-archive-line align-bottom text-muted me-2"></i> Archive</a>
                                <a class="dropdown-item" href="#"><i
                                        class="ri-mic-off-line align-bottom text-muted me-2"></i> Muted</a>
                                <a class="dropdown-item" href="#"><i
                                        class="ri-delete-bin-5-line align-bottom text-muted me-2"></i> Delete</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script setup>
import { useSocketStore } from '@/modules/chat/stores/chat.js'
import ImageFile from '@/modules/chat/components/file-partials/ImageFile.vue'
import UpdateMember from '@/modules/chat/components/UpdateMember.vue'

const store = useSocketStore();

</script>
<style scoped></style>