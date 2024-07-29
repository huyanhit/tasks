<template>
    <div class="sidebar-right p-0 bg-white " v-if="store.room" >
        <template v-if="store.room.type === 3">
            <div class="p-3 text-center"
                 style="background: url('https://themesbrand.com/velzon/html/default/assets/images/small/img-9.jpg')">
                <img src="https://themesbrand.com/velzon/html/default/assets/images/users/multi-user.jpg" alt=""
                     class="avatar-lg img-thumbnail mx-auto profile-img">
                <div class="mt-3">
                    <h5 class="fs-16 mb-1"><a href="javascript:void(0);" class="link-primary username">{{
                            store.room?.name }}</a></h5>
                    <p class="text-muted"><i
                        class="ri-checkbox-blank-circle-fill me-1 align-bottom text-success"></i>Online</p>
                </div>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn avatar-xs p-0" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Message">
                        <span class="avatar-title rounded bg-light text-body">
                            <i class="ri-question-answer-line"></i>
                        </span>
                    </button>

                    <button type="button" class="btn avatar-xs p-0" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Favourite">
                        <span class="avatar-title rounded bg-light text-body">
                            <i class="ri-star-line"></i>
                        </span>
                    </button>

                    <button type="button" class="btn avatar-xs p-0" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Phone">
                        <span class="avatar-title rounded bg-light text-body">
                            <i class="ri-phone-line"></i>
                        </span>
                    </button>

                    <div class="dropdown">
                        <button class="btn avatar-xs p-0" type="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            <span class="avatar-title bg-light text-body rounded">
                                <i class="ri-more-fill"></i>
                            </span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:void(0);"><i
                                class="ri-inbox-archive-line align-bottom text-muted me-2"></i>Archive</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);"><i
                                class="ri-mic-off-line align-bottom text-muted me-2"></i>Muted</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);"><i
                                class="ri-delete-bin-5-line align-bottom text-muted me-2"></i>Delete</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="border-top border-top-dashed p-3">
                <h5 class="fs-15 mb-3 fw-bold">Personal Details</h5>
                <div class="mb-3">
                    <p class="text-muted text-uppercase fw-medium fs-12 mb-1">Number</p>
                    <h6>+(256) 2451 8974</h6>
                </div>
                <div class="mb-3">
                    <p class="text-muted text-uppercase fw-medium fs-12 mb-1">Email</p>
                    <h6>lisaparker@gmail.com</h6>
                </div>
                <div>
                    <p class="text-muted text-uppercase fw-medium fs-12 mb-1">Location</p>
                    <h6 class="mb-0">California, USA</h6>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="p-3">
                <h5 class="fs-15 mb-3 fw-bold">Thành viên
                    <a class="float-end cursor-pointer" v-b-modal.update_member_modal>
                        <i class="ri-pencil-fill align-bottom me-2 text-muted small"></i>
                    </a>
                </h5>
                <div class="d-flex flex-wrap" style="max-height: 220px; overflow-y: scroll">
                    <template v-for="val in store.chat.ROOM_MEMBER[store.chat.CURRENT_ROOM.id]">
                        <div class="flex-grow-0 avatar-sm m-1"
                             :class="{'border border-success': val === store.chat.CURRENT_USER.id}"
                             v-if="store.chat.USER[val]" >
                            <img :src="store.chat.USER[val].avatar? store.chat.USER[val].avatar:
                                                 'https://ui-avatars.com/api/?name='+store.chat.USER[val].name"
                                 :alt="store.chat.USER[val].name"
                                 class="img-fluid">
                        </div>
                    </template>
                    <template v-for="val in store.chat.ROOM_MEMBER[store.chat.CURRENT_ROOM.id]">
                        <div class="flex-grow-0 chat-avatar avatar-sm m-1" v-if="!store.chat.USER[val]">
                            <img :src="'https://ui-avatars.com/api/?name=GUEST'" alt="GUEST"  class="img-fluid">
                        </div>
                    </template>
                </div>
            </div>
        </template>
        <div class="border-top border-top-dashed p-3">
            <h5 class="fs-15 mb-3 fw-bold">Mô tả
                <a class="float-end cursor-pointer" v-b-modal.update_room_info>
                    <i class="ri-pencil-fill align-bottom me-2 text-muted small"></i></a>
            </h5>
            <div class="vstack gap-2">
                <div class="border rounded border-dashed p-2">
                    {{ store.room.description }}
                </div>
            </div>
            <RoomUpdate/>
        </div>
        <div class="border-top border-top-dashed p-3">
            <h5 class="fs-15 mb-3 fw-bold">Attached Files</h5>
            <div class="b-overlay-wrap position-relative"
                 style="max-height: 300px; overflow-y: auto"
                 v-if="store.room_files && store.room_files.length > 0">
                <div class="vstack gap-2" v-for="item in store.room_files">
                    <div class="border rounded border-dashed p-2">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-xs">
                                    <div class="avatar-xs me-2" v-if="item.type === 'image'">
                                        <ImageFile :img_id="item.id"> </ImageFile>
                                    </div>
                                    <div v-else class="avatar-title bg-light text-secondary rounded fs-20">
                                        <i class="ri-folder-zip-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="fs-13 mb-1"><a href="#" class="text-body text-truncate d-block">{{item.name}}.{{item.ext}}</a></h5>
                                <div class="text-muted"><file-size :item="item"/></div>
                            </div>
                            <div class="flex-shrink-0 ms-2">
                                <div class="d-flex gap-1">
                                    <div class="dropdown">
                                        <button class="btn btn-icon text-muted btn-sm fs-18 dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i
                                                class="ri-share-line align-bottom me-2 text-muted"></i>
                                                Share</a></li>
                                            <li><a class="dropdown-item" href="#"><i
                                                class="ri-bookmark-line align-bottom me-2 text-muted"></i>
                                                Bookmark</a></li>
                                            <li><a class="dropdown-item" href="#"><i
                                                class="ri-delete-bin-line align-bottom me-2 text-muted"></i>
                                                Delete</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="text-center border rounded border-dashed p-2">
                <i class="ri-delete-bin-7-line fs-24 text-muted"></i>
                <div class="text-center pb-3 mt-2"> Không có file nào </div>
            </div>
            <div class="text-center mt-1">
                <a class="btn-sm btn btn-outline-success"> Load more <i class="ri-arrow-right-fill align-bottom ms-1"></i> </a>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useSocketStore } from '@/modules/chat/stores/chat.js'
import ImageFile from '@/modules/chat/components/file-partials/ImageFile.vue'
import FileSize from '@/modules/chat/components/file-partials/FileSize.vue'
import RoomUpdate from '@/modules/chat/components/RoomUpdate.vue'
const store = useSocketStore();
</script>

<style>
.sidebar-right{
    width: 480px;
}
</style>