<template>
    <div>
        <b-form @submit.prevent="onSubmit">
            <b-form-group label="Cantidad:">
                <b-form-input v-model="form.incremento" required :disabled="load"
                    type="number" min="1"></b-form-input>
            </b-form-group>
            <submit-component :load="load" textG="Guardar" textC="Guardando..."></submit-component>
        </b-form>
    </div>
</template>

<script>
import SubmitComponent from '../../partials/SubmitComponent.vue';
export default {
    components: { SubmitComponent },
    props: ['form'],
    data(){
        return {
            load: false
        }
    },
    methods: {
        // GENERAR USUARIOS
        onSubmit(){
            if(this.form.incremento > 0){
                this.load = true;
                axios.put('/codes/incrementar', this.form).then(response => {
                    this.$emit('updateLimite', response.data);
                    this.load = false;
                }).catch(error => {
                    this.load = false;
                });
            }
        }
    }
}
</script>

<style>

</style>