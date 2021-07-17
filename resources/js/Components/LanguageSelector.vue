<template>
    <div class="ml-4">
        <!--suppress JSUnresolvedFunction -->
        <a :href="route('language', [selectable_locale])">
            {{ selectable_locale }}
        </a>
    </div>
</template>

<script>
export default {
    computed: {
        selectable_locale() {
            if (this.$page.props.locale === "tr") {
                return "en";
            }
            return "tr";
        },
    },
};
</script>
