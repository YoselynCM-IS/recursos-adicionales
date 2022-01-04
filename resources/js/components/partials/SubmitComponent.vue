<template>
    <div>
        <div class="text-center">
            <b-button pill block variant="success" type="submit"
                :disabled="load">
                <b-icon-check-circle v-if="!load"></b-icon-check-circle> 
                <b-spinner v-else type="grow" :label="textC"></b-spinner>
                {{ !load ? textG:textC}}
            </b-button>
        </div>
    </div>
</template>

<script>
export default {
    props: ['load', 'textG', 'textC']
}
</script>

<style>

</style>