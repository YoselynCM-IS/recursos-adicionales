<template>
    <div>
        <b-table v-if="!load" :items="users.data" :fields="fields"
            responsive :tbody-tr-class="rowClass">
            <template v-slot:cell(actions)="data">
                <b-button variant="warning" pill size="sm" 
                    @click="editUser(data.item, data.index)">
                    <b-icon-pencil></b-icon-pencil>
                </b-button>
                <b-button v-if="data.item.estado == 'activo'" variant="secondary" 
                    pill size="sm"  @click="des_habilitarUser(data.item, data.index, 4)">
                    <b-icon-x></b-icon-x>
                </b-button>
                <b-button v-if="data.item.estado == 'deshabilitado'" variant="success" 
                    pill size="sm" @click="des_habilitarUser(data.item, data.index, 2)">
                    <b-icon-check></b-icon-check>
                </b-button>
            </template>
            <template v-slot:cell(created_at)="data">
                {{ data.item.created_at | moment("DD-MM-YYYY") }}
            </template>
            <template v-slot:cell(inicio)="data">
                {{ data.item.acceso.inicio == null ? 'No utilizado':data.item.acceso.inicio }}
            </template>
            <template #cell(show_details)="row">
                <b-button v-if="row.item.acceso.inicio !== null"
                    size="sm" @click="row.toggleDetails" 
                    class="mr-2" variant="info" pill>
                    <b-icon-eye v-if="!row.detailsShowing"></b-icon-eye>
                    <b-icon-eye-slash v-else></b-icon-eye-slash>
                </b-button>
            </template>
            <template #row-details="row">
                <b-card>
                    <b-row class="mb-2">
                        <b-col sm="3" class="text-sm-right"><b>Nombre:</b></b-col>
                        <b-col>{{ row.item.name }}</b-col>
                    </b-row>

                    <b-row class="mb-2">
                        <b-col sm="3" class="text-sm-right"><b>Correo:</b></b-col>
                        <b-col>{{ row.item.email }}</b-col>
                    </b-row>
                </b-card>
            </template>
        </b-table>
        <load-component v-else></load-component>

        <!-- EDITAR USUARIO -->
        <b-modal ref="modal-edit-user" title="Editar usuario" size="sm" hide-footer>
            <user-save-inf-component :edit="true" :form="form" 
                @updateUser="updateUser"></user-save-inf-component>
        </b-modal>
        <!-- CAMBIAR ESTADO DEL USUARIO -->
        <b-modal ref="modal-estado-user" :title="`${estado == 2 ? 'Activar':'Desactivar'} usuario`" size="sm" hide-footer>
            <b-form @submit.prevent="onSubmit">
                <div class="text-center">
                    <p>¿Estas seguro de {{estado == 2 ? 'activar':'desactivar'}} este usuario?</p><hr>
                    <b-button type="submit" :variant="`${estado == 2 ? 'success':'danger'}`" pill size="sm">
                        Si
                    </b-button>
                </div>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
import LoadComponent from '../partials/LoadComponent.vue'
export default {
    props: ['users'],
    components: { LoadComponent },
    data(){
        return {
            load: false,
            fields: [
                { key: 'actions', label: '' },
                { key: 'created_at', label: 'Creado el' },
                { key: 'codigo', label: 'Código' },
                { key: 'acceso.libro.libro', label: 'Libro' },
                { key: 'acceso.months', label: 'Meses (Vigente)' },
                { key: 'inicio', label: 'Inicio el' },
                { key: 'acceso.final', label: 'Termina el' },
                { key: 'show_details', label: '' }
            ],
            form: {
                id: null,
                name: null,
                email: null,
                months: null,
                inicio: null
            }, 
            position: null,
            formEdo: { 
                id: null,
                estado: null 
            },
            estado: null
        }
    },
    methods: {
        // COLOR DE LAS FILAS SEGUN EL ESTADO DE CADA CODIGO
        rowClass(item, type) {
            if(!item) return
            if(item.estado == 'activo') return 'table-success'
            if(item.estado == 'vencido') return 'table-secondary'
            if(item.estado == 'deshabilitado') return 'table-danger'
        },
        // EDITAR USUARIO
        editUser(user, position){
            this.form.id = user.id;
            this.form.name = user.name;
            this.form.email = user.email;
            this.form.months = user.acceso.months;
            this.form.inicio = user.acceso.inicio;
            this.position = position;
            this.$refs['modal-edit-user'].show();
        },
        // USUARIO ACTUALIZADO
        updateUser(user){
            this.users.data[this.position].name = user.name;
            this.users.data[this.position].email = user.email;
            this.users.data[this.position].acceso.months = user.months;
            this.users.data[this.position].acceso.inicio = user.inicio;
            this.users.data[this.position].acceso.final = user.final;
            this.$refs['modal-edit-user'].hide();
            swal("OK", "El usuario se actualizo correctamente.", "success")
        },
        // DESHABILITAR/HABILITAR USUARIO
        des_habilitarUser(user, position, estado){
            this.formEdo.id = user.id;
            this.formEdo.estado = estado;
            this.position = position;
            this.estado = estado;
            this.$refs['modal-estado-user'].show();
        },
        // GUARDAR CAMBIO DE ESTADO DEL USUARIO
        onSubmit(){
            this.load = true;
            axios.put('/users/des_habilitar', this.formEdo).then(response => {
                this.users.data[this.position].estado = response.data.estado;
                this.$refs['modal-estado-user'].hide();
                swal("OK", "El usuario se actualizo correctamente.", "success");
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