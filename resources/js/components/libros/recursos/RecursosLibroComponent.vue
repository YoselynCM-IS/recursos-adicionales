<template>
    <div>
        <b-table v-if="!load" responsive
            :items="libro.recursos" :fields="fieldsRecursos">
            <template v-slot:cell(index)="data">
                {{ data.index + 1 }}
            </template>
            <template v-slot:cell(actions)="data">
                <b-button v-if="verify_recurso(data.item.recurso)"
                    variant="warning" pill size="sm" 
                    @click="editRecurso(data.item, data.index)">
                    <b-icon-pencil></b-icon-pencil>
                </b-button>
                <b-button v-else @click="showEnlaces(data.item)" size="sm" 
                    pill variant="primary">
                    <b-icon-link></b-icon-link>
                </b-button>
                <b-button variant="danger" pill size="sm"
                    @click="deleteRecurso(data.item, data.index)">
                    <b-icon-trash></b-icon-trash>
                </b-button>
            </template>
        </b-table>
        <load-component v-else></load-component>
        <!-- MODALS -->
        <!-- AGREGAR LINK AL RECURSO -->
        <b-modal ref="modal-edit-recurso" title="Agregar link al recurso" size="sm" hide-footer>
            <b-form @submit.prevent="onSubmit">
                <b-form-group label="Link:">
                    <b-form-input v-model="form.link" required 
                        :disabled="load" minlength="5" type="url"
                        placeholder="Ingresa el link del recurso" 
                    ></b-form-input>
                </b-form-group>
                <submit-component :load="load" textG="Guardar" textC="Guardando..."></submit-component>
            </b-form>
        </b-modal>
        <!-- CONFIRMAR ELIMINACIÓN DEL RECURSO -->
        <b-modal ref="modal-delete-recurso" title="Eliminar recurso" size="sm" hide-footer>
            <b-form @submit.prevent="onDelete">
                <div class="text-center">
                    <p>¿Estas seguro de eliminar el recurso?</p><hr>
                    <b-button type="submit" variant="danger" pill size="sm">
                        Si
                    </b-button>
                </div>
            </b-form>
        </b-modal>
        <!-- MOSTRAR ENLACES -->
        <b-modal ref="modal-enlaces" title="Enlaces" size="lg" hide-footer>
            <recursos-enlaces-component :form="formEnlace"></recursos-enlaces-component>
        </b-modal>
    </div>
</template>

<script>
import LoadComponent from '../../partials/LoadComponent.vue';
import SubmitComponent from '../../partials/SubmitComponent.vue';
import VerifyRecurso from '../../../mixins/VerifyRecurso';
export default {
  components: { SubmitComponent, LoadComponent },
  mixins: [VerifyRecurso],
    props: ['libro'],
    data(){
        return {
            fieldsRecursos: [
                { key: 'index', label: 'N.' },
                { key: 'recurso', label: 'Recurso' },
                { key: 'pivot.link', label: 'Acceso' },
                { key: 'actions', label: '' },
            ],
            load: false,
            position: null,
            form: {
                libro_id: null,
                recurso_id: null,
                link: null
            },
            formEnlace: {
                libro_id: null,
                recurso_id: null,
                id: null,
                nombre: null,
                link: null,
                tipo: null
            }
        }
    },
    methods: {
        // EDITAR RECURSO
        editRecurso(recurso, position){
            this.set_librorecurso(recurso, position);
            this.$refs['modal-edit-recurso'].show();
        },
        // ASIGNAR VALORES DEL RECURSO
        set_librorecurso(recurso, position){
            this.form.libro_id = recurso.pivot.libro_id;
            this.form.recurso_id = recurso.pivot.recurso_id;
            this.form.link = recurso.pivot.link;
            this.position = position;
        },
        // GUARDAR RECURSO
        onSubmit(){
            this.load = true;
            axios.put('/libros/set_link', this.form).then(response => {
                this.libro.recursos[this.position].pivot.link = this.form.link;
                this.$refs['modal-edit-recurso'].hide();
                swal("OK", "El link se agrego correctamente.", "success");
                this.load = false;
            }).catch(error => {
                this.load = false;
            });
        },
        // ELIMINAR RECURSO
        deleteRecurso(recurso, position){
            this.set_librorecurso(recurso, position);
            this.$refs['modal-delete-recurso'].show();
        },
        // CONFIRMAR ELIMINACIÓN DEL RECURSO
        onDelete(){
            this.load = true;
            axios.delete('/libros/delete_recursolibro', {params: {
                libro_id: this.form.libro_id, recurso_id: this.form.recurso_id}}).then(response => {
                this.libro.recursos.splice(this.position, 1);
                this.$refs['modal-delete-recurso'].hide();
                swal("OK", "El recurso se eliminó correctamente.", "success");
                this.load = false;
            }).catch(error => {
                this.load = false;
            });
        },
        // MOSTRAR ENLACES
        showEnlaces(recurso){
            this.formEnlace.libro_id = this.libro.id;
            this.formEnlace.recurso_id = recurso.id;
            this.$refs['modal-enlaces'].show();
        }
    }
}
</script>

<style>

</style>