export default {
    data(){
        return {
            titulo: null,
            libros: [],
        }
    },
    methods: {
        // MOSTRAR LIBROS
        showBooks(){
            axios.get('/libros/show', {params: {libro: this.titulo}}).then(response => {
                this.libros = response.data;
            }).catch(error => { });
        },
    }
}