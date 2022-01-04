<template>
    <div>
        <b-alert :show="dismissCountDown" dismissible variant="success"
            @dismissed="dismissCountDown=0" @dismiss-count-down="countDownChanged">
            El recurso se {{ !edit ? 'guardo':'actualizo' }} correctamente.
        </b-alert>
    </div>
</template>

<script>
export default {
    props: ['dcd','sda', 'edit'],
    data(){
        return {
            dismissCountDown: this.dcd,
            showDismissibleAlert: this.sda
        }
    },
    methods: {
        // QUE APAREZCA ALERT
        countDownChanged(dismissCountDown) {
            this.dismissCountDown = dismissCountDown
        },
    }
}
</script>

<style>

</style>