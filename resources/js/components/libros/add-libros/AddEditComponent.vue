<template>
    <div>
        <b-form @submit.prevent="onSubmit">
            <b-form-group label="Tipo:">
                <b-form-select v-model="form.tipo_id" :options="tipos"
                    required :disabled="load"></b-form-select>
            </b-form-group>
            <b-form-group label="Código:">
                <b-form-input v-model="form.code" required 
                    :disabled="load" minlength="4" maxlength="4"
                    placeholder="Ingresa el código" 
                ></b-form-input>
                <div v-for="(error, index) in errors.code" :key="index" class="text-danger">
                    {{ error }}
                </div>
            </b-form-group>
            <b-form-group label="Libro:">
                <b-form-input v-model="form.libro" required 
                    :disabled="load" minlength="5"
                    placeholder="Ingresa tu titulo" 
                ></b-form-input>
            </b-form-group>
            <submit-component :load="load" textG="Guardar" textC="Guardando..."></submit-component>
        </b-form>
    </div>
</template>

<script>
import SubmitComponent from '../../partials/SubmitComponent.vue'
import ErrorsMixin from './../../../mixins/ErrorsMixin';
import TiposMixin from './../../../mixins/TiposMixin';
export default {
    props: ['form', 'edit'],
    components: {SubmitComponent},
    mixins: [ErrorsMixin,TiposMixin],  
    methods: {
        // GUARDAR LIBRO
        onSubmit(){
            this.load = true;
            if(!this.edit){
                axios.post('/libros/store', this.form).then(response => {
                    swal("OK", "El libro se guardo correctamente.", "success")
                    .then((value) => {
                        location.reload();
                    });
                    this.load = false;
                }).catch(error => {
                    this.set_errors(error);
                    this.load = false;
                });
            } else {
                axios.put('/libros/update', this.form).then(response => {
                    this.$emit('updateLibro', response.data);
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