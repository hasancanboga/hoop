<template>
    <breeze-authenticated-layout>
        <!--suppress HtmlUnknownTag -->
        <inertia-head title="Dashboard"/>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ lang.get("misc.dashboard") }}</h2>
                <LanguageSelector/>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">{{ lang.get("misc.welcome") }}!</div>
                </div>
            </div>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated";
import LanguageSelector from "@/Components/LanguageSelector";

export default {
    components: {
        BreezeAuthenticatedLayout,
        LanguageSelector,
    },
    props: {
        auth: Object,
        errors: Object,
    },
};
</script>
