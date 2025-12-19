<template>
    <div>
        <!-- ENCABEZADO -->
        <b-row class="mb-3">
            <b-col sm="4">
                <!-- PAGINACION -->
                <pagination size="default" :limit="1" 
                    :data="libros" @pagination-change-page="get_results">
                    <span slot="prev-nav">
                        <b-icon-chevron-left></b-icon-chevron-left>
                    </span>
                    <span slot="next-nav">
                        <b-icon-chevron-right></b-icon-chevron-right>
                    </span>
                </pagination>
            </b-col>
            <b-col>
                <b-form-group>
                    <b-form-input v-model="queryLibro" @keyup="showBooks()"
                        placeholder="Buscar libro">
                    </b-form-input>
                </b-form-group>
                <b-form-group>
                    <b-form-select v-model="queryTipo" :options="tipos"
                        required :disabled="load" @change="getTipo"></b-form-select>
                </b-form-group>
            </b-col>
            <b-col sm="2" v-if="role == 'admin'">
                <b-button pill variant="dark" block
                    :disabled="queryLibro == null && queryTipo == null"
                    :href="`/libros/download_bysearch/${queryLibro}/${queryTipo}`">
                    <b-icon-download></b-icon-download> Descargar
                </b-button>
            </b-col>
            <b-col sm="2">
                <b-button v-if="role == 'admin'" variant="success" pill block @click="newLibro()">
                    <b-icon-plus-circle></b-icon-plus-circle> Agregar libro
                </b-button>
                <b-button variant="info" pill block @click="showRecursos = true">
                    <b-icon-journals></b-icon-journals> Recursos
                </b-button>
            </b-col>
        </b-row>
        <!-- LIBROS -->
        <b-table v-if="!load" responsive :items="libros.data" 
            :fields="fieldsLibros" :tbody-tr-class="rowClass">
            <template v-slot:cell(index)="data">
                {{ data.index + 1 }}
            </template>
            <template v-slot:cell(tipo_id)="data">
                {{ setTipo(data.item.tipo_id) }}
            </template>
            <template v-slot:cell(recursos)="data">
                <b-button variant="info" size="sm" pill @click="getRecursos(data.item)">
                    <b-icon-journals></b-icon-journals>
                </b-button>
                <b-button variant="success" size="sm" pill @click="addRecursos(data.item)">
                    <b-icon-journal-plus></b-icon-journal-plus>
                </b-button>
            </template>
            <template v-slot:cell(actions)="data">
                <b-button v-if="role == 'admin'" variant="warning" pill size="sm"
                    @click="editLibro(data.item, data.index)">
                    <b-icon-pencil></b-icon-pencil>
                </b-button>
                <div v-if="role == 'admin'">
                    <b-button v-if="data.item.estado == 'activo'" variant="secondary" 
                        pill size="sm" @click="de_activateLibro(data.item, data.index, 2)">
                        <b-icon-x></b-icon-x>
                    </b-button>
                    <b-button v-else variant="success" pill size="sm"
                        @click="de_activateLibro(data.item, data.index, 1)">
                        <b-icon-check></b-icon-check>
                    </b-button>
                </div>
            </template>
        </b-table>
        <load-component v-else></load-component>
        <!-- MODALS -->
        <!-- AGREGAR LIBRO -->
        <b-modal ref="modal-libro" :title="`${!edit ? 'Agregar':'Editar'} libro`" size="sm" hide-footer>
            <libros-add-edit-component :form="formLibro" :edit="edit"
                @updateLibro="updateLibro"></libros-add-edit-component>
        </b-modal>
        <!-- DESACTIVAR LIBRO -->
        <b-modal ref="modal-libro-desactivar" :title="`${estado == 2 ? 'Desactivar':'Activar'} libro`" size="sm" hide-footer>
            <b-form @submit.prevent="onSubmit">
                <div class="text-center">
                    <p>¿Estas seguro de {{estado == 2 ? 'desactivar':'activar'}} este libro?</p><hr>
                    <b-button type="submit" :variant="`${estado == 2 ? 'danger':'success'}`" pill size="sm">
                        Si
                    </b-button>
                </div>
            </b-form>
        </b-modal>
        <!-- MOSTRAR RECURSOS -->
        <b-modal id="modal-recursos" v-model="showRecursos" title="Recursos" size="lg" hide-footer>
            <recursos-lista-component></recursos-lista-component>
        </b-modal>
        <!-- MOSTRAR RECURSOS DEL LIBRO -->
        <b-modal ref="modal-recursos-libro" :title="`Recursos de ${libro.libro}`" size="lg" hide-footer>
            <recursos-libro-component :libro="libro"></recursos-libro-component>
        </b-modal>
        <!-- AGREGAR RECURSOS DEL LIBRO -->
        <b-modal ref="modal-add-recursos" title="Agregar recursos" hide-footer>
            <libros-add-recursos-component :form="formLRec" 
                :options="recursos" @savedResources="savedResources"></libros-add-recursos-component>
        </b-modal>
    </div>
</template>

