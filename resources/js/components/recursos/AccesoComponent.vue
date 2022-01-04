<template>
    <div>
        <b-card overlay :title="libro.libro" sub-title="Recursos"
            text-variant="white"
            img-src="https://picsum.photos/900/100/?image=4">
        </b-card>
        <b-card>
            <b-row>
                <b-col v-for="(recurso, i) in libro.recursos" v-bind:key="i" sm="4">
                    <b-card style="height: 150px;" class="text-center mb-4">
                        <b-card-text><strong>{{recurso.recurso}}</strong></b-card-text>
                        <template #footer>
                            <b-row>
                                <b-col>
                                    <b-button v-if="verify_recurso(recurso.recurso)"
                                        id="btnaction" pill target="blank" size="sm" 
                                        :href="recurso.libros[0].pivot.link">
                                        <b-icon-eye></b-icon-eye> Visualizar
                                    </b-button>
                                     <b-button v-else id="btnaction" pill size="sm"
                                        @click="show_enlaces(recurso.id)">
                                        <b-icon-info-circle></b-icon-info-circle> Mostrar
                                    </b-button>
                                </b-col>
                                <b-col v-if="verify_recurso(recurso.recurso)">
                                    <b-button id="btnaction" pill :href="downloadRec(recurso.libros[0].pivot.link)"
                                        size="sm">
                                        <b-icon-download></b-icon-download> Descargar
                                    </b-button>
                                </b-col>
                            </b-row>
                        </template>
                    </b-card>
                </b-col>
            </b-row>
        </b-card>
        <!-- MODALS -->
        <!-- MOSTRAR LINKS -->
        <b-modal ref="modal-enlaces" title="Enlaces" hide-footer>
            <b-table v-if="!load" :items="enlaces" :fields="fields">
                <template v-slot:cell(view)="data">
                    <b-button v-if="data.item.tipo == 'sitio'"
                        size="sm" pill variant="info">
                            <b-icon-link45deg></b-icon-link45deg> Visitar
                    </b-button>
                    <div v-else>
                        <audio :title="data.item.nombre" controls="true">
                            <source :src="set_link(data.item.link)">
                        </audio>
                    </div>
                </template>
                <template v-slot:cell(download)="data">
                    <b-button v-if="data.item.tipo == 'track'"
                        :href="`${set_link(data.item.link)}?dl=1`"
                        pill size="sm" variant="primary">
                        <b-icon-download></b-icon-download>
                    </b-button>
                </template>
            </b-table>
            <load-component v-else></load-component>
        </b-modal>
    </div>
</template>

<script>
import VerifyRecurso from '../../mixins/VerifyRecurso';
import getEnlacesMixin from '../../mixins/getEnlacesMixin';
import LoadComponent from '../partials/LoadComponent.vue';
export default {
  components: { LoadComponent },
    props: ['libro'],
    mixins: [VerifyRecurso,getEnlacesMixin],
    data(){
        return {
            fields: [
                { key: 'nombre', label: 'Enlace' },
                { key: 'view', label: '' },
                { key: 'download', label: '' }
            ],
            link: ''
        }
    },
    methods: {
        // SUSTIUIR TEXTO, PARA DESCARGAR ARCHIVO
        downloadRec(link){
            return link.replace('dl=0', 'dl=1');
        },
        // MOSTRAR ENLACES
        show_enlaces(recurso_id){
            this.$refs['modal-enlaces'].show();
            this.get_enlaces(this.libro.id, recurso_id);
        },
        // ESTABLECER LINK
        set_link(link){
            let inicio = link.indexOf('/s/');
            let final = link.indexOf('?dl=0');
            let id = link.substring(inicio + 3, final);
            return `https://dl.dropbox.com/s/${id}`;
        },
    }
}
</script>

<style>

</style>