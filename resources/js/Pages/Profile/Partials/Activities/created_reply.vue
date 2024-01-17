<script setup lang="ts">
import Activity from "@/Pages/Profile/Partials/Activities/Activity.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps<{
  profileUser: object;
  activity: object;
}>();

const thread = props.activity.subject.thread;
const reply = props.activity.subject;
</script>

<template>
  <Activity>
    <template #heading>
      {{ props.profileUser.name }}
      replied to
      {{ console.log(route("dashboard")) }}
      <Link
        :href="
          route('threads.show', {
            channel: thread.channel,
            thread: thread.id,
          })
        "
      >
        {{ thread.title }}
      </Link>
    </template>

    <template #body>
      {{reply.body }}
    </template>
  </Activity>
</template>
