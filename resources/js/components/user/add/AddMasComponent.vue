<template>
    <div>
        <b-form @submit.prevent="onSubmit">
            <b-form-group label="Cantidad:">
                <b-form-input v-model="form.quantity" required :disabled="load"
                    type="number" min="1"></b-form-input>
            </b-form-group>
            <part-form-add :form="form" :roles="roles" :load="load"></part-form-add>
        </b-form>
    </div>
</template>

<script>
import RolesMixin from '../../../mixins/RolesMixin';
import PartFormAdd from './partials/PartFormAdd.vue'
export default {
    components: { PartFormAdd },
    mixins: [RolesMixin],
    data(){
        return {
            form: {
                quantity: 0,
                role_id: null,
                libro: null,
                libro_id: null,
                months: null,
                inicio: null,
                final: null
            },
        }
    },
    methods: {
        // GENERAR USUARIOS
        onSubmit(){
            this.load = true;
            axios.post('/users/store_mass', this.form).then(response => {
                swal("OK", "Los usuarios se crearon correctamente.", "success")
                .then((value) => {
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