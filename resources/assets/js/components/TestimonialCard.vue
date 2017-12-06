<template>
    <section class="card">
        <div class="card-title">
            <div class="text-black font-bold text-xl mb-2">{{ testimonial.title }}</div>
        </div>
        <div class="card-body">
            <p class="text-grey-darker text-base">{{ testimonial.body }}</p>
            <img class="w-10 h-10 rounded-full mr-4" v-lazy="testimonial.author.photo" :alt="avatarDescription">
        </div>
        <div class="card-footer">
            <p>{{ testimonial.author.name }} - {{ testimonial.created_at | date }}</p>
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
