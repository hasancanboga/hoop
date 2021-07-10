<template>
  <inertia-head title="Confirm Login" />
  <div class="mb-4 text-sm text-gray-600">{{ lang.get('auth.otp_prompt') }}</div>

  <breeze-validation-errors class="mb-4" />

  <form @submit.prevent="submit">
    <div>
      <breeze-label for="otp" :value="lang.get('misc.password')" />
      <breeze-input id="otp" type="text" class="mt-1 block w-full" v-model="form.otp" required autocomplete="current-otp" autofocus />
    </div>

    <div class="block mt-4">
      <label class="flex items-center">
        <breeze-checkbox name="remember" v-model:checked="form.remember" />
        <span class="ml-2 text-sm text-gray-600">{{ lang.get('misc.remember_me') }}</span>
      </label>
    </div>

    <div class="flex justify-end mt-4">
      <breeze-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"> {{ lang.get('misc.confirm') }} </breeze-button>
    </div>
  </form>
</template>

<script>
import BreezeButton from "@/Components/Button";
import BreezeGuestLayout from "@/Layouts/Guest";
import BreezeInput from "@/Components/Input";
import BreezeLabel from "@/Components/Label";
import BreezeCheckbox from "@/Components/Checkbox";
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
    temp_user: Object,
    auth: Object,
    errors: Object,
  },

  data() {
    return {
      lang: Lang,
      form: this.$inertia.form({
        phone: this.temp_user?.phone,
        otp: "",
        remember: false,
      }),
    };
  },

  methods: {
    submit() {
      this.form.post(this.route("otp.confirm"), {
        onFinish: () => this.form.reset(),
      });
    },
  },
};
</script>
