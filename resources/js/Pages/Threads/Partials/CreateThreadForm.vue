<script setup lang="ts">
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { reactive } from "vue";
import { Link, useForm, usePage, router } from "@inertiajs/vue3";

defineProps({ errors: Object });

const form = reactive({
  title: null,
  body: null,
  channel: null,
});

function submit() {
  router.post("/threads", form);
}

const user = usePage().props.auth.user;

console.log(usePage().props.auth);
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        Create your new thread here
      </h2>
    </header>

    <form @submit.prevent="submit" class="mt-6 space-y-6">
      <div>
        <InputLabel for="channel_id" value="Choose a channel" />
        <select
          name="channel_id"
          id="channel_id"
          class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full"
        >
          <option value="">Choose one</option>

          <option v-for="channel in $page.props.channels" :value="channel.id">
            {{ channel.name }}
          </option>
        </select>
        <InputError class="mt-2" :message="$page.props.errors.channel" />
      </div>

      <div>
        <InputLabel for="title" value="title" />

        <TextInput
          id="title"
          type="text"
          class="mt-1 block w-full"
          v-model="form.title"
          autofocus
          autocomplete="title"
        />

        <InputError class="mt-2" :message="$page.props.errors.title" />
      </div>

      <div>
        <InputLabel for="body" value="body" />

        <TextInput
          id="body"
          type="body"
          class="mt-1 block w-full"
          v-model="form.body"
          autocomplete="body"
        />

        <InputError class="mt-2" :message="$page.props.errors.body" />
      </div>

      <div class="flex items-center gap-4">
        <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

        <Transition
          enter-active-class="transition ease-in-out"
          enter-from-class="opacity-0"
          leave-active-class="transition ease-in-out"
          leave-to-class="opacity-0"
        >
          <p
            v-if="form.recentlySuccessful"
            class="text-sm text-gray-600 dark:text-gray-400"
          >
            Saved.
          </p>
        </Transition>
      </div>
    </form>
  </section>
</template>
