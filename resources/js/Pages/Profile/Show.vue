<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import created_thread from "@/Pages/Profile/Partials/Activities/created_thread.vue";
import created_reply from "@/Pages/Profile/Partials/Activities/created_reply.vue";
import { Head } from "@inertiajs/vue3";

const components = {
  created_thread,
  created_reply,
};

const props = defineProps<{
  profileUser: object;
  activities: object;
}>();
</script>

<template>
  <Head title="Profile" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ $page.props.auth.user.name }} profile
      </h2>
    </template>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="py-12" v-for="(items, date) in activities">
          <h2
            class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
          >
            {{ date }}
          </h2>

          <div class="py-12" v-for="activity in items">
            <component
              :is="components[activity.type]"
              :profileUser="props.profileUser"
              :activity="activity"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
