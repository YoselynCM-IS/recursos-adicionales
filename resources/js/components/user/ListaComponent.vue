<template>
    <div>
        <b-row>
            <b-col>
                <b-form-group>
                    <b-form-input v-model="titulo" @keyup="showBooks()"
                        placeholder="Buscar por libro">
                    </b-form-input>
                    <div class="list-group" v-if="libros.length" id="listResults">
                        <a v-for="(libro, i) in libros"  v-bind:key="i" 
                            class="list-group-item list-group-item-action" 
                            @click="selectBook(libro)" href="#">
                            {{ libro.libro }}
                        </a>
                    </div>
                </b-form-group>
            </b-col>
            <b-col>
                <b-form-group>
                    <b-form-input v-model="code" @keyup="showCodes()" placeholder="Buscar por código">
                    </b-form-input>
                </b-form-group>
            </b-col>
        </b-row>
        <b-row class="mb-3">
            <b-col>
                <pagination :limit="1" 
                    :data="users" @pagination-change-page="get_results">
                    <span slot="prev-nav">
                        <b-icon-chevron-left></b-icon-chevron-left>
                    </span>
                    <span slot="next-nav">
                        <b-icon-chevron-right></b-icon-chevron-right>
                    </span>
                </pagination>
            </b-col>
            <b-col sm="2">
                <b-button pill block variant="secondary" v-b-modal.modal-masivo>
                    Masivo (obsolete)
                </b-button>
            </b-col>
            <b-col sm="2">
                <b-button pill block variant="success" v-b-modal.modal-individual>
                    <b-icon-plus-circle></b-icon-plus-circle> Crear código
                </b-button>
            </b-col>
        </b-row>
        <registros-component v-if="total_users > 0" :users="users"></registros-component>
        <message-component v-else :variant="'secondary'" :message="'Realizar una búsqueda para mostrar los códigos.'"></message-component>
        <!-- MODALS -->
        <!-- AGREGAR INDIVIDUAL -->
        <b-modal id="modal-individual" title="Crear código" size="sm" hide-footer>
            <user-add-ind-component></user-add-ind-component>
        </b-modal>
        <!-- AGREGAR MASIVO (OBSOLET) -->
        <b-modal id="modal-masivo" title="Generar usuarios" size="sm" hide-footer>
            <user-add-mas-component></user-add-mas-component>
        </b-modal>
    </div>
</template>

<script>
import RegistrosComponent from './RegistrosComponent.vue';
import LoadComponent from '../partials/LoadComponent.vue';
import MessageComponent from '../partials/MessageComponent.vue';
import searchBook from '../../mixins/searchBook';

export default {
    components: {RegistrosComponent, LoadComponent, MessageComponent },
    mixins: [searchBook],
    data(){
        return {
            users: {},
            total_users: 0,
            load: false,
            libro_id: null,
            code: null,
            searchBook: false,
            searchCode: false
        }
    },
    methods: {
        get_results(page = 1){
            if(this.searchBook){
                this.http_libro(page);
            } else {
                this.showCodes(page);
            }
        },
        // SELECCIONAR LIBRO
        selectBook(libro){
            this.titulo = libro.libro;
            this.libros = [];
            this.libro_id = libro.id;
            this.http_libro();
        },
        // OBTENER LIBROS
        http_libro(page = 1){
            this.load = true;
            axios.get(`/codes/by_libro?page=${page}`, {params: {libro_id: this.libro_id}}).then(response => {
                this. set_values(response.data, response.data.total, true, false);
                this.load = false;
            }).catch(error => {
                this.load = false;
            });
        },
        // MOSTRAR COINCIDENCIAS DE CODIGO
        showCodes(page = 1){
            axios.get(`/codes/show?page=${page}`, {params: {code: this.code}}).then(response => {
                this. set_values(response.data, response.data.total, false, true);
            }).catch(error => { });
        },
        // ASIGNAR VALORES A LAS VARIABLES
        set_values(users, total, searchBook, searchCode){
            this.users = users;
            this.total_users = total;
            this.searchBook = searchBook;
            this.searchCode = searchCode;

            if(!this.searchBook){
                this.libro_id = null;
                this.titulo = null;
            } else {
                this.code = null;
            }
        }
    }
}
</script>

<style scoped>
    #listResults{
        position: absolute;
        z-index: 100;
    }
</style>