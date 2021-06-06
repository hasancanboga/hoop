<template>
  <breeze-validation-errors class="mb-4" />

  <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
    {{ status }}
  </div>

  <form @submit.prevent="submit">
    <div class="flex flex-wrap">
      <div class="w-1/4 px-3 mb-6 md:mb-0">
        <breeze-label for="country" value="Country " />
        <breeze-input id="country" type="text" class="bg-gray-200 mt-1 block w-full" v-model="form.country" required autocomplete="country" placeholder="+90" disabled />
      </div>
      <div class="w-3/4 px-3">
        <breeze-label for="phone" value="Phone" />
        <breeze-input id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" required autofocus autocomplete="phone" placeholder="5XXXXXXXXX" />
      </div>
    </div>

    <div class="block mt-4">
      <label class="flex items-center">
        <breeze-checkbox name="remember" v-model:checked="form.remember" />
        <span class="ml-2 text-sm text-gray-600">Remember me</span>
      </label>
    </div>

    <div class="flex items-center justify-end mt-4">
      <breeze-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"> Log in </breeze-button>
    </div>
  </form>
</template>

<script>
import BreezeButton from "@/Components/Button";
import BreezeGuestLayout from "@/Layouts/Guest";
import BreezeInput from "@/Components/Input";
import BreezeCheckbox from "@/Components/Checkbox";
import BreezeLabel from "@/Components/Label";
import BreezeValidationErrors from "@/Components/ValidationErrors";

export default {
  layout: BreezeGuestLayout,

  components: {
    BreezeButton,
    BreezeInput,
    BreezeCheckbox,
    BreezeLabel,
    BreezeValidationErrors,
  },

  props: {
    auth: Object,
    errors: Object,
    status: String,
  },

  data() {
    return {
      form: this.$inertia.form({
        phone: "",
        remember: false,
      }),
    };
  },

  methods: {
    submit() {
      this.form.post(this.route("login"), {
        onFinish: () => {},
      });
    },
  },
};
</script>
