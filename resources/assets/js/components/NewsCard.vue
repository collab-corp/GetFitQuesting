<template>
    <section class="flex-1 shadow-inner bg-brand-light hover\:bg-brand-lighter mb-2">
        <div class="float-left flex flex-wrap w-1/4 m-1 clearfix">
            <img
                    class="pb-1"
                    v-for="image in article.images"
                    v-lazy="image.thumb"
                    @click="$modal.show('image-modal', { path: image.path })"
            />
        </div>

        <div class="pl-4">
            <h3 class="capitalize">{{ article.title }}</h3>
            <truncate :text="article.content" clamp="..." :length="90" less="show less"></truncate>
        </div>
    </section>
</template>

<script>
    import truncate from 'vue-truncate-collapsed';

    export default {
        components: {truncate},

        props: { article:Object }
    }
</script>