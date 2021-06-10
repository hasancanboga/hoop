<template>
  <breeze-validation-errors class="mb-4" />

  <form @submit.prevent="submit">
    <div class="text-xl text-gray-600">Complete Registration</div>
    <!--  <div class="mt-4">
      <breeze-label for="username" value="Username" class="inline-block" />
      <ExclamationCircleIcon class="h-5 w-5 text-gray-500 inline mx-2" />
      <breeze-input id="username" type="text" class="mt-1 block w-full" v-model="form.username" required autofocus autocomplete="username" />
    </div> -->
    <div class="mt-4">
      <breeze-label for="first_name" value="First Name" class="inline-block" />
      <ExclamationCircleIcon class="h-5 w-5 text-gray-500 inline mx-2" />
      <breeze-input id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" required autocomplete="first_name" />
    </div>

    <div class="mt-4">
      <breeze-label for="last_name" value="Last Name" class="inline-block" />
      <ExclamationCircleIcon class="h-5 w-5 text-gray-500 inline mx-2" />
      <breeze-input id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" required autocomplete="last_name" />
    </div>

    <div class="mt-4">
      <breeze-label for="birth_year" value="Birth Year" class="inline-block" />
      <ExclamationCircleIcon class="h-5 w-5 text-gray-500 inline mx-2" />
      <breeze-input id="birth_year" type="text" class="mt-1 block w-full" v-model="form.birth_year" required autocomplete="birth_year" />
    </div>

    <div class="mt-4">
      <breeze-label for="gender" value="Gender" class="inline-block" />
      <ExclamationCircleIcon class="h-5 w-5 text-gray-500 inline mx-2" />
      <div class="mt-2">
        <label class="inline-flex items-center">
          <input type="radio" class="" name="gender" value="m" checked v-model="form.gender" />
          <span class="ml-2">Male</span>
        </label>
        <label class="inline-flex items-center ml-6">
          <input type="radio" class="" name="gender" value="f" v-model="form.gender" />
          <span class="ml-2">Female</span>
        </label>
      </div>
    </div>

    <div class="mt-4">
      <breeze-label for="email" value="Email" />
      <breeze-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" autocomplete="email" />
    </div>

    <div class="mt-4">
      <breeze-label for="locality" value="City" />
      <breeze-dropdown align="left" width="48">
        <template #trigger>
          <span class="inline-flex rounded-md">
            <button type="button" class="inline-flex items-center mt-2 px-3 py-2 border text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
              {{ this.selectedLocalityName ?? "Select a City" }}

              <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
          </span>
        </template>

        <template #content>
          <DropdownItem v-for="locality in localities" :key="locality.id" href="" @click="selectLocality(locality)" as="button"> {{ locality.name }} </DropdownItem>
        </template>
      </breeze-dropdown>

      <!-- <breeze-input id="locality" type="text" class="mt-1 block w-full" v-model="form.locality" autocomplete="locality" /> -->
    </div>

    <div class="flex items-center justify-between mt-4">
      <inertia-link :href="route('logout')" method="post" class="underline text-sm text-gray-600 hover:text-gray-900" as="button">Logout</inertia-link>
      <breeze-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"> Complete Registration </breeze-button>
    </div>
  </form>
</template>

<script>
import BreezeButton from "@/Components/Button";
import BreezeGuestLayout from "@/Layouts/Guest";
import BreezeInput from "@/Components/Input";
import BreezeLabel from "@/Components/Label";
import BreezeDropdown from "@/Components/Dropdown";
import DropdownItem from "@/Components/DropdownItem";
import BreezeValidationErrors from "@/Components/ValidationErrors";
import { ExclamationCircleIcon } from "@heroicons/vue/outline";

export default {
  layout: BreezeGuestLayout,

  components: {
    ExclamationCircleIcon,
    BreezeButton,
    BreezeInput,
    BreezeLabel,
    BreezeDropdown,
    DropdownItem,
    BreezeValidationErrors,
  },

  props: {
    auth: Object,
    localities: Object,
    errors: Object,
  },

  data() {
    return {
      selectedLocalityName: null,

      form: this.$inertia.form({
        // username: "",
        first_name: "",
        last_name: "",
        birth_year: "",
        gender: "m",
        email: "",
        locality: "",
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
    selectLocality(locality) {
      this.form.locality = locality.code;
      this.selectedLocalityName = locality.name;
    },
  },
};
</script>
