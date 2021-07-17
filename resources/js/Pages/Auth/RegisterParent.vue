<!--suppress HtmlUnknownTag -->
<template>
    <inertia-head title="Complete Registration"/>
    <breeze-validation-errors class="mb-4"/>

    You are below 13 years old.
    <br/>
    Please use the mobile app to register your parents.
    <br/>
    <br/>
    <!--suppress JSUnresolvedFunction -->
    <inertia-link :href="route('logout')" method="post" class="underline text-sm text-gray-600 hover:text-gray-900">
        {{ lang.get("auth.logout") }}
    </inertia-link>
</template>

<script>
import BreezeGuestLayout from "@/Layouts/Guest";

export default {
    layout: BreezeGuestLayout,

    components: {},

    props: {
        auth: Object,
        localities: Object,
        errors: Object,
    },

    data() {
        return {};
    },

    methods: {},
};
</script>
