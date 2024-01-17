<script setup lang="ts">
import Activity from "@/Pages/Profile/Partials/Activities/Activity.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps<{
  profileUser: object;
  activity: object;
}>();

const thread = props.activity.subject;
</script>

<template>
  <Activity>
    <template #heading>
      {{ props.profileUser.name }}
      published
      <Link :href="route('threads.show', {channel:thread.channel,thread:thread.id})">
        {{ props.activity.subject.title }}
      </Link>
    </template>

    <template #body>
      {{ thread.body }}
    </template>
  </Activity>
</template>
