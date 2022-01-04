<template>
    <div>
        <b-form @submit.prevent="onSubmit">
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
                inicio: null,
                final: null
            }
        }
    },
    methods: {
        // GUARDAR USUARIO
        onSubmit(){
            this.load = true;
            axios.post('/users/store', this.form).then(response => {
                swal("OK", "El usuario se guardo correctamente.", "success")
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