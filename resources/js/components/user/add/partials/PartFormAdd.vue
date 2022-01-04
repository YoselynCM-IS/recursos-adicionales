<template>
    <div>
        <b-form-group label="Rol:">
            <b-form-select v-model="form.role_id" :options="roles"
                required :disabled="load"></b-form-select>
        </b-form-group>
        <b-form-group label="Libro:">
            <b-form-input v-model="form.libro" required :disabled="load"
                @keyup="showBooks()">
            </b-form-input>
            <div class="list-group" v-if="books.length" id="listR">
                <a v-for="(book, i) in books" v-bind:key="i" 
                    class="list-group-item list-group-item-action" 
                    href="#" @click="selectBook(book)">
                    {{ book.libro }}
                </a>
            </div>
        </b-form-group>
        <b-form-group label="Meses (Vigente):">
            <b-form-input v-model="form.months" required :disabled="load"
                type="number" min="1"></b-form-input>
        </b-form-group>
        <submit-component :load="load" textG="Guardar" textC="Guardando..."></submit-component>
    </div>
</template>

<script>
import SubmitComponent from '../../../partials/SubmitComponent.vue';
export default {
  components: { SubmitComponent },
    props: ['form', 'roles', 'load'],
    data(){
        return {
            books: []
        }
    },
    methods: {
        // MOSTRAR LIBROS
        showBooks(){
            this.books = [];
            axios.get('/libros/show', {params: { libro: this.form.libro }}).then(response => {
                this.books = response.data;
            }).catch(error => {
            });
        },
        // ELEGIR LIBRO
        selectBook(book){
            this.form.libro = book.libro;
            this.form.libro_id = book.id;
            this.books = [];
        }
    }
}
</script>

<style scoped>
    #listR{
        position: absolute;
        z-index: 100;
    }
</style>