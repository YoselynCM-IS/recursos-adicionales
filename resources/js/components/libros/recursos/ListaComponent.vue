<template>
    <div>
        <!-- ENCABEZADO -->
        <b-row class="mb-3">
            <b-col>

            </b-col>
            <b-col sm="3">
                <b-button variant="success" pill block @click="newRecurso()">
                    <b-icon-plus-circle></b-icon-plus-circle> Agregar recurso
                </b-button>
            </b-col>
        </b-row>
        <div v-if="addEditRecurso">
            <b-form @submit.prevent="onSubmit">
                <b-row>
                    <b-col sm="2">
                        <b-form-group label="Tipo:">
                            <b-form-select v-model="form.role_id" :options="roles"
                                required :disabled="load"></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col sm="2">
                        <b-form-group label="Tipo:">
                            <b-form-select v-model="form.tipo_id" :options="tipos"
                                required :disabled="load"></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col>
                        <b-form-group label="Nombre:">
                            <b-form-input v-model="form.recurso" required 
                                :disabled="load" minlength="4"
                                placeholder="Ingresa el nombre del recurso" 
                            ></b-form-input>
                            <div v-for="(error, index) in errors.recurso" :key="index" class="text-danger">
                                {{ error }}
                            </div>
                        </b-form-group>
                    </b-col>
                    <b-col sm="2">
                        <submit-component :load="load" textG="Guardar" textC="Guardando..."></submit-component>
                    </b-col>
                </b-row>
            </b-form>
        </div>
        <b-alert :show="dismissCountDown" dismissible variant="success"
            @dismissed="dismissCountDown=0" @dismiss-count-down="countDownChanged">
            El recurso se {{ !edit ? 'guardo':'actualizo' }} correctamente.
        </b-alert>
        <!-- PAGINACION -->
        <pagination size="default" :limit="1" 
            :data="recursos" @pagination-change-page="get_recursos">
            <span slot="prev-nav">
                <b-icon-chevron-left></b-icon-chevron-left>
            </span>
            <span slot="next-nav">
                <b-icon-chevron-right></b-icon-chevron-right>
            </span>
        </pagination>
        <!-- RECURSOS -->
        <b-table v-if="!load" :items="recursos.data" :fields="fields"
            responsive class="mt-3">
            <template v-slot:cell(index)="data">
                {{ data.index + 1 }}
            </template>
            <template v-slot:cell(tipo_id)="data">
                {{ setTipo(data.item.tipo_id) }}
            </template>
            <template v-slot:cell(role_id)="data">
                {{ data.item.role_id == 2 ? 'teacher':'student' }}
            </template>
            <template v-slot:cell(actions)="data">
                <b-button size="sm" pill variant="warning"
                    @click="ediRecurso(data.item, data.index)">
                    <b-icon-pencil></b-icon-pencil>
                </b-button>
                <!-- <b-button size="sm" pill variant="danger">
                    <b-icon-trash></b-icon-trash>
                </b-button> -->
            </template>
        </b-table>
        <load-component v-else></load-component>
    </div>
</template>

<script>
import SubmitComponent from '../../partials/SubmitComponent.vue';
import ErrorsMixin from './../../../mixins/ErrorsMixin';
import TiposMixin from './../../../mixins/TiposMixin';
import RolesMixin from './../../../mixins/RolesMixin';
import LoadComponent from '../../partials/LoadComponent.vue';
import setTipo from './../../../mixins/setTipo';
export default {
  components: { SubmitComponent, LoadComponent },
    mixins: [ErrorsMixin,TiposMixin,RolesMixin,setTipo],
    data(){
        return {
            form: {
                id: null,
                role_id: null,
                tipo_id: null,
                recurso: null
            },
            addEditRecurso: false,
            load: false,
            dismissSecs: 5,
            dismissCountDown: 0,
            showDismissibleAlert: false,
            recursos: {},
            fields: [
                { key: 'index', label: 'N.' },
                { key: 'role_id', label: 'Rol' },
                { key: 'tipo_id', label: 'Tipo' },
                { key: 'recurso', label: 'Recurso' },
                { key: 'actions', label: '' }
            ],
            position: null,
            edit: false
        }
    },
    created: function(){
        this.get_recursos();
    },
    methods: {
        // OBTENER RECURSOS
        get_recursos(page = 1){
            this.load = true;
            axios.get(`/libros/get_recursos?page=${page}`).then(response => {
                this.recursos = response.data;
                this.load = false;
            }).catch(error => {
                this.load = false;
            });
        },
        // AGREGAR RECURSO
        newRecurso(){
            this.set_recurso(null, null, null, null, false, true);
        },
        // GUARDAR RECURSO
        onSubmit(){
            this.load = true;
            if(!this.edit){
                axios.post('/libros/store_recurso', this.form).then(response => {
                    this.recursos.data.unshift({
                        id: response.data.id, 
                        role_id: response.data.role_id,
                        tipo_id: response.data.tipo_id,
                        recurso: response.data.recurso
                    });
                    this.addEditRecurso = false;
                    this.dismissCountDown = this.dismissSecs;
                    this.load = false;
                }).catch(error => {
                    this.set_errors(error);
                    this.load = false;
                });
            } else {
                axios.put('/libros/update_recurso', this.form).then(response => {
                    this.recursos.data[this.position].role_id = response.data.role_id;
                    this.recursos.data[this.position].tipo_id = response.data.tipo_id;
                    this.recursos.data[this.position].recurso = response.data.recurso;
                    this.addEditRecurso = false;
                    this.dismissCountDown = this.dismissSecs;
                    this.load = false;
                }).catch(error => {
                    this.set_errors(error);
                    this.load = false;
                });
            }
        },
        // QUE APAREZCA ALERT
        countDownChanged(dismissCountDown) {
            this.dismissCountDown = dismissCountDown
        },
        // EDITAR RECURSO
        ediRecurso(recurso, position){
            this.position = position;
            this.set_recurso(recurso.id, recurso.role_id, recurso.tipo_id, recurso.recurso, true, true);
        },
        // INICIALIZAR FORM RECURSO
        set_recurso(id, role_id, tipo_id, recurso, edit, addEditRecurso){
            this.form.id = id;
            this.form.role_id = role_id;
            this.form.tipo_id = tipo_id;
            this.form.recurso = recurso;
            this.edit = edit;
            this.addEditRecurso = addEditRecurso;
        },
    }
}
</script>

<style>

</style>