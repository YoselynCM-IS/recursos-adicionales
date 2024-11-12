<template>
    <div>
        <b-form @submit.prevent="onSubmit">
            <b-form-group label="Limite de usuarios:">
                <b-form-input v-model="form.limite" required :disabled="load"
                    type="number" min="1"></b-form-input>
            </b-form-group>
            <part-form-add :form="form" :roles="roles" :load="load"></part-form-add>
        </b-form>
    </div>
</template>

<script>
import RolesMixin from '../../../mixins/RolesMixin';
import PartFormAdd from './partials/PartFormAdd.vue';
export default {
  components: { PartFormAdd },
  mixins: [RolesMixin],
    data(){
        return {
            form: {
                id: null,
                role_id: null,
                libro: null,
                libro_id: null,
                codigo: null,
                months: null,
                limite:  null,
                inicio: null,
                final: null
            }
        }
    },
    methods: {
        // GUARDAR USUARIO
        onSubmit(){
            this.load = true;
            axios.post('/codes/store', this.form).then(response => {
                swal("OK", "El código se ha creado correctamente.", "success").then((value) => {
                    location.reload();
                });
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