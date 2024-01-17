<script setup lang="ts">
import { useForm, Link } from "@inertiajs/vue3";
import { plural } from "@/functions.js";

const props = defineProps<{
  thread: object;
}>();
</script>

<template>
  <section>
    <header class="flex flex-row">
      <Link
        :href="route('threads.show', { channel: thread.channel, thread: thread.id })"
        class="text-lg font-medium text-gray-900 dark:text-gray-100"
      >
        <h2>
          {{ thread.title }}
        </h2>
      </Link>

      <div class="ml-auto">
        <!-- @can('update', $thread)
      <form
        method="POST"
        action="{{ route('threads.delete', ['channel' => $thread->channel, 'thread' => $thread->id]) }}"
        class="ml-auto"
      >
        @csrf @method('delete')

        <x-danger-button>{{ __("Delete thread") }}</x-danger-button>
      </form>
      @endcan -->

        <Link
          :href="route('threads.show', { channel: thread.channel, thread: thread.id })"
        >
          <strong>
            {{ thread.replies_count }}
            {{ plural("reply", "replies", thread.replies_count) }}
          </strong>
        </Link>
      </div>
    </header>

    <div>
      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ thread.body }}
      </p>
    </div>
  </section>
</template>
