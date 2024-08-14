<template>
    <span class="small"> {{size}} </span>
</template>
<script setup>
const size = ref('')
const props = defineProps(['item'])
onMounted(async () => {
    size.value = ((await fetch(props.item.blob).then(r => r.blob())).size  / 1024).toFixed(2) + " KB";
})

</script>