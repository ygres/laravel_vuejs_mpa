<template>
    <div></div>
</template>

<script>
    export default {
        data: function(){
            return {

            }
        },
        mounted(){
            this.$nextTick(function () {
                const images = document.querySelectorAll('img');
                if(images.length > 0) console.log(`Found ${images.length} images`)
            });
        }

    }
</script>

<style>

</style>