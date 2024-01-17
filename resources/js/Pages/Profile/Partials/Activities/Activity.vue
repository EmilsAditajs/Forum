<script setup lang="ts">

</script>

<template>
    <div class="flex flex-row">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            <slot name="heading" />
        </h2>
    </div>

    <div>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            <slot name="body" />
        </p>
    </div>
</template>
