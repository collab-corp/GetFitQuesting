<template>
    <article class="flex flex-col h-full container mx-auto news">
        <h1 class="capitalize text-center">Latest news</h1>
        <news-article v-for="article in news" :key="article.id" :article="article"></news-article>
    </article>
</template>

<script>
    import NewsArticle from 'resources/assets/js/components/NewsCard.vue';

    export default {
        components: {NewsArticle},

        props: { initial:Array },

        data() {
            return {
                news: this.initial
            }
        }
    }
</script>