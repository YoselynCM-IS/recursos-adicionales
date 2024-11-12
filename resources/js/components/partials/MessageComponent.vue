<template>
    <div>
        <b-alert show :variant="variant" class="text-center">
            <b-icon-info-circle-fill></b-icon-info-circle-fill> {{ message }}
        </b-alert>
    </div>
</template>

<script>
export default {
    props: ['variant', 'message']
}
</script>

<style>

</style>