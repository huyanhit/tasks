<template>
    <div class="file-message file-upload" @click="downFile">
        <i class="ri-folder-zip-line"></i>
    </div>
</template>
<script setup>
const downFile = function() {
    alert("tính năng đang thực hiện")
}
</script>
<style scoped>
.file-upload{
    font-size: 40px;
}
</style>