<template>
    <div>
        <b-card overlay :title="libro.libro" sub-title="Recursos"
            text-variant="white" sub-title-text-variant="white"
            img-src="https://picsum.photos/900/100/?image=4">
        </b-card>
        <!-- img-src="https://dl.dropboxusercontent.com/scl/fi/7i2s6b4ft5r186kosfxct/CLUES-ALL-AROND-1-HEADER.png?rlkey=m0bej5kp4x2jmd5yv24wzg3bh&st=fw4j7478&dl=0"
            img-height="150" -->
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
                                        :href="recurso.pivot.link">
                                        <b-icon-eye></b-icon-eye> Visualizar
                                    </b-button>
                                     <b-button v-else id="btnaction" pill size="sm"
                                        @click="show_enlaces(recurso)">
                                        <b-icon-info-circle></b-icon-info-circle> Mostrar
                                    </b-button>
                                </b-col>
                                <b-col v-if="verify_recurso(recurso.recurso)">
                                    <b-button id="btnaction" pill :href="downloadRec(recurso.pivot.link)"
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
        <b-modal ref="modal-enlaces" title="" hide-footer :size="showByTipo == 'Audios' ? 'md':'xl'">
            <div v-if="!load">
                <b-embed v-if="showByTipo == 'Games' || showByTipo == 'Flipbook'" type="iframe"
                    aspect="16by9" :src="enlaceRecurso" allowfullscreen>
                </b-embed>
                <b-table v-else :items="enlaces" :fields="fields">
                    <template v-slot:cell(view)="data">
                        <label>{{ data.item.nombre }}</label>
                        <audio :title="data.item.nombre" controls="true">
                            <source :src="set_link(data.item.link)">
                        </audio>
                        <!-- <b-button v-if="data.item.tipo == 'sitio'"
                            size="sm" pill variant="info" :href="data.item.link" target="blank">
                                <b-icon-link45deg></b-icon-link45deg> Visitar
                        </b-button>-->
                    </template>
                    <template v-slot:cell(download)="data">
                        <b-button v-if="data.item.tipo == 'track'"
                            :href="`${down_link(data.item.link)}`"
                            pill size="sm" variant="dark">
                            <b-icon-download></b-icon-download>
                        </b-button>
                    </template>
                </b-table>
            </div>
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
                { key: 'view', label: 'Audio' },
                { key: 'download', label: 'Descargar' }
            ],
            link: '',
            showByTipo: 'links',
            enlaceRecurso: null,
        }
    },
    methods: {
        // SUSTIUIR TEXTO, PARA DESCARGAR ARCHIVO
        downloadRec(link){
            return link.replace('dl=0', 'dl=1');
        },
        // MOSTRAR ENLACES
        show_enlaces(recurso){
            this.$refs['modal-enlaces'].show();
            if(recurso.recurso.includes('Games')){
                this.enlaceRecurso = recurso.pivot.link;
                this.showByTipo = 'Games';
            } 
            if(recurso.recurso.includes('Audios')) {
                this.showByTipo = 'Audios';
                this.get_enlaces(this.libro.id, recurso.id);
            }
            if(recurso.recurso.includes('Flipbook')){
                this.enlaceRecurso = recurso.pivot.link;
                this.showByTipo = 'Flipbook';
            } 
        },
        // ESTABLECER LINK DE DESCARGA
        down_link(link){;
            return link.replace('dl=0', 'dl=1');
        },
        // ESTABLECER LINK DE CONSULTA
        set_link(link){
            return link.replace("https://www.dropbox.com", "https://dl.dropboxusercontent.com");
        }
    }
}
</script>

<style>

</style>