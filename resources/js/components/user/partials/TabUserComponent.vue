<template>
    <div>
        <b-tab :title="title" @click="get_users(role)">
            <b-row>
                <b-col>
                    <b-form-group>
                        <b-form-input v-model="libro" @keyup="showBooks()"
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
                        <b-form-select v-model="estado" :options="estados"
                            required :disabled="load" @change="http_estado"></b-form-select>
                    </b-form-group>
                </b-col>
                <b-col sm="2">
                    <b-button variant="dark" pill 
                        :disabled="libro_id == null && estado == null"
                        :href="`/users/download_bysearch/${role_id}/${libro_id}/${estado}`">
                        <b-icon-download></b-icon-download> Descargar
                    </b-button>
                </b-col>
                <b-col>
                    <pagination size="small" align="right" :limit="1" 
                        :data="users" @pagination-change-page="get_results">
                        <span slot="prev-nav">
                            <b-icon-chevron-left></b-icon-chevron-left>
                        </span>
                        <span slot="next-nav">
                            <b-icon-chevron-right></b-icon-chevron-right>
                        </span>
                    </pagination>
                </b-col>
            </b-row>
            <registros-component v-if="!load" :users="users"></registros-component>
            <load-component v-else></load-component>
        </b-tab>
    </div>
</template>

<script>
import LoadComponent from './../../partials/LoadComponent.vue';
import RegistrosComponent from './../RegistrosComponent.vue';
import GetUsersMixin from './../../../mixins/GetUsersMixin';
export default {
    props: ['title', 'role'],
    components: { LoadComponent, RegistrosComponent },
    mixins: [GetUsersMixin],
}
</script>

<style scoped>
    #listResults{
        position: absolute;
        z-index: 100;
    }
</style>