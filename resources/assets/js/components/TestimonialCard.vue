<template>
    <section class="border-r border-b border-l border-grey-light lg:border-l-0 lg:border-t lg:border-grey-light bg-white rounded-b lg:rounded-b-none lg:rounded-r p-4 justify-between leading-normal w-full testimonial">
        <div class="mb-8">
            <div class="text-black font-bold text-xl mb-2">{{ testimonial.title }}</div>
            <p class="text-grey-darker text-base">{{ testimonial.body }}</p>
        </div>
        <div class="flex items-center">
            <img class="w-10 h-10 rounded-full mr-4" v-lazy="testimonial.author.photo" :alt="avatarDescription">
            <div class="text-sm">
                <p class="text-black leading-none capitalize">{{ testimonial.author.name }}</p>
                <p class="text-grey-dark">{{ testimonial.created_at | date }}</p>
            </div>
        </div>
    </section>
</template>

<script>
export default {
  props: { testimonial: Object },

  computed: {
    avatarDescription: function() {
      return `Avatar of ${this.testimonial.author.name}`;
    }
  }
};
</script>