<script>
import LoadComponent from '../partials/LoadComponent.vue';
import setTipo from '../../mixins/setTipo';
import TiposMixin from '../../mixins/TiposMixin';
export default {
    props: ['role'],
    components: {LoadComponent},
    mixins: [setTipo,TiposMixin],
    data(){
        return {
            formLibro: {
                id: null,
                tipo_id: null,
                code: null,
                libro: null
            },
            libros: {},
            load: false,
            fieldsLibros: [
                { key: 'index', label: 'N.' },
                { key: 'tipo_id', label: 'Tipo' },
                { key: 'code', label: 'Código' },
                { key: 'libro', label: 'Libro' },
                { key: 'recursos', label: 'Recursos' },
                { key: 'actions', label: '' },
            ],
            position: null,
            edit: false,
            formLDes: { 
                id: null,
                estado: null 
            },
            showRecursos: false,
            recursos: [],
            formLRec: {
                id: null,
                selected: []
            },
            libro: {
                id: null,
                libro: null,
                recursos: []
            },
            estado: null,
            queryLibro: null,
            queryTipo: null
        }
    },
    created: function (){
        this.get_results();
    },
    methods: {
        // OBTENER PAGINACIÓN
        get_results(page = 1){
            if(this.queryLibro == null && this.queryTipo == null) 
                this.http_libros(page);
            if(this.queryLibro != null) this.showBooks(page);
            if(this.queryTipo != null) this.getTipo(page);
        },
        // OBTENER LIBROS
        http_libros(page = 1){
            this.load = true;
            axios.get(`/libros/index?page=${page}`).then(response => {
                this.libros = response.data;
                this.load = false;
            }).catch(error => {
                this.load = false;
            });
        },
        // AGREGAR LIBRO
        newLibro(){
            this.formLibro = {
                id: null,
                tipo_id: null,
                code: null,
                libro: null
            };
            this.edit = false;
            this.$refs['modal-libro'].show();
        },
        // EDITAR LIBRO
        editLibro(libro, index){
            this.formLibro.id = libro.id;
            this.formLibro.tipo_id = libro.tipo_id;
            this.formLibro.code = libro.code;
            this.formLibro.libro = libro.libro;
            this.position = index;
            this.edit = true;
            this.$refs['modal-libro'].show();
        },
        // LIBRO ACTUALIZADO
        updateLibro(libro){
            this.libros.data[this.position].tipo_id = libro.tipo_id;
            this.libros.data[this.position].code = libro.code;
            this.libros.data[this.position].libro = libro.libro;
            this.$refs['modal-libro'].hide();
            swal("OK", "El libro se actualizo correctamente.", "success");
        },
        // DESACTIVAR LIBRO
        de_activateLibro(libro, index, estado){
            this.formLDes.id = libro.id;
            this.formLDes.estado = estado;
            this.position = index;
            this.estado = estado;
            this.$refs['modal-libro-desactivar'].show();
        },
        // GUARDAR CAMBIOS DEL LIBRO
        onSubmit(){
            this.load = true;
            axios.put('/libros/des_activar', this.formLDes).then(response => {
                this.libros.data[this.position].estado = response.data.estado;
                this.$refs['modal-libro-desactivar'].hide();
                swal("OK", "El libro se actualizo correctamente.", "success");
                this.load = false;
            }).catch(error => {
                this.load = false;
            });
        },
        // COLOR DE LAS FILAS SEGUN EL ESTADO DE CADA CODIGO
        rowClass(item, type) {
            if(!item) return
            if(item.estado == 'inactivo') return 'table-secondary'
        },
        // OBTENER LOS RECURSOS DEL LIBRO
        getRecursos(libro){
            this.load = true;
            axios.get('/libros/recursos_bylibro', {params: { libro_id: libro.id }}).then(response => {
                this.libro = {
                    id: response.data.id,
                    libro: response.data.libro,
                    recursos: response.data.recursos
                };
                this.$refs['modal-recursos-libro'].show();
                this.load = false;
            }).catch(error => {
                this.load = false;
            });
        },
        // AGREGAR RECURSOS AL LIBRO
        addRecursos(libro){
            this.load = true;
            this.recursos = [];
            axios.get('/libros/available_recursos', {params: { libro_id: libro.id }}).then(response => {
                this.formLRec.id = libro.id;
                this.formLRec.selected = [];
                response.data.forEach(r => {
                    this.recursos.push({ value: r.id, text: `${r.recurso}` });
                });
                this.$refs['modal-add-recursos'].show();
                this.load = false;
            }).catch(error => {
                this.load = false;
            });
        },
        // RECURSOS GUARDADOS
        savedResources(libro){
            this.$refs['modal-add-recursos'].hide();
            swal("OK", "Los recursos se guardaron correctamente.", "success");
        },
        // MOSTRAR LIBROS
        showBooks(page = 1){
            axios.get(`/libros/show_paginate?page=${page}`, {params: { libro: this.queryLibro }}).then(response => {
                this.libros = response.data;
                this.queryTipo = null;
            }).catch(error => {
            });
        },
        // OBTENER LIBROS POR TIPO
        getTipo(page = 1){
            this.load = true;
            axios.get(`/libros/by_tipo?page=${page}`, {params: {tipo_id: this.queryTipo}}).then(response => {
                this.libros = response.data;
                this.queryLibro = null;
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