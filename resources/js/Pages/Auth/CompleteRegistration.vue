<template>
  <breeze-validation-errors class="mb-4" />

  <form @submit.prevent="submit">
    <div>
      <breeze-label for="first_name" value="First Name" />
      <breeze-input id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" required autofocus autocomplete="first_name" />
    </div>

    <div class="mt-4">
      <breeze-label for="last_name" value="Last Name" />
      <breeze-input id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" required autofocus autocomplete="last_name" />
    </div>

    <div class="mt-4">
      <breeze-label for="date_of_birth" value="Date of Birth" />
      <breeze-input id="date_of_birth" type="text" class="mt-1 block w-full" v-model="form.date_of_birth" required autofocus autocomplete="date_of_birth" />
    </div>

    <div class="mt-4">
      <breeze-label for="gender" value="Gender" />
      <breeze-input id="gender" type="text" class="mt-1 block w-full" v-model="form.gender" required autofocus autocomplete="gender" />
    </div>

    <div class="mt-4">
      <breeze-label for="city" value="City" />
      <breeze-input id="city" type="text" class="mt-1 block w-full" v-model="form.city"  autofocus autocomplete="city" />
    </div>

    <div class="mt-4">
      <breeze-label for="email" value="Email" />
      <breeze-input id="email" type="email" class="mt-1 block w-full" v-model="form.email"  autocomplete="username" />
    </div>

    <div class="flex items-center justify-between mt-4">
      <inertia-link :href="route('logout')" method="post" class="underline text-sm text-gray-600 hover:text-gray-900">Logout</inertia-link>

      <breeze-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"> Complete Registration </breeze-button>
    </div>
  </form>
</template>

<script>
import BreezeButton from "@/Components/Button";
import BreezeGuestLayout from "@/Layouts/Guest";
import BreezeInput from "@/Components/Input";
import BreezeLabel from "@/Components/Label";
import BreezeValidationErrors from "@/Components/ValidationErrors";

export default {
  layout: BreezeGuestLayout,

  components: {
    BreezeButton,
    BreezeInput,
    BreezeLabel,
    BreezeValidationErrors,
  },

  props: {
    auth: Object,
    errors: Object,
  },

  data() {
    return {
      form: this.$inertia.form({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        terms: false,
      }),
    };
  },

  methods: {
    submit() {
      this.form.post(this.route("register.complete"), {
        onFinish: () => this.form.reset("password", "password_confirmation"),
      });
    },
  },
};
</script>
