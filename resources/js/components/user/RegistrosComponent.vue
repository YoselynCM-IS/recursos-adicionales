<template>
    <div>
        <b-table v-if="!load" :items="users.data" :fields="fields" responsive>
            <template v-slot:cell(created_at)="data">
                {{ data.item.created_at | moment("DD-MM-YYYY") }}
            </template>
            <template v-slot:cell(details)="data">
                <b-button variant="dark" pill size="sm" 
                    @click="showUsers(data.item, data.index)">
                    <b-icon-people></b-icon-people>
                </b-button>
                <b-button variant="success" pill size="sm" 
                    @click="incrementUsers(data.item, data.index)">
                    <b-icon-plus></b-icon-plus>
                </b-button>
            </template>
        </b-table>
        <load-component v-else></load-component>
        <!-- MOSTRAR USUARIOS QUE ESTAN UTILIZANDO EL CODIGO -->
        <b-modal ref="modal-showUsers" title="Usuarios registrados" size="xl" hide-footer>
            <div v-if="total_registros > 0">
                <b-table v-if="!load" :items="registros.data" :fields="fieldsUsers"
                    responsive :tbody-tr-class="rowClass">
                    <template v-slot:cell(created_at)="data">
                        {{ data.item.created_at | moment("DD-MM-YYYY") }}
                    </template>
                    <template v-slot:cell(email_verified_at)="data">
                        <b-badge :variant="data.item.email_verified_at ? 'success':'secondary'">
                            <b-icon-check2-circle v-if="data.item.email_verified_at"></b-icon-check2-circle>
                            <b-icon-x-circle v-else></b-icon-x-circle>
                        </b-badge>
                    </template>
                    <template v-slot:cell(actions)="data">
                        <b-button variant="warning" pill size="sm" 
                            @click="editUser(data.item, data.index)">
                            <b-icon-pencil></b-icon-pencil>
                        </b-button>
                        <b-button v-if="data.item.estado == 'activo' || data.item.estado == 'deshabilitado'" 
                            :variant="data.item.estado == 'activo' ? 'secondary':'success'" 
                            pill size="sm"  @click="des_habilitarUser(data.item, data.index, 4)">
                            <b-icon-x></b-icon-x>
                        </b-button>
                    </template>
                </b-table>
                <load-component v-else></load-component>
            </div>
            <message-component v-else :variant="'secondary'" :message="'No se han registrado usuarios hasta el momento.'"></message-component>
        </b-modal>
        <!-- EDITAR USUARIO -->
        <b-modal ref="modal-edit-user" title="Editar usuario" size="sm" hide-footer>
            <user-save-inf-component :form="form" 
                @updateUser="updateUser"></user-save-inf-component>
        </b-modal>
        <!-- CAMBIAR ESTADO DEL USUARIO -->
        <b-modal ref="modal-estado-user" :title="`${formEdo.estado == 'activo' ? 'Desactivar':'Activar'} usuario`" size="sm" hide-footer>
            <b-form @submit.prevent="onSubmit">
                <div class="text-center">
                    <p>¿Estas seguro de {{formEdo.estado == 'activo' ? 'desactivar':'activar' }} este usuario?</p><hr>
                    <b-button type="submit" :variant="`${formEdo.estado == 'activo' ? 'danger':'success'}`" pill size="sm">
                        Si
                    </b-button>
                </div>
            </b-form>
        </b-modal>
        <b-modal ref="modal-incrementar" :title="`${codigo.codigo} - Incrementar usuarios`" size="sm" hide-footer>
            <increment-component :form="codigo" @updateLimite="updateLimite"></increment-component>
        </b-modal>
    </div>
</template>

<script>
import MessageComponent from '../partials/MessageComponent.vue';
import LoadComponent from '../partials/LoadComponent.vue'
import IncrementComponent from './add/IncrementComponent.vue';
export default {
    props: ['users'],
    components: { LoadComponent, MessageComponent, IncrementComponent },
    data(){
        return {
            load: false,
            fields: [
                { key: 'created_at', label: 'Creado el' },
                { key: 'role.role', label: 'Rol' },
                { key: 'libro.libro', label: 'Libro' },
                { key: 'codigo', label: 'Código' },
                { key: 'months', label: 'Meses vigente' },
                { key: 'limite', label: 'Usuarios disponibles' },
                { key: 'details', label: '' }
            ],
            fieldsUsers: [
                { key: 'name', label: 'Nombre' },
                { key: 'email', label: 'Correo' },
                { key: 'email_verified_at', label: 'Verificación de correo' },
                { key: 'acceso.inicio', label: 'Registrado el' },
                { key: 'acceso.final', label: 'Vence el' },
                { key: 'actions', label: '' }
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
            registros: {},
            total_registros: 0,
            codigo: {
                id: null,
                codigo: null,
                incremento: 0
            }
        }
    },
    methods: {
        // COLOR DE LAS FILAS SEGUN EL ESTADO DE CADA CODIGO
        rowClass(item, type) {
            if(!item) return
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
        // MOSTRAR USUARIOS REGISTRADOS CON EL CODIGO
        showUsers(code){
            this.load = true;
            this.total_registros = 0;
            axios.get('/users/by_code', {params: {code_id: code.id}}).then(response => {
                this.registros = response.data;
                this.total_registros = response.data.total;
                this.load = false;
                this.$refs['modal-showUsers'].show();
            }).catch(error => {
                this.load = false;
            });
        },
        // INCREMENTAR USUARIOS
        incrementUsers(code, position){
            this.ini_codigo(code.id, code.codigo, 0, position);
            this.$refs['modal-incrementar'].show();
        },
        // USUARIOS INCREMENTADOS
        updateLimite(){
            this.users.data[this.position].limite += parseInt(this.codigo.incremento);
            this.$refs['modal-incrementar'].hide();
            swal("OK", "Se incremento el limite de usuarios correctamente.", "success")
            this.ini_codigo(null, null, 0, null);
        },
        // INICIALIZAR VALORES DE VARIABLES CODIGO
        ini_codigo(id, codigo, incremento, position){
            this.codigo.id = id;
            this.codigo.codigo = codigo;
            this.codigo.incremento = incremento;
            this.position = position;
        },
        // USUARIO ACTUALIZADO
        updateUser(user){
            this.registros.data[this.position].name = user.name;
            this.registros.data[this.position].email = user.email;
            this.registros.data[this.position].acceso.inicio = user.acceso.inicio;
            this.registros.data[this.position].acceso.final = user.acceso.final;
            this.$refs['modal-edit-user'].hide();
            swal("OK", "El usuario se actualizo correctamente.", "success")
        },
        // DESHABILITAR/HABILITAR USUARIO
        des_habilitarUser(user, position, estado){
            this.formEdo.id = user.id;
            this.formEdo.estado = user.estado;
            this.position = position;
            this.$refs['modal-estado-user'].show();
        },
        // GUARDAR CAMBIO DE ESTADO DEL USUARIO
        onSubmit(){
            this.load = true;
            axios.put('/users/des_habilitar', this.formEdo).then(response => {
                this.registros.data[this.position].estado = response.data.estado;
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