<template>
    <div>
        <b-card no-body>
            <b-tabs pills card vertical>
                <b-tab title="INICIO" active>
                    <!-- ENCABEZADO -->
                    <div class="col-md-6 text-center">
                        <h6><strong>AGREGAR USUARIOS</strong></h6>
                        <b-row>
                            <b-col>
                                <b-button pill block variant="success" v-b-modal.modal-individual>
                                    <b-icon-plus-circle></b-icon-plus-circle> Individual
                                </b-button>
                            </b-col>
                            <b-col>
                                <b-button pill block variant="success" v-b-modal.modal-masivo>
                                    <b-icon-plus-circle></b-icon-plus-circle> Masivo
                                </b-button>
                            </b-col>
                        </b-row>
                    </div>
                </b-tab>
                <tab-user-component :title="'PROFESORES'" :role="2"></tab-user-component>
                <tab-user-component :title="'ALUMNOS'" :role="3"></tab-user-component>
            </b-tabs>
        </b-card>

        <!-- MODALS -->
        <!-- AGREGAR INDIVIDUAL -->
        <b-modal id="modal-individual" title="Agregar usuario" size="sm" hide-footer>
            <user-add-ind-component></user-add-ind-component>
        </b-modal>
        <!-- AGREGAR MASIVO -->
        <b-modal id="modal-masivo" title="Generar usuarios" size="sm" hide-footer>
            <user-add-mas-component></user-add-mas-component>
        </b-modal>
    </div>
</template>

<script>

import TabUserComponent from './partials/TabUserComponent.vue';
export default {
    components: { TabUserComponent },
}
</script>

<style>

</style>