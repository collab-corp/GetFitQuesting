<template>
    <modal name="image-modal" transition="scale" @before-open="setImagePath">
        <img v-lazy="path" class="shadow-inner"/>
    </modal>
</template>

<script>
    export default {
        data() {
            return {
                path: ''
            }
        },

        methods: {
            setImagePath(event) {
                this.path = event.params.path;
            }
        }
    }
</script>