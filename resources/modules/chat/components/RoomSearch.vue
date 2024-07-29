<template>
    <span class="search-box flex-grow-1">
        <input type="text"
               class="form-control bg-light border-light"
               placeholder="Search here..."
               :value="$props.modelValue"
               @input="$emit('update:modelValue', $event.target.value)"
        >
        <i class="ri-search-2-line search-icon"></i>
    </span>
</template>
<script setup>
   const props = defineProps(['modelValue'])
</script>