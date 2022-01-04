<template>
    <div>
        <b-row>
            <b-col>

            </b-col>
            <b-col sm="5" class="text-right">
                <b-button pill variant="success" @click="newEnlace()">
                    <b-icon-plus-circle></b-icon-plus-circle> Agregar enlace
                </b-button>
            </b-col>
        </b-row>
        <!-- AGREGAR ENLACE -->
        <div class="mt-3 mb-3">
            <b-form v-if="addEditEnlace" @submit.prevent="onSubmit">
                <b-row>
                    <b-col sm="2">
                        <b-form-select v-model="form.tipo" :options="tipos"
                            required :disabled="load"></b-form-select>
                    </b-col>
                    <b-col>
                        <b-form-input v-model="form.nombre" required 
                            :disabled="load" minlength="3"
                            placeholder="Nombre del enlace" 
                        ></b-form-input>
                    </b-col>
                    <b-col>
                        <b-form-input v-model="form.link" required 
                            :disabled="load" minlength="5" type="url"
                            placeholder="Link del enlace" 
                        ></b-form-input>
                    </b-col>
                    <b-col sm="2">
                        <submit-component :load="load" textG="" textC=""></submit-component>
                    </b-col>
                </b-row>
            </b-form>
            <b-alert :show="dismissCountDown" dismissible variant="success"
                @dismissed="dismissCountDown=0" @dismiss-count-down="countDownChanged">
                El enlace se {{ !edit ? 'guardo':'actualizo' }} correctamente.
            </b-alert>
        </div>
        <!-- ELIMINAR ENLACE -->
        <b-alert variant="danger" class="mt-3 mb-3" dismissible
            :show="borrarEnlace" @dismissed="borrarEnlace=false">
            <b-form @submit.prevent="onDelete">
                <b-row class="text-center">
                    <b-col>
                        <p>¿Estas seguro de eliminar el enlace?</p>
                    </b-col>
                    <b-col sm="2">
                        <b-button type="submit" variant="danger" pill size="sm">
                            Si
                        </b-button>
                    </b-col>
                </b-row>
            </b-form>
        </b-alert>
        <!-- TABLA DE ENLACES -->
        <b-table v-if="!load" :items="enlaces" :fields="fields" responsive>
            <template v-slot:cell(index)="data">
                {{ data.index + 1 }}
            </template>
            <template v-slot:cell(actions)="data">
                <b-button size="sm" variant="warning" pill 
                    @click="editEnlace(data.item, data.index)">
                    <b-icon-pencil></b-icon-pencil>
                </b-button>
                <b-button size="sm" variant="danger" pill 
                    @click="deleteEnlace(data.item, data.index)">
                    <b-icon-trash></b-icon-trash>
                </b-button>
            </template>
        </b-table>
        <load-component v-else></load-component>
    </div>
</template>

<script>
import LoadComponent from '../../partials/LoadComponent.vue';
import SubmitComponent from '../../partials/SubmitComponent.vue';
import getEnlacesMixin from '../../../mixins/getEnlacesMixin';
export default {
    components: { SubmitComponent, LoadComponent },
    mixins: [getEnlacesMixin],
    props: ['form'],
    data(){
        return {
            addEditEnlace: false,
            dismissSecs: 5,
            dismissCountDown: 0,
            showDismissibleAlert: false,
            edit: false,
            fields: [
                { key: 'index', label: 'N.' },
                { key: 'tipo', label: 'Tipo' },
                { key: 'nombre', label: 'Nombre' },
                { key: 'link', label: 'Link' },
                { key: 'actions', label: '' }
            ],
            position: null,
            enlace_id: null,
            borrarEnlace: false,
            tipos: [
                { value: null, text: 'Selecciona una opción', disabled: true },
                { value: 'sitio', text: 'Sitio' },
                { value: 'track', text: 'Track' }
            ]
        }
    },
    created: function(){
        this.get_enlaces(this.form.libro_id, this.form.recurso_id);
    },
    methods: {
        // AGREGAR ENLACE
        newEnlace(){
            this.set_enlace(false, null, null, null, null);
        },
        // GUARDAR ENLACE
        onSubmit(){
            // this.load = true;
            if(!this.edit){
                axios.post('/libros/save_enlaces', this.form).then(response => {
                    this.set_finished();
                    this.get_enlaces(this.form.libro_id, this.form.recurso_id);
                }).catch(error => {
                    this.load = false;
                });
            } else {
                axios.put('/libros/update_enlaces', this.form).then(response => {
                    this.enlaces[this.position].tipo = this.form.tipo;
                    this.enlaces[this.position].nombre = this.form.nombre;
                    this.enlaces[this.position].link = this.form.link;
                    this.set_finished();
                }).catch(error => {
                    this.load = false;
                });
            }
        },
        // PROCESO TERMINADO
        set_finished(){
            this.addEditEnlace = false;
            this.dismissCountDown = this.dismissSecs;
            this.load = false;
        },
        // QUE APAREZCA ALERT
        countDownChanged(dismissCountDown) {
            this.dismissCountDown = dismissCountDown
        },
        set_enlace(edit, id, nombre, link, tipo){
            this.addEditEnlace = true;
            this.form.id = id;
            this.form.nombre = nombre;
            this.form.link = link;
            this.form.tipo = tipo;
            this.edit = edit;
        },
        // EDITAR ENLACE
        editEnlace(enlace, position){
            this.set_enlace(true, enlace.id, enlace.nombre, enlace.link, enlace.tipo);
            this.position = position;
        },
        // ELIMINAR ENLACE
        deleteEnlace(enlace, position){
            this.enlace_id = enlace.id;
            this.position = position;
            this.borrarEnlace = true;
        },
        // CONFIRMAR ELIMINACION DE ENLACE
        onDelete(){
            this.load = true;
            axios.delete('/libros/delete_enlaces', {params: {
                enlace_id: this.enlace_id}}).then(response => {
                this.borrarEnlace = false;
                this.enlaces.splice(this.position, 1);
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