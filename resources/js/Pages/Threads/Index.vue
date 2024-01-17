<script setup lang="ts">
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Thread from "@/Pages/Threads/Partials/Thread.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps<{
  threads: object;
}>();
</script>

<template>
  <Head title="Profile" />

  <GuestLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Threads
      </h2>
    </template>

    <div class="py-6 w-full" v-for="thread in props.threads">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
          <thread :thread="thread" />
        </div>
      </div>
    </div>
  </GuestLayout>
</template>

<!--
    @empty
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    {{ __('There are no threads in this channel') }}
                </div>
            </div>
        </div>
    @endforelse -->
