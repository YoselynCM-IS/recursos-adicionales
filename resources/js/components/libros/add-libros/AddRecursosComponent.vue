<template>
    <div>
        <b-form @submit.prevent="onSubmit">
            <b-form-group label="Selecciona los recursos:" v-slot="{ ariaDescribedby }">
                <b-form-checkbox-group 
                    v-model="form.selected" :options="options"
                    :aria-describedby="ariaDescribedby"
                    name="recursos_id" stacked :disabled="load"
                ></b-form-checkbox-group>
            </b-form-group>
            <submit-component :load="load" textG="Guardar" textC="Guardando..."></submit-component>
        </b-form>
    </div>
</template>

<script>
import SubmitComponent from '../../partials/SubmitComponent.vue'
export default {
  components: { SubmitComponent },
    props: ['options', 'form'],
    data(){
        return {
            load: false
        }
    },
    methods: {
        // GUARDAR RECURSOS EN EL LIBRO
        onSubmit(){
            this.load = true;
            axios.post('/libros/save_recursos', this.form).then(response => {
                this.$emit('savedResources', response.data);
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