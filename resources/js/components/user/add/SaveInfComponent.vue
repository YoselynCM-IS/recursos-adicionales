<template>
    <div>
        <b-form @submit.prevent="onSubmit">
            <b-form-group label="Meses (Vigente):">
                <b-form-input v-model="form.months" required :disabled="load"
                    type="number" min="1"></b-form-input>
            </b-form-group>
            <div v-if="form.name !== null">
                <b-form-group label="Inicio:">
                    <b-form-datepicker v-model="form.inicio" required :disabled="load"></b-form-datepicker>
                </b-form-group>
                <part-form :form="form" :load="load"></part-form>
            </div>
            <submit-component :load="load" textG="Guardar" textC="Guardando..."></submit-component>
        </b-form>
    </div>
</template>

<script>
import SubmitComponent from '../../partials/SubmitComponent.vue';
import PartForm from './partials/PartForm.vue';
export default {
    components: { PartForm, SubmitComponent },
    props: ['form'],
    data(){
        return {
            load: false
        }
    },
    methods: {
        // Guardar información del usuario
        onSubmit(){
            this.load = true;
            axios.put('/users/update', this.form).then(response => {
                this.$emit('updateUser', response.data);
                this.load = false;
            }).catch(error => {
                this.load = false;
            });
        }
    }
}
</script>

<style>

</style>