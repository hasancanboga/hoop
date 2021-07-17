<template>
    <inertia-head title="Login"/>
    <breeze-validation-errors class="mb-4"/>

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
    </div>

    <form @submit.prevent="submit">
        <div class="flex flex-wrap">
            <div class="w-1/4 px-3 mb-6 md:mb-0">
                <breeze-label for="country" :value="lang.get('validation.attributes.country')"/>
                <breeze-input id="country" type="text" class="bg-gray-200 mt-1 block w-full" v-model="form.country"
                              required autocomplete="country" placeholder="+90" disabled/>
            </div>
            <div class="w-3/4 px-3">
                <breeze-label for="phone" :value="lang.get('validation.attributes.phone')"/>
                <breeze-input id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" required autofocus
                              autocomplete="phone" placeholder="5XXXXXXXXX"/>
            </div>
        </div>

        <div class="flex items-center justify-between mt-4">
            <LanguageSelector/>
            <breeze-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ lang.get('auth.login') }}
            </breeze-button>
        </div>
    </form>
</template>

<script>
import BreezeButton from "@/Components/Button";
import BreezeGuestLayout from "@/Layouts/Guest";
import BreezeInput from "@/Components/Input";
import BreezeLabel from "@/Components/Label";
import BreezeValidationErrors from "@/Components/ValidationErrors";
import LanguageSelector from "@/Components/LanguageSelector";

export default {
    layout: BreezeGuestLayout,

    components: {
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        LanguageSelector,
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
                phone_country: "TR",
            }),
        };
    },

    methods: {
        submit() {
            this.form.post(this.route("login"), {
                onFinish: () => {
                },
            });
        },
    },
};
</script>
